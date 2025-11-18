<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiayaPendaftaran;
use App\Models\DokumenPendaftar;
use App\Models\Gelombang;
use App\Models\JalurPendaftaran;
use App\Models\PeriodePendaftaran;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PeriodePendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $periodePendaftarans = PeriodePendaftaran::with(['gelombang', 'jalurPendaftaran', 'biayaPendaftaran'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.periode_pendaftaran.index', compact('periodePendaftarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $gelombangs = Gelombang::orderBy('nama_gelombang')->get();
        $jalurPendaftarans = JalurPendaftaran::orderBy('nama_jalur')->get();
        $biayaPendaftarans = BiayaPendaftaran::with('jalurPendaftaran')->orderBy('nama_biaya')->get();
        $dokumenPendaftars = DokumenPendaftar::orderBy('nama_dokumen')->get();

        return view('admin.periode_pendaftaran.create', compact('gelombangs', 'jalurPendaftarans', 'biayaPendaftarans', 'dokumenPendaftars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gelombang_id' => 'required|exists:gelombang,id',
            'jalur_pendaftaran_id' => 'required|exists:jalur_pendaftaran,id',
            'biaya_pendaftaran_id' => 'required|exists:biaya_pendaftaran,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'kuota' => 'required|integer|min:1|max:10000',
            'status' => 'required|in:aktif,nonaktif,draft,selesai',
            'dokumen_pendaftar_ids' => 'nullable|array',
            'dokumen_pendaftar_ids.*' => 'exists:dokumen_pendaftar,id',
            'dokumen_wajib' => 'nullable|array',
            'dokumen_catatan' => 'nullable|array',
        ]);

        // Validasi duplikasi periode
        $existingPeriode = PeriodePendaftaran::where('gelombang_id', $validatedData['gelombang_id'])
            ->where('jalur_pendaftaran_id', $validatedData['jalur_pendaftaran_id'])
            ->where(function ($query) use ($validatedData) {
                $query
                    ->whereBetween('tanggal_mulai', [$validatedData['tanggal_mulai'], $validatedData['tanggal_selesai']])
                    ->orWhereBetween('tanggal_selesai', [$validatedData['tanggal_mulai'], $validatedData['tanggal_selesai']])
                    ->orWhere(function ($q) use ($validatedData) {
                        $q->where('tanggal_mulai', '<=', $validatedData['tanggal_mulai'])->where('tanggal_selesai', '>=', $validatedData['tanggal_selesai']);
                    });
            })
            ->exists();

        if ($existingPeriode) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['duplicate' => 'Periode pendaftaran untuk gelombang dan jalur yang sama sudah ada pada rentang tanggal tersebut.']);
        }

        // Validasi biaya pendaftaran sesuai dengan jalur
        $biayaPendaftaran = BiayaPendaftaran::find($validatedData['biaya_pendaftaran_id']);
        if ($biayaPendaftaran->jalur_pendaftaran_id !== $validatedData['jalur_pendaftaran_id']) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['biaya_pendaftaran_id' => 'Biaya pendaftaran yang dipilih tidak sesuai dengan jalur pendaftaran.']);
        }

        $validatedData['kuota_terisi'] = 0;

        $periodePendaftaran = PeriodePendaftaran::create($validatedData);

        // Sync dokumen pendaftar dengan periode
        if ($request->filled('dokumen_pendaftar_ids')) {
            $dokumenData = [];
            foreach ($request->dokumen_pendaftar_ids as $dokumenId) {
                $dokumenData[$dokumenId] = [
                    'is_wajib' => isset($request->dokumen_wajib[$dokumenId]) ? true : false,
                    'catatan' => $request->dokumen_catatan[$dokumenId] ?? null,
                ];
            }
            $periodePendaftaran->dokumenPendaftars()->sync($dokumenData);
        }

        return redirect()->route('periode-pendaftaran.index')->with('success', 'Periode pendaftaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PeriodePendaftaran $periodePendaftaran): View
    {
        $periodePendaftaran->load(['gelombang', 'jalurPendaftaran', 'biayaPendaftaran', 'dokumenPendaftars']);

        return view('admin.periode_pendaftaran.show', compact('periodePendaftaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodePendaftaran $periodePendaftaran): View
    {
        $gelombangs = Gelombang::orderBy('nama_gelombang')->get();
        $jalurPendaftarans = JalurPendaftaran::orderBy('nama_jalur')->get();
        $biayaPendaftarans = BiayaPendaftaran::with('jalurPendaftaran')->orderBy('nama_biaya')->get();
        $dokumenPendaftars = DokumenPendaftar::orderBy('nama_dokumen')->get();
        
        // Load existing dokumen for this periode
        $periodePendaftaran->load('dokumenPendaftars');

        return view('admin.periode_pendaftaran.edit', compact('periodePendaftaran', 'gelombangs', 'jalurPendaftarans', 'biayaPendaftarans', 'dokumenPendaftars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PeriodePendaftaran $periodePendaftaran): RedirectResponse
    {
        $validatedData = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gelombang_id' => 'required|exists:gelombang,id',
            'jalur_pendaftaran_id' => 'required|exists:jalur_pendaftaran,id',
            'biaya_pendaftaran_id' => 'required|exists:biaya_pendaftaran,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'kuota' => 'required|integer|min:1|max:10000',
            'status' => 'required|in:aktif,nonaktif,draft,selesai',
            'dokumen_pendaftar_ids' => 'nullable|array',
            'dokumen_pendaftar_ids.*' => 'exists:dokumen_pendaftar,id',
            'dokumen_wajib' => 'nullable|array',
            'dokumen_catatan' => 'nullable|array',
        ]);

        // Validasi kuota tidak boleh lebih kecil dari kuota terisi
        if ($validatedData['kuota'] < $periodePendaftaran->kuota_terisi) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['kuota' => "Kuota tidak boleh lebih kecil dari kuota terisi ({$periodePendaftaran->kuota_terisi} pendaftar)."]);
        }

        // Validasi duplikasi periode (exclude current record)
        $existingPeriode = PeriodePendaftaran::where('gelombang_id', $validatedData['gelombang_id'])
            ->where('jalur_pendaftaran_id', $validatedData['jalur_pendaftaran_id'])
            ->where('id', '!=', $periodePendaftaran->id)
            ->where(function ($query) use ($validatedData) {
                $query
                    ->whereBetween('tanggal_mulai', [$validatedData['tanggal_mulai'], $validatedData['tanggal_selesai']])
                    ->orWhereBetween('tanggal_selesai', [$validatedData['tanggal_mulai'], $validatedData['tanggal_selesai']])
                    ->orWhere(function ($q) use ($validatedData) {
                        $q->where('tanggal_mulai', '<=', $validatedData['tanggal_mulai'])->where('tanggal_selesai', '>=', $validatedData['tanggal_selesai']);
                    });
            })
            ->exists();

        if ($existingPeriode) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['duplicate' => 'Periode pendaftaran untuk gelombang dan jalur yang sama sudah ada pada rentang tanggal tersebut.']);
        }

        // Validasi biaya pendaftaran sesuai dengan jalur
        $biayaPendaftaran = BiayaPendaftaran::find($validatedData['biaya_pendaftaran_id']);
        if ($biayaPendaftaran->jalur_pendaftaran_id !== $validatedData['jalur_pendaftaran_id']) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['biaya_pendaftaran_id' => 'Biaya pendaftaran yang dipilih tidak sesuai dengan jalur pendaftaran.']);
        }

        $periodePendaftaran->update($validatedData);

        // Sync dokumen pendaftar dengan periode
        if ($request->filled('dokumen_pendaftar_ids')) {
            $dokumenData = [];
            foreach ($request->dokumen_pendaftar_ids as $dokumenId) {
                $dokumenData[$dokumenId] = [
                    'is_wajib' => isset($request->dokumen_wajib[$dokumenId]) ? true : false,
                    'catatan' => $request->dokumen_catatan[$dokumenId] ?? null,
                ];
            }
            $periodePendaftaran->dokumenPendaftars()->sync($dokumenData);
        } else {
            // Jika tidak ada dokumen yang dipilih, hapus semua relasi
            $periodePendaftaran->dokumenPendaftars()->detach();
        }

        return redirect()->route('periode-pendaftaran.index')->with('success', 'Periode pendaftaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodePendaftaran $periodePendaftaran): RedirectResponse
    {
        // Cek apakah ada pendaftar di periode ini
        if ($periodePendaftaran->kuota_terisi > 0) {
            return redirect()->route('periode-pendaftaran.index')->with('error', 'Periode pendaftaran tidak dapat dihapus karena sudah ada pendaftar.');
        }

        $periodePendaftaran->delete();

        return redirect()->route('periode-pendaftaran.index')->with('success', 'Periode pendaftaran berhasil dihapus.');
    }

    /**
     * Get biaya pendaftaran berdasarkan jalur pendaftaran (AJAX)
     */
    public function getBiayaByJalur(Request $request)
    {
        $jalurId = $request->get('jalur_id');

        $biayaPendaftarans = BiayaPendaftaran::where('jalur_pendaftaran_id', $jalurId)
            ->orderBy('nama_biaya')
            ->get(['id', 'nama_biaya', 'jumlah_biaya']);

        return response()->json($biayaPendaftarans);
    }
}
