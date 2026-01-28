<?php

namespace App\Http\Controllers\Api\Internal;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\PeriodePendaftaran;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MahasiswaController extends Controller
{
    /**
     * Get all pendaftar with complete accepted status
     * - Pembayaran status: confirmed
     * - Status pendaftaran: submitted  
     * - All documents: approved
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Pendaftar::select([
                'id', 'nomor_pendaftaran', 'nama_lengkap', 'nik', 'email', 'no_hp', 
                'jenis_kelamin', 'tanggal_lahir', 'alamat', 'pendidikan_terakhir', 
                'asal_sekolah', 'asal_info', 'periode_pendaftaran_id', 'prodi_id', 
                'status', 'created_at', 'updated_at'
            ])->with([
                'periodePendaftaran:id,nama_periode,jalur_pendaftaran_id',
                'periodePendaftaran.jalurPendaftaran:id,nama_jalur',
                'periodePendaftaran.dokumenPendaftars' => function($q) {
                    $q->select('dokumen_pendaftar_id', 'nama_dokumen')
                      ->withPivot('is_wajib');
                },
                'prodi:id,nama_prodi,kode_prodi',
                'payments:id,pendaftar_id,status,metode_pembayaran,tanggal_pembayaran',
                'documents:id,pendaftar_id,dokumen_pendaftar_id,status_dokumen,alamat_dokumen',
                'documents.dokumenPendaftar:id,nama_dokumen'
            ]);

            // Filter dasar (sama seperti indexDokumenDiterima)
            $query->whereHas('payments', function($q) {
                      $q->where('status', 'confirmed');
                  })
                  ->where('status', 'submitted');

            // Additional filters
            if ($request->periode_id) {
                $query->where('periode_pendaftaran_id', $request->periode_id);
            }

            if ($request->prodi_id) {
                $query->where('prodi_id', $request->prodi_id);
            }

            if ($request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_pendaftaran', 'like', "%{$search}%")
                      ->orWhere('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Limit untuk performa (default 100, max 500)
            $limit = min($request->limit ?? 100, 500);
            $pendaftars = $query->orderByDesc('created_at')
                               ->limit($limit)
                               ->get();

            // Filter manual untuk dokumen lengkap (sama seperti indexDokumenDiterima)
            $filteredPendaftars = $pendaftars->filter(function ($pendaftar) {
                $dokumenDiperlukan = $pendaftar->periodePendaftaran->dokumenPendaftars ?? collect([]);
                $dokumenTerupload = $pendaftar->documents ?? collect([]);
                
                $dokumenWajibDiperlukan = $dokumenDiperlukan->where('is_wajib', true)->count();
                $dokumenWajibLengkap = $dokumenTerupload->whereIn('dokumen_pendaftar_id', 
                    $dokumenDiperlukan->where('is_wajib', true)->pluck('id'))->count();
                
                return $dokumenWajibLengkap >= $dokumenWajibDiperlukan;
            });

            // Transform data untuk response
            $transformedData = $filteredPendaftars->map(function ($pendaftar) {
                return [
                    'id' => $pendaftar->id,
                    'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran,
                    'nama_lengkap' => $pendaftar->nama_lengkap,
                    'email' => $pendaftar->email,
                    'no_hp' => $pendaftar->no_hp,
                    'jenis_kelamin' => $pendaftar->jenis_kelamin,
                    'tanggal_lahir' => $pendaftar->tanggal_lahir->format('Y-m-d'),
                    'alamat' => $pendaftar->alamat,
                    'pendidikan_terakhir' => $pendaftar->pendidikan_terakhir,
                    'asal_sekolah' => $pendaftar->asal_sekolah,
                    
                    // Relasi data
                    'periode_pendaftaran' => [
                        'id' => $pendaftar->periodePendaftaran->id ?? null,
                        'nama_periode' => $pendaftar->periodePendaftaran->nama_periode ?? null,
                        'jalur' => [
                            'id' => $pendaftar->periodePendaftaran->jalurPendaftaran->id ?? null,
                            'nama_jalur' => $pendaftar->periodePendaftaran->jalurPendaftaran->nama_jalur ?? null,
                        ]
                    ],
                    'prodi' => [
                        'id' => $pendaftar->prodi->id ?? null,
                        'nama_prodi' => $pendaftar->prodi->nama_prodi ?? null,
                        'kode_prodi' => $pendaftar->prodi->kode_prodi ?? null,
                    ],
                    
                    // Status info
                    'status_pendaftaran' => $pendaftar->status,
                    'status_pembayaran' => $pendaftar->payments->first()?->status ?? null,
                    'total_dokumen' => $pendaftar->documents->count(),
                    'dokumen_disetujui' => $pendaftar->documents->where('status_dokumen', 'approved')->count(),
                    
                    // Timestamps
                    'tanggal_daftar' => $pendaftar->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $pendaftar->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data mahasiswa berhasil diambil',
                'data' => $transformedData,
                'meta' => [
                    'total_count' => $transformedData->count(),
                    'limit' => $limit,
                    'filters_applied' => [
                        'periode_id' => $request->periode_id ?? null,
                        'prodi_id' => $request->prodi_id ?? null,
                        'search' => $request->search ?? null,
                    ],
                    'note' => 'Filter: Pembayaran confirmed + Status submitted + Dokumen lengkap'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mahasiswa: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get all periode pendaftaran for filter options
     */
    public function getPeriodePendaftaran(Request $request): JsonResponse
    {
        try {
            $periodes = PeriodePendaftaran::select(['id', 'nama_periode', 'status'])
                ->orderByDesc('created_at')
                ->get();

            // Transform data untuk response
            $transformedData = $periodes->map(function ($periode) {
                return [
                    'id' => $periode->id,
                    'nama_periode' => $periode->nama_periode,
                    'status' => $periode->status,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data periode pendaftaran berhasil diambil',
                'data' => $transformedData,
                'meta' => [
                    'total_count' => $transformedData->count()
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data periode pendaftaran: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Get single mahasiswa by ID
     */
    public function show(string $id): JsonResponse
    {
        try {
            $pendaftar = Pendaftar::with([
                'periodePendaftaran.jalurPendaftaran',
                'prodi',
                'payments',
                'documents.dokumenPendaftar'
            ])->findOrFail($id);

            // Validate if mahasiswa is completely accepted
            if ($pendaftar->status !== 'submitted') {
                return response()->json([
                    'success' => false,
                    'message' => 'Status pendaftaran belum disetujui',
                    'data' => null
                ], 422);
            }

            $hasConfirmedPayment = $pendaftar->payments()->where('status', 'confirmed')->exists();
            if (!$hasConfirmedPayment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran belum dikonfirmasi',
                    'data' => null
                ], 422);
            }

            $allDocumentsApproved = $pendaftar->documents()->where('status_dokumen', 'approved')->count() === $pendaftar->documents->count();
            if (!$allDocumentsApproved) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum semua dokumen disetujui',
                    'data' => null
                ], 422);
            }

            $detailData = [
                'id' => $pendaftar->id,
                'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran,
                'nama_lengkap' => $pendaftar->nama_lengkap,
                'nik' => $pendaftar->nik,
                'email' => $pendaftar->email,
                'no_hp' => $pendaftar->no_hp,
                'jenis_kelamin' => $pendaftar->jenis_kelamin,
                'tanggal_lahir' => $pendaftar->tanggal_lahir->format('Y-m-d'),
                'alamat' => $pendaftar->alamat,
                'pendidikan_terakhir' => $pendaftar->pendidikan_terakhir,
                'asal_sekolah' => $pendaftar->asal_sekolah,
                'asal_info' => $pendaftar->asal_info,
                
                // Relasi data
                'periode_pendaftaran' => [
                    'id' => $pendaftar->periodePendaftaran->id,
                    'nama_periode' => $pendaftar->periodePendaftaran->nama_periode,
                    'jalur' => [
                        'id' => $pendaftar->periodePendaftaran->jalurPendaftaran->id,
                        'nama_jalur' => $pendaftar->periodePendaftaran->jalurPendaftaran->nama_jalur,
                    ]
                ],
                'prodi' => [
                    'id' => $pendaftar->prodi->id,
                    'nama_prodi' => $pendaftar->prodi->nama_prodi,
                    'kode_prodi' => $pendaftar->prodi->kode_prodi,
                ],
                
                // Pembayaran detail
                'pembayaran' => $pendaftar->payments->map(function($payment) {
                    return [
                        'id' => $payment->id,
                        'metode_pembayaran' => $payment->metode_pembayaran,
                        'tanggal_pembayaran' => $payment->tanggal_pembayaran,
                        'status' => $payment->status,
                        'catatan' => $payment->catatan,
                    ];
                }),
                
                // Dokumen detail
                'dokumen' => $pendaftar->documents->map(function($doc) {
                    return [
                        'id' => $doc->id,
                        'nama_dokumen' => $doc->dokumenPendaftar->nama_dokumen,
                        'status_dokumen' => $doc->status_dokumen,
                        'catatan_admin' => $doc->catatan_admin,
                        'alamat_dokumen' => $doc->alamat_dokumen,
                    ];
                }),
                
                // Status info
                'status_pendaftaran' => $pendaftar->status,
                'total_dokumen' => $pendaftar->documents->count(),
                'dokumen_disetujui' => $pendaftar->documents->where('status_dokumen', 'approved')->count(),
                
                // Timestamps
                'tanggal_daftar' => $pendaftar->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $pendaftar->updated_at->format('Y-m-d H:i:s'),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Detail mahasiswa berhasil diambil',
                'data' => $detailData
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa tidak ditemukan',
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail mahasiswa: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
