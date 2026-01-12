<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    public function index()
    {
        $user = auth('google')->user();
        $pendaftars = \App\Models\Pendaftar::with([
            'payments',
            'periodePendaftaran.dokumenPendaftars',
            'documents'
        ])
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

        $pendaftar = \App\Models\Pendaftar::where('id', $id)
            ->where('google_user_id', $user?->id)
            ->firstOrFail();

        try {
            $validated = $request->validate([
                'metode_pembayaran'   => 'required|string',
                'tanggal_pembayaran'  => 'required|date',
                'bukti_pembayaran'    => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'catatan'             => 'nullable|string|max:255',
            ]);

            // Simpan file
            $file = $request->file('bukti_pembayaran');
            $path = $file->store('pembayaran', 'public');
            $fullPath = Storage::disk('public')->path($path);

            // Data untuk body API - Flatten the array for multipart/form-data
            $postData = [
                'pendaftar[id]'       => $pendaftar->id,
                'pendaftar[nama]'     => $pendaftar->nama_lengkap,
                'pendaftar[email]'    => $pendaftar->email,
                'biaya'               => $pendaftar->periodePendaftaran->biayaPendaftaran->jumlah_biaya,
                'pembayaran[tanggal]' => $validated['tanggal_pembayaran'],
                'pembayaran[catatan]' => $validated['catatan'] ?? null,
                'filename'            => basename($path),
            ];

            // HMAC signature (keep original structure for signature if needed, or sing the flat one? 
            // The original code JSON encoded the Nested data. 
            // Since we established siakad-keu does NOT verify signature in the current code, 
            // we can leave the signature generation as is (using the nested structure) 
            // or update it. For now, I will keep the signature generation as is to minimize changes to logic 
            // that might be used elsewhere, but send the FLATTENED data to the API.

            // Re-create nested data just for signature consistency if validation existed (it doesn't in target, but good practice)
            // IMPORTANT: Cast to string to match server-side multipart/form-data parsing behavior
            $signatureData = [
                'pendaftar' => [
                    'id'    => (string) $pendaftar->id,
                    'nama'  => (string) $pendaftar->nama_lengkap,
                    'email' => (string) $pendaftar->email,
                ],
                'biaya' => (string) $pendaftar->periodePendaftaran->biayaPendaftaran->jumlah_biaya,
                'pembayaran' => [
                    'tanggal' => (string) $validated['tanggal_pembayaran'],
                    'catatan' => isset($validated['catatan']) ? (string) $validated['catatan'] : null,
                ],
                'filename' => (string) basename($path),
            ];

            $timestamp = time();
            $stringToSign = $timestamp . ".POST./api/internal/pembayaran-pmb." . json_encode($signatureData);
            $signature = hash_hmac(
                'sha256',
                $stringToSign,
                config('services.internal_api.secret')
            );

            \Log::info('HMAC DEBUG CLIENT', [
                'stringToSign' => $stringToSign,
                'timestamp'    => $timestamp,
                'secret'       => config('services.internal_api.secret'),
            ]);

            // Kirim ke API internal
            $urlapi = config('api.base_url') . 'internal/pembayaran-pmb';

            // Use fopen for reliable stream
            $fileStream = fopen($fullPath, 'r');

            $response = Http::withHeaders([
                'X-API-KEY'   => config('services.internal_api.key'),
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => $signature,
            ])
                ->attach(
                    'bukti_pembayaran',
                    $fileStream,
                    basename($path)
                )
                ->post($urlapi, $postData);

            // Cek response
            if (! $response->successful()) {
                \Log::error('API pembayaran gagal', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return back()->withErrors([
                    'msg' => 'Gagal mengirim pembayaran ke server keuangan'
                ]);
            }else{
                $pendaftar->payments()->create([
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
                    'bukti_pembayaran' => $path,
                    'catatan' => $validated['catatan'] ?? null,
                ]);
            }

            return redirect()
                ->route('pmb.pembayaran.index')
                ->with('success', 'Bukti pembayaran berhasil diupload, menunggu verifikasi.');
        } catch (\Throwable $e) {
            \Log::error('Gagal upload pembayaran', [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'msg' => 'Gagal upload pembayaran'
            ]);
        }
    }





    public function showBukti($id)
    {
        $user = auth('google')->user();
        $pembayaran = \App\Models\PendaftarPembayaran::where('id', $id)
            ->whereHas('pendaftar', function ($q) use ($user) {
                $q->where('google_user_id', $user?->id);
            })
            ->firstOrFail();

        // Cek tipe file
        $ext = pathinfo($pembayaran->bukti_pembayaran, PATHINFO_EXTENSION);
        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
        $isPdf = strtolower($ext) === 'pdf';

        // URL file
        $url = asset('storage/' . $pembayaran->bukti_pembayaran);

        return view('pmb.pembayaran.bukti', compact('pembayaran', 'url', 'isImage', 'isPdf'));
    }

    /**
     * Handle Webhook from SIAKAD-KEU
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Validate Secret
            $secret = $request->header('X-PMB-SECRET') ?? $request->input('secret');
            if ($secret !== config('services.pmb.secret', env('PMB_SECRET'))) {
                 return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $validated = $request->validate([
                'pendaftar_id' => 'required|exists:pendaftar,id',
                'status'       => 'required|in:lunas,sebagian,belum_bayar,disetujui,ditolak',
            ]);

            // Update status pembayaran pendaftaran
            // Logika: Cari pembayaran terakhir yang statusnya 'menunggu' untuk pendaftar ini
            // atau jika status 'disetujui', update jadi 'lunas' (sesuai request user "mengubah status di model pembayaran pendaftaran")
            
            $statusMap = [
                'disetujui' => 'lunas',
                'ditolak'   => 'ditolak',
            ];
            
            $newStatus = $statusMap[$validated['status']] ?? $validated['status'];

            $payment = \App\Models\PendaftarPembayaran::where('pendaftar_id', $validated['pendaftar_id'])
                ->latest()
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => $newStatus
                ]);
            }
            
            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            \Log::error('Webhook Error', ['msg' => $e->getMessage()]);
             return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
