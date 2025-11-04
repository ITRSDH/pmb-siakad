<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    public function index()
    {
        $user = auth('google')->user();
        $pendaftars = \App\Models\Pendaftar::with(['payments'])
            ->where('google_user_id', $user?->id)
            ->orderByDesc('created_at')
            ->get();
        return view('pmb.pembayaran.pembayaran_index', compact('pendaftars'));
    }

    public function show($id)
    {
        $user = auth('google')->user();
        $pendaftar = \App\Models\Pendaftar::where('id', $id)
            ->where('google_user_id', $user?->id)
            ->with('payments')
            ->firstOrFail();
        // Ambil pembayaran terakhir (atau null)
        $pembayaran = $pendaftar->payments->sortByDesc('created_at')->first();
        return view('pmb.pembayaran.tambah_pembayaran', compact('pendaftar', 'pembayaran'));
    }

    public function store(Request $request, $id)
    {
        $user = auth('google')->user();
        Log::info('Masuk ke PembayaranController@store', ['user_id' => $user?->id, 'pendaftar_id' => $id]);
        $pendaftar = \App\Models\Pendaftar::where('id', $id)
            ->where('google_user_id', $user?->id)
            ->firstOrFail();

        Log::info('Pendaftar ditemukan', ['pendaftar' => $pendaftar->toArray()]);

        try {
            $validated = $request->validate([
                'metode_pembayaran' => 'required|string',
                'tanggal_pembayaran' => 'required|date',
                'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'catatan' => 'nullable|string|max:255',
            ]);
            Log::info('Validasi pembayaran berhasil', ['validated' => $validated]);

            if (!$request->hasFile('bukti_pembayaran')) {
                Log::error('File bukti_pembayaran tidak ditemukan di request', ['request' => $request->all()]);
                return redirect()->back()->withInput()->withErrors(['msg' => 'File bukti pembayaran tidak ditemukan.']);
            }

            $file = $request->file('bukti_pembayaran');
            Log::info('File upload ditemukan', ['original_name' => $file->getClientOriginalName()]);
            $path = $file->store("pembayaran", 'public');
            Log::info('File berhasil disimpan', ['path' => $path]);

            $pembayaran = $pendaftar->payments()->create([
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
                'bukti_pembayaran' => $path,
                'status' => 'pending',
                'catatan' => $validated['catatan'] ?? null,
            ]);
            Log::info('Pembayaran berhasil dibuat', ['pembayaran' => $pembayaran->toArray()]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            Log::error('Validasi pembayaran gagal', [
                'errors' => $ve->errors(),
                'user_id' => $user?->id,
                'pendaftar_id' => $pendaftar->id,
            ]);
            return redirect()->back()->withInput()->withErrors($ve->errors());
        } catch (\Exception $e) {
            Log::error('Gagal upload pembayaran: '.$e->getMessage(), [
                'user_id' => $user?->id,
                'pendaftar_id' => $pendaftar->id,
                'request' => $request->all(),
            ]);
            return redirect()->back()->withInput()->withErrors(['msg' => 'Gagal upload pembayaran. Silakan coba lagi.']);
        }

        return redirect()->route('pmb.pembayaran.index')->with('success', 'Bukti pembayaran berhasil diupload, menunggu verifikasi.');
    }

    public function showBukti($id)
    {
        $user = auth('google')->user();
        $pembayaran = \App\Models\PendaftarPembayaran::where('id', $id)
            ->whereHas('pendaftar', function($q) use ($user) {
                $q->where('google_user_id', $user?->id);
            })
            ->firstOrFail();

        // Cek tipe file
        $ext = pathinfo($pembayaran->bukti_pembayaran, PATHINFO_EXTENSION);
        $isImage = in_array(strtolower($ext), ['jpg','jpeg','png']);
        $isPdf = strtolower($ext) === 'pdf';

        // URL file
        $url = asset('storage/' . $pembayaran->bukti_pembayaran);

        return view('pmb.pembayaran.bukti', compact('pembayaran', 'url', 'isImage', 'isPdf'));
    }
}
