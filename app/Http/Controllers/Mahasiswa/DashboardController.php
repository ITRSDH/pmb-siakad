<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\PeriodePendaftaran;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(): View
    {
        $googleUser = auth('google')->user();

        // Find existing pendaftar by google_user_id if available
        $pendaftar = null;
        $statusBayar = 'Belum Bayar';
        $periodeAktif = null;
        $biayaPendaftaran = null;
        $hasActiveRegistration = false;
        $hasOngoingRegistration = false;

        if ($googleUser) {
            // Cek status pendaftaran user
            $hasActiveRegistration = Pendaftar::hasActiveRegistration($googleUser->id);
            $hasOngoingRegistration = Pendaftar::hasOngoingRegistration($googleUser->id);

            // Prioritas 1: Ambil pendaftaran yang sedang berlangsung (ongoing)
            if ($hasOngoingRegistration) {
                $pendaftar = Pendaftar::getOngoingRegistration($googleUser->id);
            }
            // Prioritas 2: Ambil pendaftaran yang sudah lolos (active)
            elseif ($hasActiveRegistration) {
                $pendaftar = Pendaftar::getActiveRegistration($googleUser->id);
            }
            // Prioritas 3: Ambil pendaftaran terbaru
            else {
                $pendaftar = Pendaftar::with(['periodePendaftaran', 'payments' => function($q){ $q->orderByDesc('created_at'); }])
                    ->where('google_user_id', $googleUser->id)
                    ->latest('id')
                    ->first();
            }
            
            // Ambil status pembayaran dari pembayaran terakhir
            if ($pendaftar && $pendaftar->payments && $pendaftar->payments->count() > 0) {
                $latestPayment = $pendaftar->payments->first();
                if ($latestPayment) {
                    switch ($latestPayment->status) {
                        case 'confirmed': $statusBayar = 'Lunas'; break;
                        case 'pending': $statusBayar = 'Menunggu Verifikasi'; break;
                        case 'rejected': $statusBayar = 'Ditolak'; break;
                        default: $statusBayar = 'Belum Bayar'; break;
                    }
                }
            }

            // Gunakan periode dari pendaftaran user jika ada
            if ($pendaftar && $pendaftar->periodePendaftaran) {
                $periodeAktif = $pendaftar->periodePendaftaran;
                $biayaPendaftaran = $periodeAktif->biayaPendaftaran;
            }
        }

        // Fallback: Jika user belum punya pendaftaran, tampilkan periode yang sedang berjalan
        if (!$periodeAktif) {
            $periodeAktif = PeriodePendaftaran::aktif()->visible()->berjalan()->orderBy('tanggal_mulai', 'asc')->first();
            $biayaPendaftaran = $periodeAktif?->biayaPendaftaran;
        }

        return view('pmb.google', compact(
            'pendaftar', 
            'periodeAktif', 
            'biayaPendaftaran', 
            'statusBayar',
            'hasActiveRegistration',
            'hasOngoingRegistration'
        ));
    }
}
