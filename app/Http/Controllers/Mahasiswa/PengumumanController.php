<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use App\Models\PendaftarPembayaran;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(): View
    {
        $user = auth('google')->user();
        
        // Ambil data pendaftar milik user yang login
        $pendaftars = collect();
        $pengumumanData = [];
        
        if ($user) {
            $pendaftars = Pendaftar::with(['periodePendaftaran', 'payments'])
                ->where('google_user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
            
            // Proses setiap pendaftar untuk mengecek status pengumuman
            foreach ($pendaftars as $pendaftar) {
                $statusPendaftar = $pendaftar->status;
                $pembayaran = $pendaftar->payments()->latest()->first();
                $statusPembayaran = $pembayaran ? $pembayaran->status : null;
                
                // Cek apakah lolos tahap pendaftaran
                $lolosTahapPendaftaran = ($statusPendaftar === 'submitted' && $statusPembayaran === 'confirmed');
                
                $pengumumanData[] = [
                    'pendaftar' => $pendaftar,
                    'pembayaran' => $pembayaran,
                    'status_pendaftar' => $statusPendaftar,
                    'status_pembayaran' => $statusPembayaran,
                    'lolos_tahap_pendaftaran' => $lolosTahapPendaftaran,
                ];
            }
        }
        
        return view('pmb.pengumuman.index', compact('pengumumanData', 'pendaftars'));
    }
}
