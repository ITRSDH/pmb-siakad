<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelengkapanPendaftar;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use App\Models\PeriodePendaftaran;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class KelengkapanPendaftarController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftar::select(['id', 'nomor_pendaftaran', 'nama_lengkap', 'email', 'periode_pendaftaran_id', 'status', 'created_at'])
            ->with([
                'periodePendaftaran:id,nama_periode,jalur_pendaftaran_id', 
                'periodePendaftaran.jalurPendaftaran:id,nama_jalur',
                'kelengkapan',
                'payments:id,pendaftar_id,status,created_at'
            ])
            ->whereHas('payments', function ($q) {
                $q->where('status', 'confirmed');
            });
            // ->where('status', '!=', 'submitted'); // Filter status selain submitted

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
            $kelengkapan = $pendaftar->kelengkapan;
            
            // Daftar kolom dokumen yang dicek
            $dokumenFields = ['pas_foto', 'ktp', 'kk', 'ijazah_skl'];
            
            // Hitung dokumen yang sudah diupload (tidak null)
            $dokumenTerupload = 0;
            $dokumenDetail = [];
            
            foreach ($dokumenFields as $field) {
                $isUploaded = $kelengkapan && !empty($kelengkapan->$field);
                if ($isUploaded) {
                    $dokumenTerupload++;
                }
                $dokumenDetail[$field] = $isUploaded;
            }
            
            $totalDokumen = count($dokumenFields);
            $persentase = $totalDokumen > 0 ? round(($dokumenTerupload / $totalDokumen) * 100) : 0;
            
            // Status kelengkapan: lengkap jika semua dokumen sudah diupload
            $statusKelengkapan = $dokumenTerupload < $totalDokumen ? 'belum_lengkap' : 'lengkap';
            
            $pendaftar->kelengkapan_dokumen = [
                'total_diperlukan' => $totalDokumen,
                'total_terupload' => $dokumenTerupload,
                'persentase' => $persentase,
                'status_kelengkapan' => $statusKelengkapan,
                'detail' => $dokumenDetail,
                'kelengkapan_id' => $kelengkapan?->id,
                'status' => $kelengkapan?->status ?? 'draft',
                'catatan_admin' => $kelengkapan?->catatan_admin
            ];
            
            return $pendaftar;
        });
        
        // Tidak ada filtering tambahan - tampilkan semua pendaftar dengan status != submitted
        
        // Ambil semua periode untuk dropdown filter
        $periodes = PeriodePendaftaran::select('id', 'nama_periode')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.kelengkapan-pendaftar.index', compact('pendaftars', 'periodes'));
    }

    public function show($id)
    {
        $pendaftar = Pendaftar::with([
            'periodePendaftaran.jalurPendaftaran', 
            'periodePendaftaran.gelombang', 
            'periodePendaftaran.biayaPendaftaran', 
            'kelengkapan',
            'prodi',
            'payments'
        ])->findOrFail($id);

        // Daftar dokumen yang diperiksa dari KelengkapanPendaftar
        $dokumenFields = [
            'pas_foto' => 'Pas Foto',
            'ktp' => 'KTP',
            'kk' => 'Kartu Keluarga',
            'ijazah_skl' => 'Ijazah/SKL'
        ];

        // Build dokumen detail dari KelengkapanPendaftar
        $dokumenDetail = collect();
        $kelengkapan = $pendaftar->kelengkapan;

        foreach ($dokumenFields as $field => $namaDokumen) {
            $isUploaded = $kelengkapan && !empty($kelengkapan->$field);
            $filePath = $isUploaded ? $kelengkapan->$field : null;
            
            $dokumenDetail->push([
                'field' => $field,
                'nama_dokumen' => $namaDokumen,
                'is_wajib' => true, // Semua dokumen dianggap wajib untuk saat ini
                'is_uploaded' => $isUploaded,
                'file_path' => $filePath,
                'uploaded_at' => $kelengkapan?->updated_at,
                'status' => $kelengkapan?->status ?? 'draft',
                'catatan_admin' => $kelengkapan?->catatan_admin ?? null
            ]);
        }

        return view('admin.kelengkapan-pendaftar.show', compact('pendaftar', 'dokumenDetail'));
    }

    public function updateStatusPendaftaran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,submitted,verified,rejected',
            'catatan_admin' => 'nullable|string|max:500'
        ]);

        $kelengkapan = KelengkapanPendaftar::findOrFail($id);
        $kelengkapan->status = $request->status;
        $kelengkapan->catatan_admin = $request->catatan_admin;
        $kelengkapan->save();

        return response()->json([
            'success' => true,
            'message' => 'Status kelengkapan berhasil diupdate'
        ]);
    }

    public function downloadDokumen($id)
    {
        $kelengkapan = KelengkapanPendaftar::with('pendaftar')->findOrFail($id);

        // Daftar dokumen yang akan di-zip
        $dokumenFields = [
            'pas_foto' => 'Pas Foto',
            'ktp' => 'KTP',
            'kk' => 'Kartu Keluarga',
            'ijazah_skl' => 'Ijazah_SKL'
        ];

        // Cek apakah ada dokumen yang tersedia
        $dokumenTersedia = [];
        foreach ($dokumenFields as $field => $nama) {
            if (!empty($kelengkapan->$field)) {
                $dokumenTersedia[$field] = [
                    'path' => $kelengkapan->$field,
                    'nama' => $nama
                ];
            }
        }

        if (empty($dokumenTersedia)) {
            return back()->with('error', 'Tidak ada dokumen yang tersedia untuk di-download.');
        }

        // Buat nama file zip berdasarkan nama lengkap
        $namaLengkap = str_replace(' ', '_', $kelengkapan->nama_lengkap);
        $namaLengkap = preg_replace('/[^A-Za-z0-9_]/', '', $namaLengkap);
        $zipFileName = $namaLengkap . '_dokumen.zip';

        // Buat file zip sementara
        $zip = new ZipArchive();
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Pastikan direktori temp ada
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            return back()->with('error', 'Gagal membuat file zip.');
        }

        // Tambahkan file ke zip
        foreach ($dokumenTersedia as $field => $dokumen) {
            $filePath = storage_path('app/public/' . $dokumen['path']);
            if (file_exists($filePath)) {
                // Ekstensi file
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $fileInZip = $dokumen['nama'] . '.' . $extension;
                $zip->addFile($filePath, $fileInZip);
            }
        }

        $zip->close();

        // Download file zip
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}
