<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PeriodePendaftaran;
use App\Models\Pendaftar;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    /**
     * Tampilkan daftar periode pendaftaran yang dibuka untuk mahasiswa (google user)
     */
    public function index(Request $request): View
    {
        $query = PeriodePendaftaran::with(['gelombang', 'jalurPendaftaran', 'biayaPendaftaran'])
            ->aktif()
            ->belumSelesai() // tampilkan yang sedang berjalan atau belum mulai
            ->orderBy('tanggal_mulai', 'asc');

        // Opsional: filter pencarian
        if ($search = trim($request->get('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_periode', 'like', "%{$search}%")->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($jalurId = $request->get('jalur')) {
            $query->where('jalur_pendaftaran_id', $jalurId);
        }

        if ($gelombangId = $request->get('gelombang')) {
            $query->where('gelombang_id', $gelombangId);
        }

        $periodes = $query->paginate(12)->withQueryString();

        // Cek status pendaftaran user untuk setiap periode
        $currentUser = auth('google')->user();
        $userRegistrations = [];
        $hasActiveRegistration = false;
        $hasOngoingRegistration = false;
        $canRegisterNew = true;
        $ongoingRegistration = null;

        if ($currentUser) {
            // Ambil semua periode yang sudah didaftari user ini
            $registeredPeriods = Pendaftar::where('google_user_id', $currentUser->id)->pluck('periode_pendaftaran_id')->toArray();

            // Cek berbagai kondisi pendaftaran
            $hasActiveRegistration = Pendaftar::hasActiveRegistration($currentUser->id);
            $hasOngoingRegistration = Pendaftar::hasOngoingRegistration($currentUser->id);
            $canRegisterNew = Pendaftar::canRegisterNewPeriod($currentUser->id);

            // Ambil data pendaftaran yang sedang berjalan jika ada
            if ($hasOngoingRegistration && !$hasActiveRegistration) {
                $ongoingRegistration = Pendaftar::getOngoingRegistration($currentUser->id);
            }

            foreach ($periodes as $periode) {
                $userRegistrations[$periode->id] = in_array($periode->id, $registeredPeriods);
            }
        }

        return view('pmb.pendaftaran.index_pendaftaran', [
            'periodes' => $periodes,
            'q' => $search ?? '',
            'jalur' => $jalurId ?? null,
            'gelombang' => $gelombangId ?? null,
            'userRegistrations' => $userRegistrations,
            'hasActiveRegistration' => $hasActiveRegistration,
            'hasOngoingRegistration' => $hasOngoingRegistration,
            'canRegisterNew' => $canRegisterNew,
            'ongoingRegistration' => $ongoingRegistration,
        ]);
    }

    /**
     * Show registration form for a periode
     */
    public function create(PeriodePendaftaran $periode)
    {
        $user = auth('google')->user();

        // Only allow if periode is aktif and available
        if (!$periode->isAvailable()) {
            abort(404);
        }

        // Cek apakah user sudah lolos tahap pendaftaran atau punya pendaftaran ongoing
        if ($user) {
            $hasActiveRegistration = Pendaftar::hasActiveRegistration($user->id);
            $hasOngoingRegistration = Pendaftar::hasOngoingRegistration($user->id);

            if ($hasActiveRegistration) {
                return redirect()->route('pmb.pendaftaran.index')
                    ->with('error', 'Anda sudah lolos tahap pendaftaran di periode lain. Tidak dapat mendaftar ke periode baru.');
            }

            if ($hasOngoingRegistration) {
                return redirect()->route('pmb.pendaftaran.index')
                    ->with('warning', 'Anda memiliki pendaftaran yang sedang berlangsung. Selesaikan terlebih dahulu sebelum mendaftar ke periode baru.');
            }
        }

        // Get available prodi
        $prodis = Prodi::orderBy('nama_prodi', 'asc')->get();

        return view('pmb.pendaftaran.form_pendaftaran', compact('periode', 'user', 'prodis'));
    }

    /**
     * Store registration
     */
    public function store(Request $request, PeriodePendaftaran $periode): RedirectResponse
    {
        $user = auth('google')->user();

        if (!$periode->isAvailable()) {
            return redirect()->route('pmb.pendaftaran.index')->with('error', 'Periode tidak tersedia untuk pendaftaran.');
        }

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'nullable|numeric|digits:16',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date|before:today',
            'alamat' => 'nullable|string|max:1000',
            'pendidikan_terakhir' => 'required|in:SMA,SMK,MA,PAKET C',
            'asal_sekolah' => 'nullable|string|max:255',
            'sumber_informasi' => 'required|in:Guru BK,Website,Telegram',
            'prodi_id' => 'required|exists:prodi,id',
        ]);

        // Prevent duplicate registration for same google user and periode
        if ($user) {
            $exists = Pendaftar::where('google_user_id', $user->id)->where('periode_pendaftaran_id', $periode->id)->exists();

            if ($exists) {
                return redirect()->route('google.dashboard')->with('info', 'Anda sudah terdaftar untuk periode ini. Silakan lengkapi biodata atau cek dashboard Anda.');
            }

            // Cek apakah user sudah lolos tahap pendaftaran atau punya pendaftaran ongoing
            $hasActiveRegistration = Pendaftar::hasActiveRegistration($user->id);
            $hasOngoingRegistration = Pendaftar::hasOngoingRegistration($user->id);

            if ($hasActiveRegistration) {
                return redirect()->route('pmb.pendaftaran.index')
                    ->with('error', 'Anda sudah lolos tahap pendaftaran di periode lain. Tidak dapat mendaftar ke periode baru.');
            }

            if ($hasOngoingRegistration) {
                return redirect()->route('pmb.pendaftaran.index')
                    ->with('warning', 'Anda memiliki pendaftaran yang sedang berlangsung. Selesaikan terlebih dahulu sebelum mendaftar ke periode baru.');
            }
        }

        // Use DB transaction with row lock to prevent race and overbooking
        $created = DB::transaction(function () use ($validated, $periode, $user) {
            // Lock the periode row for update
            $lockedPeriode = PeriodePendaftaran::where('id', $periode->id)->lockForUpdate()->first();

            // Re-check availability under lock
            if (!($lockedPeriode->status === 'aktif' && $lockedPeriode->is_berjalan && $lockedPeriode->kuota_sisa > 0)) {
                // Return null to indicate no availability
                return null;
            }

            $pendaftar = Pendaftar::create([
                'periode_pendaftaran_id' => $lockedPeriode->id,
                'google_user_id' => $user?->id,
                'prodi_id' => $validated['prodi_id'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'alamat' => $validated['alamat'],
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
                'asal_sekolah' => $validated['asal_sekolah'],
                'asal_info' => $validated['sumber_informasi'],
                'status' => 'draft',
            ]);

            // Atomic increment kuota_terisi
            $lockedPeriode->increment('kuota_terisi');

            return $pendaftar;
        });

        if (!$created) {
            return redirect()->route('pmb.pendaftaran.index')->with('error', 'Pendaftaran gagal: periode tidak tersedia atau kuota telah penuh.');
        }

        return redirect()->route('pmb.pembayaran.index')->with('success', 'Pendaftaran berhasil dibuat');
    }

    /**
     * Show detailed information about a specific periode pendaftaran
     */
    public function show(PeriodePendaftaran $periode): View
    {
        // Load all related data
        $periode->load([
            'gelombang',
            'jalurPendaftaran',
            'biayaPendaftaran'
        ]);

        $currentUser = auth('google')->user();
        $isUserRegistered = false;
        $hasActiveRegistration = false;
        $hasOngoingRegistration = false;
        $canRegisterNew = true;
        
        // Check if current user already registered for this periode
        if ($currentUser) {
            $isUserRegistered = Pendaftar::where('google_user_id', $currentUser->id)
                ->where('periode_pendaftaran_id', $periode->id)
                ->exists();

            // Cek berbagai kondisi pendaftaran
            $hasActiveRegistration = Pendaftar::hasActiveRegistration($currentUser->id);
            $hasOngoingRegistration = Pendaftar::hasOngoingRegistration($currentUser->id);
            $canRegisterNew = Pendaftar::canRegisterNewPeriod($currentUser->id);
        }

        return view('pmb.pendaftaran.show', compact('periode', 'isUserRegistered', 'hasActiveRegistration', 'hasOngoingRegistration', 'canRegisterNew'));
    }
}
