<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Pendaftar;
use App\Models\PendaftarDocuments;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RiwayatPendaftaranController extends Controller
{
    /**
     * Show list of pendaftars for current google user
     */
    public function index(Request $request): View
    {
        $user = auth('google')->user();

        $query = Pendaftar::query();
        if ($user) {
            $query->where('google_user_id', $user->id);
        } else {
            // if not logged via google, return empty collection
            $query->whereNull('id')->whereRaw('1 = 0');
        }

        $pendaftars = $query->with('documents')->orderByDesc('created_at')->get();

        return view('pmb.riwayat.index', compact('pendaftars'));
    }

    /**
     * Show the form for editing documents of a rejected pendaftar
     */
    public function edit(Request $request, string $id): View
    {
        $user = auth('google')->user();
        
        // Cari pendaftar yang sesuai dengan user yang login dan status rejected
        $pendaftar = Pendaftar::with(['documents', 'periodePendaftaran'])
            ->where('id', $id)
            ->where('google_user_id', $user->id)
            ->where('status', 'rejected')
            ->firstOrFail();

        return view('pmb.riwayat.edit', compact('pendaftar'));
    }

    /**
     * Update the documents for rejected pendaftar
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $user = auth('google')->user();
        
        // Cari pendaftar yang sesuai dengan user yang login dan status rejected
        $pendaftar = Pendaftar::where('id', $id)
            ->where('google_user_id', $user->id)
            ->where('status', 'rejected')
            ->firstOrFail();

        // Validasi file upload
        $request->validate([
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'catatan' => 'nullable|string|max:500'
        ], [
            'ktp.required' => 'File KTP wajib diupload',
            'ktp.mimes' => 'File KTP harus berformat PDF, JPG, JPEG, atau PNG',
            'ktp.max' => 'Ukuran file KTP maksimal 2MB',
            'ijazah.required' => 'File Ijazah/SKL wajib diupload',
            'ijazah.mimes' => 'File Ijazah harus berformat PDF, JPG, JPEG, atau PNG',
            'ijazah.max' => 'Ukuran file Ijazah maksimal 2MB',
            'kk.required' => 'File Kartu Keluarga wajib diupload',
            'kk.mimes' => 'File Kartu Keluarga harus berformat PDF, JPG, JPEG, atau PNG',
            'kk.max' => 'Ukuran file Kartu Keluarga maksimal 2MB',
            'pas_foto.required' => 'File Pas Foto wajib diupload',
            'pas_foto.mimes' => 'File Pas Foto harus berformat JPG, JPEG, atau PNG',
            'pas_foto.max' => 'Ukuran file Pas Foto maksimal 1MB',
        ]);

        try {
            // Hapus dokumen lama
            $oldDocuments = PendaftarDocuments::where('pendaftar_id', $pendaftar->id)->get();
            foreach ($oldDocuments as $doc) {
                if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                    Storage::disk('public')->delete($doc->file_path);
                }
                $doc->delete();
            }

            // Upload dokumen baru
            $documents = [
                'ktp' => 'KTP',
                'ijazah' => 'Ijazah/SKL',
                'kk' => 'Kartu Keluarga',
                'pas_foto' => 'Pas Foto'
            ];

            foreach ($documents as $field => $label) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $fileName = $pendaftar->nomor_pendaftaran . '_' . Str::slug($label) . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('dokumen-pendaftar', $fileName, 'public');

                    PendaftarDocuments::create([
                        'pendaftar_id' => $pendaftar->id,
                        'alamat_dokumen' => $filePath,
                        'catatan' => $request->catatan
                    ]);
                }
            }

            // Update status pendaftar kembali ke draft untuk review ulang
            $pendaftar->update(['status' => 'draft']);

            Log::info('Dokumen berhasil diupload ulang untuk pendaftar: ' . $pendaftar->nomor_pendaftaran);

            return redirect()
                ->route('pmb.riwayat.index')
                ->with('success', 'Dokumen berhasil diupload ulang. Status pendaftaran Anda telah berubah menjadi "Menunggu Verifikasi" untuk ditinjau kembali.');

        } catch (\Exception $e) {
            Log::error('Error saat upload ulang dokumen: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupload dokumen. Silakan coba lagi.');
        }
    }
}
