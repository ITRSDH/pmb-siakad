<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use App\Models\PeriodePendaftaran;
use App\Exports\LaporanExport;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    /**
     * Laporan Gabungan Pendaftar dan Pembayaran
     */
    public function indexAll(Request $request): View
    {
        $query = Pendaftar::with([
            'periodePendaftaran', 
            'periodePendaftaran.jalurPendaftaran', 
            'periodePendaftaran.gelombang', 
            'payments'
        ]);

        // Filter berdasarkan periode
        if ($request->filled('periode_id')) {
            $query->where('periode_pendaftaran_id', $request->periode_id);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $bulan = $request->bulan;
            $tahun = $request->filled('tahun') ? $request->tahun : date('Y');
            $query->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
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

        $pendaftars = $query->orderByDesc('created_at')->get();

        // Ambil semua periode untuk dropdown filter
        $periodes = PeriodePendaftaran::select('id', 'nama_periode')
            ->orderByDesc('created_at')
            ->get();

        // Statistics
        $stats = [
            'total' => $pendaftars->count(),
            'draft' => $pendaftars->where('status', 'draft')->count(),
            'submitted' => $pendaftars->where('status', 'submitted')->count(),
            'rejected' => $pendaftars->where('status', 'rejected')->count(),
            'confirmed_payment' => $pendaftars->filter(function($p) {
                return $p->payments->contains('status', 'confirmed');
            })->count(),
            'pending_payment' => $pendaftars->filter(function($p) {
                return $p->payments->contains('status', 'pending');
            })->count(),
            'belum_bayar' => $pendaftars->filter(function($p) {
                return $p->payments->isEmpty();
            })->count(),
        ];

        return view('admin.laporan.index_all', compact('pendaftars', 'periodes', 'stats'));
    }

    /**
     * Export Laporan Gabungan ke Excel
     */
    public function exportAllExcel(Request $request)
    {
        return Excel::download(new LaporanExport($request), 'laporan_lengkap_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * Export Laporan Gabungan ke CSV
     */
    public function exportAll(Request $request): StreamedResponse
    {
        $query = Pendaftar::with([
            'periodePendaftaran', 
            'periodePendaftaran.jalurPendaftaran', 
            'periodePendaftaran.gelombang', 
            'payments'
        ]);

        // Apply same filters as indexAll
        if ($request->filled('periode_id')) {
            $query->where('periode_pendaftaran_id', $request->periode_id);
        }

        if ($request->filled('bulan')) {
            $bulan = $request->bulan;
            $tahun = $request->filled('tahun') ? $request->tahun : date('Y');
            $query->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
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

        $pendaftars = $query->orderByDesc('created_at')->get();

        $callback = function() use ($pendaftars) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            $headers = [
                'No',
                'Nomor Pendaftaran',
                'Nama Lengkap',
                'Email',
                'No. HP',
                'Jenis Kelamin',
                'Periode',
                'Jalur',
                'Status Pendaftaran',
                'Status Pembayaran',
                'Tanggal Daftar'
            ];
            fputcsv($file, $headers);

            foreach ($pendaftars as $index => $pendaftar) {
                $latestPayment = $pendaftar->payments->sortByDesc('created_at')->first();
                $statusPembayaran = 'Belum Bayar';
                
                if ($latestPayment) {
                    if ($latestPayment->status == 'confirmed') {
                        $statusPembayaran = 'Sudah Bayar';
                    } elseif ($latestPayment->status == 'pending') {
                        $statusPembayaran = 'Menunggu Verifikasi';
                    } elseif ($latestPayment->status == 'rejected') {
                        $statusPembayaran = 'Ditolak';
                    }
                }

                fputcsv($file, [
                    $index + 1,
                    $pendaftar->nomor_pendaftaran,
                    $pendaftar->nama_lengkap,
                    $pendaftar->email,
                    $pendaftar->no_hp,
                    $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                    $pendaftar->periodePendaftaran->nama_periode ?? '-',
                    $pendaftar->periodePendaftaran->jalurPendaftaran->nama_jalur ?? '-',
                    ucfirst($pendaftar->status),
                    $statusPembayaran,
                    $pendaftar->created_at->format('d M Y H:i')
                ]);
            }

            fclose($file);
        };

        $filename = 'laporan_lengkap_' . date('Y-m-d_H-i-s') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    public function show($id)
    {
        $pendaftar = Pendaftar::with([
            'periodePendaftaran.jalurPendaftaran', 
            'periodePendaftaran.gelombang', 
            'periodePendaftaran.biayaPendaftaran', 
            'periodePendaftaran.dokumenPendaftars',
            'documents.dokumenPendaftar',
            'prodi',
            'payments'
        ])->findOrFail($id);

        // Tambahkan informasi kelengkapan dokumen detail
        $dokumenDiperlukan = $pendaftar->periodePendaftaran->dokumenPendaftars;
        $dokumenTerupload = $pendaftar->documents;
        
        // Buat mapping dokumen yang sudah diupload
        $uploadedDokumenIds = $dokumenTerupload->pluck('dokumen_pendaftar_id')->toArray();
        
        // Detail dokumen untuk ditampilkan di view
        $dokumenDetail = $dokumenDiperlukan->map(function ($dokumen) use ($uploadedDokumenIds, $dokumenTerupload) {
            $isUploaded = in_array($dokumen->id, $uploadedDokumenIds);
            $uploadedDoc = $isUploaded ? $dokumenTerupload->where('dokumen_pendaftar_id', $dokumen->id)->first() : null;
            
            return [
                'id' => $dokumen->id,
                'pendaftar_document_id' => $uploadedDoc ? $uploadedDoc->id : null,
                'nama_dokumen' => $dokumen->nama_dokumen,
                'is_wajib' => $dokumen->pivot->is_wajib,
                'catatan' => $dokumen->pivot->catatan,
                'is_uploaded' => $isUploaded,
                'uploaded_at' => $uploadedDoc ? $uploadedDoc->created_at : null,
                'file_path' => $uploadedDoc ? $uploadedDoc->alamat_dokumen : null,
                'file_note' => $uploadedDoc ? $uploadedDoc->catatan : null,
                'status_dokumen' => $uploadedDoc ? $uploadedDoc->status_dokumen : null
            ];
        });

        return view('admin.laporan.show_laporan', compact('pendaftar', 'dokumenDetail'));
    }
}
