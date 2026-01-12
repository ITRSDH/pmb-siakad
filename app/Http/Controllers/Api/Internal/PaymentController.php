<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Models\PendaftarPembayaran;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Get list of payments.
     * Filterable by status, date_start, date_end.
     */
    public function index(Request $request)
    {
        $query = PendaftarPembayaran::with(['pendaftar.googleUser', 'pendaftar.periodePendaftaran.biayaPendaftaran']);

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pembayaran', [
                $request->start_date, 
                $request->end_date
            ]);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by Pendaftar ID (misalnya cari spesifik)
        if ($request->filled('pendaftar_id')) {
            $query->where('pendaftar_id', $request->pendaftar_id);
        }

        $payments = $query->latest()->paginate(20);

        return response()->json($payments);
    }

    /**
     * Get payment detail by ID.
     */
    public function show($id)
    {
        $payment = PendaftarPembayaran::with(['pendaftar'])->find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return response()->json($payment);
    }
}
