<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\PeriodePendaftaran;
use Illuminate\Http\Request;

class PendaftarController extends Controller
{
    public function indexMenunggu(Request $request)
    {
        $query = Pendaftar::select(['id', 'nomor_pendaftaran', 'nama_lengkap', 'email', 'periode_pendaftaran_id', 'status', 'created_at'])
            ->with(['periodePendaftaran:id,nama_periode,jalur_pendaftaran_id', 'periodePendaftaran.jalurPendaftaran:id,nama_jalur', 'payments:id,pendaftar_id,status,created_at'])
            ->where(function ($query) {
                // Kasus 1: status != submitted dan pembayaran sudah confirmed atau rejected
                $query->where(function ($q) {
                    $q->where('status', '!=', 'submitted')
                      ->whereHas('payments', function ($q2) {
                          $q2->whereIn('status', ['confirmed', 'rejected', 'pending']);
                      });
                })
                // Kasus 2: status submitted namun pembayaran belum confirmed atau belum ada pembayaran
                ->orWhere(function ($q) {
                    $q->where('status', 'submitted')
                      ->where(function ($q2) {
                          $q2->whereDoesntHave('payments')
                             ->orWhereHas('payments', function ($q3) {
                                 $q3->where('status', '!=', 'confirmed');
                             });
                      });
                });
            });

        // Filter berdasarkan periode pendaftaran
        if ($request->filled('periode_id')) {
            $query->where('periode_pendaftaran_id', $request->periode_id);
        }

        $pendaftars = $query->orderByDesc('created_at')->get();
        
        // Ambil semua periode untuk dropdown filter
        $periodes = PeriodePendaftaran::select('id', 'nama_periode')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pendaftar.index_menunggu', compact('pendaftars', 'periodes'));
    }

    public function indexDiterima(Request $request) 
    {
        $query = Pendaftar::select(['id', 'nomor_pendaftaran', 'nama_lengkap', 'email', 'periode_pendaftaran_id', 'status', 'created_at'])
            ->with(['periodePendaftaran:id,nama_periode,jalur_pendaftaran_id', 'periodePendaftaran.jalurPendaftaran:id,nama_jalur', 'payments:id,pendaftar_id,status,created_at'])
            ->where('status', 'submitted')
            ->whereHas('payments', function ($q) {
                $q->where('status', 'confirmed');
            });

        // Filter berdasarkan periode pendaftaran
        if ($request->filled('periode_id')) {
            $query->where('periode_pendaftaran_id', $request->periode_id);
        }

        $pendaftars = $query->orderByDesc('created_at')->get();
        
        // Ambil semua periode untuk dropdown filter
        $periodes = PeriodePendaftaran::select('id', 'nama_periode')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pendaftar.index_diterima', compact('pendaftars', 'periodes'));
    }

    public function show($id)
    {
        $pendaftar = Pendaftar::with(['periodePendaftaran.jalurPendaftaran', 'periodePendaftaran.gelombang', 'periodePendaftaran.biayaPendaftaran', 'payments'])->findOrFail($id);

        return view('admin.pendaftar.show', compact('pendaftar'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,submitted,verified,rejected',
            'status_pembayaran' => 'required|in:pending,confirmed,rejected',
        ]);

        $pendaftar = Pendaftar::with([
            'payments' => function ($q) {
                $q->orderByDesc('created_at');
            },
        ])->findOrFail($id);

        // Update status pendaftaran
        $pendaftar->status = $request->status;
        $pendaftar->save();

        // Update status pembayaran terakhir jika ada
        $latestPayment = $pendaftar->payments->first();
        if ($latestPayment) {
            $latestPayment->status = $request->status_pembayaran;
            $latestPayment->save();
        }

        return redirect()->back()->with('success', 'Status berhasil diupdate.');
    }
}
