<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\PendaftarPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentStatusController extends Controller
{
    public function updateStatus(Request $request)
    {
        Log::info('Webhook received', ['payload' => $request->all()]);

        // Validasi input
        $request->validate([
            'transaction_id' => 'required', // ID Transaksi di siakad-keu
            'status'         => 'required|in:disetujui,ditolak',
            'catatan'        => 'nullable|string',
            'no_pendaftaran' => 'required|string' // Ini adalah pendaftar_id di PMB
        ]);

        $statusMapping = [
            'disetujui' => 'confirmed',
            'ditolak'   => 'rejected', 
        ];

        $newStatus = $statusMapping[$request->status] ?? $request->status;

        // Cari transaksi berdasarkan pendaftar_id
        // Kita cari pembayaran terakhir yang 'pending' milik pendaftar ini
        // atau fallback ke yang terakhir dibuat.
        $pendaftarId = $request->no_pendaftaran;
        
        $pembayaran = PendaftarPembayaran::where('pendaftar_id', $pendaftarId)
            ->where('status', 'pending')
            ->latest()
            ->first();

        // Fallback: jika tidak ada yang menunggu, ambil yang terakhir (mungkin re-koreksi status)
        if (!$pembayaran) {
            $pembayaran = PendaftarPembayaran::where('pendaftar_id', $pendaftarId)
                ->latest()
                ->first();
        }

        if (!$pembayaran) {
            Log::error('Webhook error: Pendaftar record not found', ['pendaftar_id' => $pendaftarId]);
            return response()->json(['message' => 'Payment record not found'], 404);
        }

        $pembayaran->status = $newStatus;
        if ($request->catatan) {
            $pembayaran->catatan = $request->catatan;
        }
        $pembayaran->save();

        Log::info('Payment status updated', [
            'id' => $pembayaran->id,
            'pendaftar_id' => $pendaftarId,
            'new_status' => $newStatus
        ]);

        return response()->json(['message' => 'Status updated successfully']);
    }
}
