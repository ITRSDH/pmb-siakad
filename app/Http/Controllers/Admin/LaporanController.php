<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use App\Models\PeriodePendaftaran;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Laporan Data Pendaftar
     */
    public function indexPendaftar(Request $request): View
    {
        $query = Pendaftar::with(['periodePendaftaran', 'periodePendaftaran.jalurPendaftaran', 'periodePendaftaran.gelombang', 'payments']);

        // Filter berdasarkan periode
        if ($request->filled('periode_id')) {
            $query->where('periode_pendaftaran_id', $request->periode_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_pembayaran')) {
            if ($request->status_pembayaran == 'confirmed') {
                $query->whereHas('payments', function($q) {
                    $q->where('status', 'confirmed');
                });
            } elseif ($request->status_pembayaran == 'pending') {
                $query->whereHas('payments', function($q) {
                    $q->where('status', 'pending');
                });
            } elseif ($request->status_pembayaran == 'rejected') {
                $query->whereHas('payments', function($q) {
                    $q->where('status', 'rejected');
                });
            } elseif ($request->status_pembayaran == 'belum_bayar') {
                $query->whereDoesntHave('payments');
            }
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        // Filter berdasarkan jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $pendaftars = $query->orderByDesc('created_at')->get();

        // Ambil data untuk dropdown filter
        $periodes = PeriodePendaftaran::select('id', 'nama_periode')->orderByDesc('created_at')->get();

        // Statistics
        $stats = [
            'total' => $pendaftars->count(),
            'submitted' => $pendaftars->where('status', 'submitted')->count(),
            'draft' => $pendaftars->where('status', 'draft')->count(),
            'rejected' => $pendaftars->where('status', 'rejected')->count(),
            'laki_laki' => $pendaftars->where('jenis_kelamin', 'L')->count(),
            'perempuan' => $pendaftars->where('jenis_kelamin', 'P')->count(),
            'lunas' => $pendaftars->filter(function($p) {
                return $p->payments->where('status', 'confirmed')->isNotEmpty();
            })->count(),
            'belum_bayar' => $pendaftars->filter(function($p) {
                return $p->payments->isEmpty();
            })->count(),
        ];

        return view('admin.laporan.index_pendaftar', compact('pendaftars', 'periodes', 'stats'));
    }

    /**
     * Laporan Data Pembayaran
     */
    public function indexPembayaran(Request $request): View
    {
        $query = \App\Models\PendaftarPembayaran::with(['pendaftar', 'pendaftar.periodePendaftaran']);

        // Filter berdasarkan periode
        if ($request->filled('periode_id')) {
            $query->whereHas('pendaftar', function($q) use ($request) {
                $q->where('periode_pendaftaran_id', $request->periode_id);
            });
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->whereHas('pendaftar', function($q) use ($request) {
                $q->where('jenis_kelamin', $request->jenis_kelamin);
            });
        }

        // Filter berdasarkan tanggal pembayaran
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pembayaran', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pembayaran', '<=', $request->tanggal_selesai);
        }

        $pembayarans = $query->orderByDesc('tanggal_pembayaran')->get();

        // Ambil data untuk dropdown filter
        $periodes = PeriodePendaftaran::select('id', 'nama_periode')->orderByDesc('created_at')->get();

        // Statistik
        $stats = [
            'total' => $pembayarans->count(),
            'confirmed' => $pembayarans->where('status', 'confirmed')->count(),
            'pending' => $pembayarans->where('status', 'pending')->count(),
            'rejected' => $pembayarans->where('status', 'rejected')->count(),
            'laki_laki' => $pembayarans->filter(function($p) { return $p->pendaftar && $p->pendaftar->jenis_kelamin == 'L'; })->count(),
            'perempuan' => $pembayarans->filter(function($p) { return $p->pendaftar && $p->pendaftar->jenis_kelamin == 'P'; })->count(),
        ];

        return view('admin.laporan.index_pembayaran', compact('pembayarans', 'periodes', 'stats'));
    }

    /**
     * Export Laporan Pembayaran ke CSV
     */
    public function exportPembayaran(Request $request): StreamedResponse
    {
        $query = \App\Models\PendaftarPembayaran::with(['pendaftar', 'pendaftar.periodePendaftaran']);

        if ($request->filled('periode_id')) {
            $query->whereHas('pendaftar', function($q) use ($request) {
                $q->where('periode_pendaftaran_id', $request->periode_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->whereHas('pendaftar', function($q) use ($request) {
                $q->where('jenis_kelamin', $request->jenis_kelamin);
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pembayaran', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pembayaran', '<=', $request->tanggal_selesai);
        }

        $pembayarans = $query->orderByDesc('tanggal_pembayaran')->get();

        $filename = 'laporan_pembayaran_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($pembayarans) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'No',
                'Nomor Pendaftaran',
                'Nama Lengkap',
                'Email',
                'No HP',
                'Periode',
                'Tanggal Pembayaran',
                'Metode',
                'Status',
                'Catatan'
            ]);

            foreach ($pembayarans as $i => $p) {
                fputcsv($file, [
                    $i + 1,
                    optional($p->pendaftar)->nomor_pendaftaran ?? '',
                    optional($p->pendaftar)->nama_lengkap ?? '',
                    optional($p->pendaftar)->email ?? '',
                    optional($p->pendaftar)->no_hp ?? '',
                    optional(optional($p->pendaftar)->periodePendaftaran)->nama_periode ?? '',
                    $p->tanggal_pembayaran ? \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d/m/Y') : '',
                    $p->metode_pembayaran,
                    ucfirst($p->status),
                    $p->catatan
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Laporan Pendaftar ke CSV
     */
    public function exportPendaftar(Request $request): StreamedResponse
    {
        $query = Pendaftar::with(['periodePendaftaran', 'periodePendaftaran.jalurPendaftaran', 'periodePendaftaran.gelombang', 'payments']);

        // Apply same filters as index
        if ($request->filled('periode_id')) {
            $query->where('periode_pendaftaran_id', $request->periode_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('status_pembayaran')) {
            if ($request->status_pembayaran == 'confirmed') {
                $query->whereHas('payments', function($q) {
                    $q->where('status', 'confirmed');
                });
            } elseif ($request->status_pembayaran == 'pending') {
                $query->whereHas('payments', function($q) {
                    $q->where('status', 'pending');
                });
            } elseif ($request->status_pembayaran == 'rejected') {
                $query->whereHas('payments', function($q) {
                    $q->where('status', 'rejected');
                });
            } elseif ($request->status_pembayaran == 'belum_bayar') {
                $query->whereDoesntHave('payments');
            }
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $pendaftars = $query->orderByDesc('created_at')->get();

        $filename = 'laporan_pendaftar_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($pendaftars) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($file, [
                'No',
                'Nomor Pendaftaran',
                'Nama Lengkap',
                'NIK',
                'Email',
                'No HP',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Umur',
                'Alamat',
                'Pendidikan Terakhir',
                'Periode',
                'Gelombang',
                'Jalur',
                'Status Pendaftaran',
                'Status Pembayaran',
                'Tanggal Daftar'
            ]);

            // Data
            foreach ($pendaftars as $index => $p) {
                $statusPembayaran = 'Belum Bayar';
                $latestPayment = $p->payments->sortByDesc('created_at')->first();
                if ($latestPayment) {
                    switch ($latestPayment->status) {
                        case 'confirmed':
                            $statusPembayaran = 'Lunas';
                            break;
                        case 'pending':
                            $statusPembayaran = 'Menunggu Verifikasi';
                            break;
                        case 'rejected':
                            $statusPembayaran = 'Ditolak';
                            break;
                    }
                }

                fputcsv($file, [
                    $index + 1,
                    $p->nomor_pendaftaran,
                    $p->nama_lengkap,
                    $p->nik,
                    $p->email,
                    $p->no_hp,
                    $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                    $p->tanggal_lahir ? $p->tanggal_lahir->format('d/m/Y') : '',
                    $p->umur,
                    $p->alamat,
                    $p->pendidikan_terakhir,
                    optional($p->periodePendaftaran)->nama_periode ?? '',
                    optional(optional($p->periodePendaftaran)->gelombang)->nama_gelombang ?? '',
                    optional(optional($p->periodePendaftaran)->jalurPendaftaran)->nama_jalur ?? '',
                    ucfirst($p->status),
                    $statusPembayaran,
                    $p->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
