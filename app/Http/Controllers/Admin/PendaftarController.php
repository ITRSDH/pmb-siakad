<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenPendaftar;
use App\Models\Pendaftar;
use App\Models\PeriodePendaftaran;
use Illuminate\Http\Request;

class PendaftarController extends Controller
{
    public function indexPembayaranMenunggu(Request $request)
    {
        $query = Pendaftar::select(['id', 'nomor_pendaftaran', 'nama_lengkap', 'email', 'periode_pendaftaran_id', 'status', 'created_at'])
            ->with(['periodePendaftaran:id,nama_periode,jalur_pendaftaran_id', 'periodePendaftaran.jalurPendaftaran:id,nama_jalur', 'payments:id,pendaftar_id,status,created_at'])
            ->where(function ($query) {
                // Pendaftar yang tidak memiliki pembayaran sama sekali
                $query->whereDoesntHave('payments')
                    // Atau memiliki pembayaran tetapi statusnya bukan confirmed
                    ->orWhereHas('payments', function ($q) {
                        $q->where('status', '!=', 'confirmed');
                    });
            });

        // Filter berdasarkan periode pendaftaran
        if ($request->filled('periode_id')) {
            $query->where('periode_pendaftaran_id', $request->periode_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pendaftars = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        
        // Ambil semua periode untuk dropdown filter
        $periodes = PeriodePendaftaran::select('id', 'nama_periode')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pendaftar.index_pembayaran_menunggu', compact('pendaftars', 'periodes'));
    }

    public function indexDokumenMenunggu(Request $request)
    {
        $query = Pendaftar::select(['id', 'nomor_pendaftaran', 'nama_lengkap', 'email', 'periode_pendaftaran_id', 'status', 'created_at'])
            ->with([
                'periodePendaftaran:id,nama_periode,jalur_pendaftaran_id', 
                'periodePendaftaran.jalurPendaftaran:id,nama_jalur',
                'periodePendaftaran.dokumenPendaftars',
                'documents.dokumenPendaftar',
                'payments:id,pendaftar_id,status,created_at'
            ])
            ->whereHas('payments', function ($q) {
                $q->where('status', 'confirmed');
            })
            ->where('status', '!=', 'submitted'); // Filter status selain submitted

        // Filter berdasarkan periode pendaftaran
        if ($request->filled('periode_id')) {
            $query->where('periode_pendaftaran_id', $request->periode_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pendaftars = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        
        // Tambahkan informasi kelengkapan dokumen
        $pendaftars->getCollection()->transform(function ($pendaftar) {
            $dokumenDiperlukan = $pendaftar->periodePendaftaran->dokumenPendaftars;
            $dokumenTerupload = $pendaftar->documents;
            
            $totalDokumen = $dokumenDiperlukan->count();
            $dokumenLengkap = $dokumenTerupload->count();
            $dokumenWajibDiperlukan = $dokumenDiperlukan->where('pivot.is_wajib', true)->count();
            $dokumenWajibLengkap = $dokumenTerupload->whereIn('dokumen_pendaftar_id', 
                $dokumenDiperlukan->where('pivot.is_wajib', true)->pluck('id'))->count();
            
            $pendaftar->kelengkapan_dokumen = [
                'total_diperlukan' => $totalDokumen,
                'total_terupload' => $dokumenLengkap,
                'wajib_diperlukan' => $dokumenWajibDiperlukan,
                'wajib_terupload' => $dokumenWajibLengkap,
                'persentase' => $totalDokumen > 0 ? round(($dokumenLengkap / $totalDokumen) * 100) : 0,
                'status_kelengkapan' => $dokumenWajibLengkap < $dokumenWajibDiperlukan ? 'belum_lengkap' : 'lengkap'
            ];
            
            return $pendaftar;
        });
        
        // Tidak ada filtering tambahan - tampilkan semua pendaftar dengan status != submitted
        
        // Ambil semua periode untuk dropdown filter
        $periodes = PeriodePendaftaran::select('id', 'nama_periode')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pendaftar.index_dokumen_menunggu', compact('pendaftars', 'periodes'));
    }

    public function indexPembayaranDiterima(Request $request) 
    {
        $query = Pendaftar::select(['id', 'nomor_pendaftaran', 'nama_lengkap', 'email', 'periode_pendaftaran_id', 'status', 'created_at'])
            ->with(['periodePendaftaran:id,nama_periode,jalur_pendaftaran_id', 'periodePendaftaran.jalurPendaftaran:id,nama_jalur', 'payments:id,pendaftar_id,status,created_at'])
            ->whereHas('payments', function ($q) {
                $q->where('status', 'confirmed');
            });

        // Filter berdasarkan periode pendaftaran
        if ($request->filled('periode_id')) {
            $query->where('periode_pendaftaran_id', $request->periode_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pendaftars = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        
        // Ambil semua periode untuk dropdown filter
        $periodes = PeriodePendaftaran::select('id', 'nama_periode')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pendaftar.index_pembayaran_diterima', compact('pendaftars', 'periodes'));
    }

    public function indexDokumenDiterima(Request $request)
    {
        $query = Pendaftar::select(['id', 'nomor_pendaftaran', 'nama_lengkap', 'email', 'periode_pendaftaran_id', 'status', 'created_at'])
            ->with([
                'periodePendaftaran:id,nama_periode,jalur_pendaftaran_id', 
                'periodePendaftaran.jalurPendaftaran:id,nama_jalur',
                'periodePendaftaran.dokumenPendaftars',
                'documents.dokumenPendaftar',
                'payments:id,pendaftar_id,status,created_at'
            ])
            ->whereHas('payments', function ($q) {
                $q->where('status', 'confirmed');
            })
            ->where('status', 'submitted'); // Hanya status submitted

        // Filter berdasarkan periode pendaftaran
        if ($request->filled('periode_id')) {
            $query->where('periode_pendaftaran_id', $request->periode_id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Note: Karena ada filtering setelah query, kita perlu get dulu
        $allPendaftars = $query->orderByDesc('created_at')->get();
        
        // Tambahkan informasi kelengkapan dokumen
        $allPendaftars = $allPendaftars->map(function ($pendaftar) {
            $dokumenDiperlukan = $pendaftar->periodePendaftaran->dokumenPendaftars;
            $dokumenTerupload = $pendaftar->documents;
            
            $totalDokumen = $dokumenDiperlukan->count();
            $dokumenLengkap = $dokumenTerupload->count();
            $dokumenWajibDiperlukan = $dokumenDiperlukan->where('pivot.is_wajib', true)->count();
            $dokumenWajibLengkap = $dokumenTerupload->whereIn('dokumen_pendaftar_id', 
                $dokumenDiperlukan->where('pivot.is_wajib', true)->pluck('id'))->count();
            
            $pendaftar->kelengkapan_dokumen = [
                'total_diperlukan' => $totalDokumen,
                'total_terupload' => $dokumenLengkap,
                'wajib_diperlukan' => $dokumenWajibDiperlukan,
                'wajib_terupload' => $dokumenWajibLengkap,
                'persentase' => $totalDokumen > 0 ? round(($dokumenLengkap / $totalDokumen) * 100) : 0,
                'status_kelengkapan' => $dokumenWajibLengkap >= $dokumenWajibDiperlukan ? 'lengkap' : 'belum_lengkap'
            ];
            
            return $pendaftar;
        });
        
        // Filter hanya yang dokumennya lengkap
        $filtered = $allPendaftars->filter(function ($pendaftar) {
            return $pendaftar->kelengkapan_dokumen['status_kelengkapan'] === 'lengkap';
        });
        
        // Manual paginate filtered results
        $perPage = 15;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $pendaftars = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $filtered->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );
        
        // Ambil semua periode untuk dropdown filter
        $periodes = PeriodePendaftaran::select('id', 'nama_periode')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pendaftar.index_dokumen_diterima', compact('pendaftars', 'periodes'));
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
                'status_dokumen' => $uploadedDoc ? $uploadedDoc->status_dokumen : null,
                'catatan_admin' => $uploadedDoc ? $uploadedDoc->catatan_admin : null
            ];
        });

        return view('admin.pendaftar.show', compact('pendaftar', 'dokumenDetail'));
    }

    public function updateStatusPembayaran(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:pending,confirmed,rejected',
        ]);

        $pendaftar = Pendaftar::with([
            'payments' => function ($q) {
                $q->orderByDesc('created_at');
            },
        ])->findOrFail($id);

        // Update status pembayaran terakhir jika ada
        $latestPayment = $pendaftar->payments->first();
        if ($latestPayment) {
            $latestPayment->status = $request->status_pembayaran;
            $latestPayment->save();
            
            $message = 'Status pembayaran berhasil diupdate menjadi ' . ucfirst($request->status_pembayaran);
        } else {
            $message = 'Pendaftar belum memiliki riwayat pembayaran';
        }

        return redirect()->back()->with('success', $message);
    }

    public function updateStatusPendaftaran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,submitted,rejected',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        // Update status pendaftaran (untuk konteks dokumen)
        $pendaftar->status = $request->status;
        $pendaftar->save();

        $message = 'Status dokumen pendaftar berhasil diupdate menjadi ' . ucfirst($request->status);
        
        return redirect()->back()->with('success', $message);
    }

    public function updateStatusDokumenPendaftar(Request $request, $id)
    {
        $request->validate([
            'status_dokumen' => 'required|in:disetujui,ditolak,menunggu',
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        $dokumenPendaftar = \App\Models\PendaftarDocuments::findOrFail($id);

        // Update status dokumen pendaftar
        $dokumenPendaftar->status_dokumen = $request->status_dokumen;
        $dokumenPendaftar->catatan_admin = $request->catatan_admin;
        $dokumenPendaftar->save();

        $message = 'Status dokumen pendaftar berhasil diupdate menjadi ' . ucfirst($request->status_dokumen);
        
        return redirect()->back()->with('success', $message);
    }
}
