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
                'periodePendaftaran:id,nama_periode,jalur_pendaftaran_id,tanggal_mulai',
                'periodePendaftaran.jalurPendaftaran:id,nama_jalur',
                'periodePendaftaran.dokumenPendaftars' => function($q) {
                    $q->select('dokumen_pendaftar_id', 'nama_dokumen')
                      ->withPivot('is_wajib');
                },
                'prodi:id,nama_prodi,kode_prodi',
                'payments:id,pendaftar_id,status,metode_pembayaran,tanggal_pembayaran',
                'kelengkapan:pendaftar_id,nama_lengkap,nik,jenis_kelamin,tanggal_lahir,tempat_lahir,alamat_lengkap,no_hp,email,asal_sekolah,pas_foto,ktp,kk,ijazah_skl'
                // 'documents:id,pendaftar_id,dokumen_pendaftar_id,status_dokumen,alamat_dokumen',
                // 'documents.dokumenPendaftar:id,nama_dokumen'
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

            // Parse tanggal_mulai untuk mengubah dari Date menjadi tahun saja
            $pendaftars->each(function ($pendaftar) {
                if ($pendaftar->periodePendaftaran && $pendaftar->periodePendaftaran->tanggal_mulai) {
                    $tanggalMulai = $pendaftar->periodePendaftaran->tanggal_mulai;
                    if (is_string($tanggalMulai)) {
                        // Jika string format YYYY-MM-DD, ambil 4 karakter pertama (tahun)
                        $pendaftar->periodePendaftaran->tanggal_mulai = substr($tanggalMulai, 0, 4);
                    } elseif (is_object($tanggalMulai) && method_exists($tanggalMulai, 'year')) {
                        // Jika Carbon object, ambil tahun
                        $pendaftar->periodePendaftaran->tanggal_mulai = $tanggalMulai->year;
                    }
                }
            });

            // Filter manual untuk dokumen lengkap (sama seperti indexDokumenDiterima)
            // $filteredPendaftars = $pendaftars->filter(function ($pendaftar) {
            //     $dokumenDiperlukan = $pendaftar->periodePendaftaran->dokumenPendaftars ?? collect([]);
            //     $dokumenTerupload = $pendaftar->documents ?? collect([]);
                
            //     $dokumenWajibDiperlukan = $dokumenDiperlukan->where('is_wajib', true)->count();
            //     $dokumenWajibLengkap = $dokumenTerupload->whereIn('dokumen_pendaftar_id', 
            //         $dokumenDiperlukan->where('is_wajib', true)->pluck('id'))->count();
                
            //     return $dokumenWajibLengkap >= $dokumenWajibDiperlukan;
            // });

            // Transform data untuk response
            $transformedData = $pendaftars->map(function ($pendaftar) {
                $kelengkapan = $pendaftar->kelengkapan;
                return [
                    'id' => $pendaftar->id,
                    'nomor_pendaftaran' => $pendaftar->nomor_pendaftaran,
                    'nama_lengkap' => $kelengkapan->nama_lengkap ?? null,
                    'email' => $kelengkapan->email ?? $pendaftar->email ?? null,
                    'no_hp' => $kelengkapan->no_hp ?? $pendaftar->no_hp ?? null,
                    'jenis_kelamin' => $kelengkapan->jenis_kelamin ?? $pendaftar->jenis_kelamin ?? null,
                    'tanggal_lahir' => $kelengkapan?->tanggal_lahir
                        ? (is_string($kelengkapan->tanggal_lahir) ? $kelengkapan->tanggal_lahir : $kelengkapan->tanggal_lahir->format('Y-m-d'))
                        : ($pendaftar->tanggal_lahir
                            ? (is_string($pendaftar->tanggal_lahir) ? $pendaftar->tanggal_lahir : $pendaftar->tanggal_lahir->format('Y-m-d'))
                            : null),
                    'alamat' => $kelengkapan->alamat_lengkap ?? $pendaftar->alamat ?? null,
                    'pendidikan_terakhir' => $pendaftar->pendidikan_terakhir,
                    'asal_sekolah' => $kelengkapan->asal_sekolah ?? $pendaftar->asal_sekolah ?? null,
                    'tanggal_masuk' => $pendaftar->periodePendaftaran->tanggal_mulai ?? null,
                    'nik' => $kelengkapan->nik ?? null,
                    'tempat_lahir' => $kelengkapan->tempat_lahir ?? null,
                    'tahun_angkatan' => $pendaftar->periodePendaftaran->tanggal_mulai
                        ? (is_numeric($pendaftar->periodePendaftaran->tanggal_mulai)
                            ? (int) $pendaftar->periodePendaftaran->tanggal_mulai
                            : (is_object($pendaftar->periodePendaftaran->tanggal_mulai) && method_exists($pendaftar->periodePendaftaran->tanggal_mulai, 'year')
                                ? (int) $pendaftar->periodePendaftaran->tanggal_mulai->year
                                : (int) substr($pendaftar->periodePendaftaran->tanggal_mulai, 0, 4)))
                        : null,

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
                    'total_dokumen' => 4, // Total dokumen yang diperlukan (pas_foto, ktp, kk, ijazah_skl)
                    'dokumen_disetujui' => $kelengkapan ? (
                        ($kelengkapan->pas_foto ? 1 : 0) +
                        ($kelengkapan->ktp ? 1 : 0) +
                        ($kelengkapan->kk ? 1 : 0) +
                        ($kelengkapan->ijazah_skl ? 1 : 0)
                    ) : 0,

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
    
    public function getPeriodePendaftaranLanding(Request $request): JsonResponse
    {
        try {
            $periodes = PeriodePendaftaran::with([
                    'gelombang:id,nama_gelombang',
                    'jalurPendaftaran:id,nama_jalur',
                    'biayaPendaftaran:id,jumlah_biaya'
                ])
                ->select([
                    'id',
                    'nama_periode',
                    'gelombang_id',
                    'jalur_pendaftaran_id',
                    'biaya_pendaftaran_id',
                    'kuota',
                    'kuota_terisi',
                    'status',
                    'tanggal_mulai',
                    'tanggal_selesai',
                ])
                ->berjalan()
                ->latest()
                ->get();
    
            $transformedData = $periodes->map(function ($periode) {
                return [
                    'id' => $periode->id,
                    'nama_periode' => $periode->nama_periode,
                    'nama_gelombang' => optional($periode->gelombang)->nama_gelombang,
                    'nama_jalur_pendaftaran' => optional($periode->jalurPendaftaran)->nama_jalur,
                    'biaya_pendaftaran' => optional($periode->biayaPendaftaran)->jumlah_biaya,
                    'status' => $periode->status,
                    'total_kuota' => $periode->kuota,
                    'kuota_terisi' => $periode->kuota_terisi,
                    'kuota_sisa' => $periode->kuota_sisa,
                    'tanggal_mulai' => $periode->tanggal_mulai,
                    'tanggal_selesai' => $periode->tanggal_selesai,
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
    
        } catch (\Throwable $e) {
    
            report($e);
    
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
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
