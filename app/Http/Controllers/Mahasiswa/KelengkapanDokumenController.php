<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\PendaftarDocuments;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class KelengkapanDokumenController extends Controller
{
    /**
     * Tampilkan daftar dokumen yang telah diupload mahasiswa
     * Hanya bisa diakses jika pembayaran sudah diterima admin
     */
    public function index()
    {
        $user = auth('google')->user();
        
        // Ambil pendaftar yang pembayarannya sudah confirmed
        $pendaftar = Pendaftar::with(['documents.dokumenPendaftar', 'periodePendaftaran.dokumenPendaftars', 'payments'])
            ->where('google_user_id', $user->id)
            ->whereHas('payments', function($query) {
                $query->where('status', 'confirmed');
            })
            ->first();

        // Jika tidak ada pendaftar yang memenuhi syarat, redirect ke pembayaran
        if (!$pendaftar) {
            return redirect()->route('pmb.pembayaran.index')
                ->with('error', 'Anda belum memiliki pembayaran yang dikonfirmasi. Silakan selesaikan pembayaran terlebih dahulu.');
        }

        // Ambil dokumen yang diperlukan untuk periode ini
        $dokumenDiperlukan = $pendaftar->periodePendaftaran->dokumenPendaftars;
        
        // Ambil dokumen yang sudah diupload
        $dokumenTerupload = $pendaftar->documents()->with('dokumenPendaftar')->latest()->get();

        return view('pmb.kelengkapan-dokumen.index', compact('pendaftar', 'dokumenDiperlukan', 'dokumenTerupload'));
    }

    /**
     * Tampilkan form upload dokumen baru
     */
    public function create()
    {
        $user = auth('google')->user();
        
        $pendaftar = Pendaftar::with('periodePendaftaran.dokumenPendaftars')
            ->where('google_user_id', $user->id)
            ->whereHas('payments', function($query) {
                $query->where('status', 'confirmed');
            })
            ->first();

        if (!$pendaftar) {
            return redirect()->route('pmb.pembayaran.index')
                ->with('error', 'Pembayaran Anda belum dikonfirmasi.');
        }

        // Ambil dokumen yang diperlukan untuk periode ini
        $dokumenDiperlukan = $pendaftar->periodePendaftaran->dokumenPendaftars;
        
        // Ambil dokumen yang sudah diupload (untuk mengetahui mana yang belum)
        $dokumenTerupload = $pendaftar->documents()->pluck('dokumen_pendaftar_id')->toArray();

        return view('pmb.kelengkapan-dokumen.create', compact('pendaftar', 'dokumenDiperlukan', 'dokumenTerupload'));
    }

    /**
     * Simpan dokumen yang diupload
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth('google')->user();
        
        $pendaftar = Pendaftar::with('periodePendaftaran.dokumenPendaftars')
            ->where('google_user_id', $user->id)
            ->whereHas('payments', function($query) {
                $query->where('status', 'confirmed');
            })
            ->first();

        if (!$pendaftar) {
            return redirect()->route('pmb.pembayaran.index')
                ->with('error', 'Pembayaran Anda belum dikonfirmasi.');
        }

        try {
            $validated = $request->validate([
                'dokumen_pendaftar_id' => 'required|exists:dokumen_pendaftar,id',
                'alamat_dokumen' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
                'catatan' => 'nullable|string|max:500',
            ], [
                'dokumen_pendaftar_id.required' => 'Jenis dokumen wajib dipilih.',
                'dokumen_pendaftar_id.exists' => 'Jenis dokumen tidak valid.',
                'alamat_dokumen.required' => 'File dokumen wajib diupload.',
                'alamat_dokumen.mimes' => 'Format file harus PDF, JPG, JPEG, atau PNG.',
                'alamat_dokumen.max' => 'Ukuran file maksimal 5MB.',
            ]);

            // Cek apakah dokumen sudah ada
            $existingDocument = $pendaftar->documents()
                ->where('dokumen_pendaftar_id', $validated['dokumen_pendaftar_id'])
                ->first();
                
            if ($existingDocument) {
                return redirect()->back()->withInput()
                    ->withErrors(['dokumen_pendaftar_id' => 'Dokumen jenis ini sudah diupload. Gunakan fitur edit untuk menggantinya.']);
            }

            // Validasi apakah dokumen ini diperlukan untuk periode ini
            $isRequired = $pendaftar->periodePendaftaran->dokumenPendaftars()
                ->where('dokumen_pendaftar_id', $validated['dokumen_pendaftar_id'])
                ->exists();
                
            if (!$isRequired) {
                return redirect()->back()->withInput()
                    ->withErrors(['dokumen_pendaftar_id' => 'Dokumen ini tidak diperlukan untuk periode pendaftaran Anda.']);
            }

            // Upload file
            $file = $request->file('alamat_dokumen');
            $fileName = time() . '_' . $pendaftar->id . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumen-pendaftar', $fileName, 'public');

            // Simpan ke database
            $pendaftar->documents()->create([
                'dokumen_pendaftar_id' => $validated['dokumen_pendaftar_id'],
                'alamat_dokumen' => $path,
                'catatan' => $validated['catatan'] ?? null,
            ]);

            Log::info('Dokumen berhasil diupload', [
                'pendaftar_id' => $pendaftar->id,
                'dokumen_pendaftar_id' => $validated['dokumen_pendaftar_id'],
                'file_path' => $path,
                'user_id' => $user->id
            ]);

            return redirect()->route('pmb.dokumen.index')
                ->with('success', 'Dokumen berhasil diupload.');

        } catch (\Illuminate\Validation\ValidationException $ve) {
            Log::error('Validasi upload dokumen gagal', [
                'errors' => $ve->errors(),
                'user_id' => $user->id,
                'pendaftar_id' => $pendaftar->id,
            ]);
            return redirect()->back()->withInput()->withErrors($ve->errors());

        } catch (\Exception $e) {
            Log::error('Gagal upload dokumen: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'pendaftar_id' => $pendaftar->id,
                'request' => $request->all(),
            ]);
            return redirect()->back()->withInput()
                ->withErrors(['alamat_dokumen' => 'Gagal mengupload dokumen. Silakan coba lagi.']);
        }
    }

    /**
     * Tampilkan detail dokumen
     */
    public function show($id)
    {
        $user = auth('google')->user();
        
        // Ambil dokumen dengan relasi pendaftar
        $dokumen = PendaftarDocuments::with('pendaftar')->findOrFail($id);
        
        // Pastikan dokumen milik user yang sedang login
        if (!$dokumen->pendaftar || $dokumen->pendaftar->google_user_id !== $user->id) {
            abort(404);
        }

        // Cek tipe file
        $ext = pathinfo($dokumen->alamat_dokumen, PATHINFO_EXTENSION);
        $isImage = in_array(strtolower($ext), ['jpg','jpeg','png']);
        $isPdf = strtolower($ext) === 'pdf';

        // URL file
        $fileUrl = asset('storage/' . $dokumen->alamat_dokumen);

        return view('pmb.kelengkapan-dokumen.show', compact('dokumen', 'fileUrl', 'isImage', 'isPdf'));
    }

    /**
     * Tampilkan form edit dokumen
     */
    public function edit($id)
    {
        $user = auth('google')->user();
        
        // Ambil dokumen dengan relasi pendaftar
        $dokumen = PendaftarDocuments::with('pendaftar')->findOrFail($id);
        
        // Pastikan dokumen milik user yang sedang login
        if (!$dokumen->pendaftar || $dokumen->pendaftar->google_user_id !== $user->id) {
            abort(404);
        }

        return view('pmb.kelengkapan-dokumen.edit', compact('dokumen'));
    }

    /**
     * Update dokumen (ganti file atau edit catatan)
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $user = auth('google')->user();
        
        // Ambil dokumen dengan relasi pendaftar
        $dokumen = PendaftarDocuments::with('pendaftar')->findOrFail($id);
        
        // Pastikan dokumen milik user yang sedang login
        if (!$dokumen->pendaftar || $dokumen->pendaftar->google_user_id !== $user->id) {
            abort(404);
        }

        try {
            $validated = $request->validate([
                'alamat_dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Opsional untuk update
                'catatan' => 'nullable|string|max:500',
            ], [
                'alamat_dokumen.mimes' => 'Format file harus PDF, JPG, JPEG, atau PNG.',
                'alamat_dokumen.max' => 'Ukuran file maksimal 5MB.',
            ]);

            $updateData = ['catatan' => $validated['catatan'] ?? null];

            // Jika ada file baru diupload
            if ($request->hasFile('alamat_dokumen')) {
                $file = $request->file('alamat_dokumen');
                $fileName = time() . '_' . $dokumen->pendaftar_id . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('dokumen-pendaftar', $fileName, 'public');
                
                // Hapus file lama
                if ($dokumen->alamat_dokumen && Storage::disk('public')->exists($dokumen->alamat_dokumen)) {
                    Storage::disk('public')->delete($dokumen->alamat_dokumen);
                }
                
                $updateData['alamat_dokumen'] = $path;
                
                Log::info('File dokumen diperbarui', [
                    'dokumen_id' => $dokumen->id,
                    'dokumen_pendaftar_id' => $dokumen->dokumen_pendaftar_id,
                    'old_path' => $dokumen->alamat_dokumen,
                    'new_path' => $path,
                ]);
            }

            $dokumen->update($updateData);

            return redirect()->route('pmb.dokumen.index')
                ->with('success', 'Dokumen berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $ve) {
            return redirect()->back()->withInput()->withErrors($ve->errors());

        } catch (\Exception $e) {
            Log::error('Gagal update dokumen: ' . $e->getMessage(), [
                'dokumen_id' => $dokumen->id,
                'user_id' => $user->id,
            ]);
            return redirect()->back()->withInput()
                ->withErrors(['alamat_dokumen' => 'Gagal memperbarui dokumen. Silakan coba lagi.']);
        }
    }

    /**
     * Hapus dokumen
     */
    public function destroy($id): RedirectResponse
    {
        $user = auth('google')->user();
        
        // Ambil dokumen dengan relasi pendaftar
        $dokumen = PendaftarDocuments::with('pendaftar')->findOrFail($id);
        
        // Pastikan dokumen milik user yang sedang login
        if (!$dokumen->pendaftar || $dokumen->pendaftar->google_user_id !== $user->id) {
            abort(404);
        }

        try {
            // Hapus file dari storage
            if ($dokumen->alamat_dokumen && Storage::disk('public')->exists($dokumen->alamat_dokumen)) {
                Storage::disk('public')->delete($dokumen->alamat_dokumen);
            }

            $dokumen->delete();

            Log::info('Dokumen berhasil dihapus', [
                'dokumen_id' => $dokumen->id,
                'user_id' => $user->id,
            ]);

            return redirect()->route('pmb.dokumen.index')
                ->with('success', 'Dokumen berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal hapus dokumen: ' . $e->getMessage(), [
                'dokumen_id' => $dokumen->id,
                'user_id' => $user->id,
            ]);
            return redirect()->back()
                ->with('error', 'Gagal menghapus dokumen. Silakan coba lagi.');
        }
    }
}
