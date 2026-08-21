<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelengkapanPendaftar;

class KelengkapanPendaftarController extends Controller
{
    public function index()
    {
        $pendaftar = auth('google')->user()->pendaftar;
        $kelengkapan = $pendaftar->kelengkapan;
        return view('pmb.kelengkapan-pendaftar.index', compact('pendaftar', 'kelengkapan'));
    }

    public function create()
    {
        $pendaftar = auth('google')->user()->pendaftar;
        return view('pmb.kelengkapan-pendaftar.form', compact('pendaftar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Biodata
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:kelengkapan_pendaftars,nik',
            'nisn' => 'required|string|size:10',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            // Alamat
            'alamat_lengkap' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            // Pendidikan
            'asal_sekolah' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:2000',
            // Dokumen
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'kk' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijazah_skl' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            // Orang tua
            'nama_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:100',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:100',
        ]);

        $pendaftar = auth('google')->user()->pendaftar;
        
        $kelengkapan = KelengkapanPendaftar::create([
            'pendaftar_id' => $pendaftar->id,
            'nama_lengkap' => $validated['nama_lengkap'],
            'nik' => $validated['nik'],
            'nisn' => $validated['nisn'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'no_hp' => $validated['no_hp'],
            'email' => $validated['email'],
            'alamat_lengkap' => $validated['alamat_lengkap'],
            'provinsi' => $validated['provinsi'],
            'kabupaten_kota' => $validated['kabupaten_kota'],
            'kecamatan' => $validated['kecamatan'],
            'kelurahan' => $validated['kelurahan'],
            'asal_sekolah' => $validated['asal_sekolah'],
            'tahun_lulus' => $validated['tahun_lulus'],
            'nama_ayah' => $validated['nama_ayah'],
            'pekerjaan_ayah' => $validated['pekerjaan_ayah'],
            'nama_ibu' => $validated['nama_ibu'],
            'pekerjaan_ibu' => $validated['pekerjaan_ibu'],
            'status' => 'submitted',
        ]);

        // Handle file uploads
        if ($request->hasFile('pas_foto')) {
            $kelengkapan->pas_foto = $request->file('pas_foto')->store('kelengkapan/pas_foto', 'public');
        }
        if ($request->hasFile('ktp')) {
            $kelengkapan->ktp = $request->file('ktp')->store('kelengkapan/ktp', 'public');
        }
        if ($request->hasFile('kk')) {
            $kelengkapan->kk = $request->file('kk')->store('kelengkapan/kk', 'public');
        }
        if ($request->hasFile('ijazah_skl')) {
            $kelengkapan->ijazah_skl = $request->file('ijazah_skl')->store('kelengkapan/ijazah_skl', 'public');
        }
        
        $kelengkapan->save();

        return redirect()->route('kelengkapan-pendaftar.index')
            ->with('success', 'Data kelengkapan berhasil disimpan dan menunggu verifikasi admin.');
    }

    public function show($id)
    {
        $pendaftar = auth('google')->user()->pendaftar;
        $kelengkapan = KelengkapanPendaftar::where('pendaftar_id', $pendaftar->id)->firstOrFail();
        return view('pmb.kelengkapan-pendaftar.show', compact('pendaftar', 'kelengkapan'));
    }

    public function edit($id)
    {
        $pendaftar = auth('google')->user()->pendaftar;
        $kelengkapan = KelengkapanPendaftar::where('pendaftar_id', $pendaftar->id)->firstOrFail();

        // Hanya boleh edit jika status draft atau rejected
        if (!in_array($kelengkapan->status, ['draft', 'rejected'])) {
            return redirect()->route('kelengkapan-pendaftar.index')
                ->with('error', 'Data sudah disubmit dan tidak dapat diubah.');
        }

        return view('pmb.kelengkapan-pendaftar.form_update', compact('pendaftar', 'kelengkapan'));
    }

    public function update(Request $request, $id)
    {
        $pendaftar = auth('google')->user()->pendaftar;
        $kelengkapan = KelengkapanPendaftar::where('pendaftar_id', $pendaftar->id)->firstOrFail();
        
        // Hanya boleh update jika status draft atau rejected
        if (!in_array($kelengkapan->status, ['draft', 'rejected'])) {
            return redirect()->route('kelengkapan-pendaftar.index')
                ->with('error', 'Data sudah disubmit dan tidak dapat diubah.');
        }

        $validated = $request->validate([
            // Biodata
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:kelengkapan_pendaftars,nik,' . $id,
            'nisn' => 'required|string|size:10',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            // Alamat
            'alamat_lengkap' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kabupaten_kota' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
            // Pendidikan
            'asal_sekolah' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:2000',
            // Dokumen
            'pas_foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'kk' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'ijazah_skl' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            // Orang tua
            'nama_ayah' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:100',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:100',
        ]);

        $kelengkapan->update([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nik' => $validated['nik'],
            'nisn' => $validated['nisn'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'no_hp' => $validated['no_hp'],
            'email' => $validated['email'],
            'alamat_lengkap' => $validated['alamat_lengkap'],
            'provinsi' => $validated['provinsi'],
            'kabupaten_kota' => $validated['kabupaten_kota'],
            'kecamatan' => $validated['kecamatan'],
            'kelurahan' => $validated['kelurahan'],
            'asal_sekolah' => $validated['asal_sekolah'],
            'tahun_lulus' => $validated['tahun_lulus'],
            'nama_ayah' => $validated['nama_ayah'],
            'pekerjaan_ayah' => $validated['pekerjaan_ayah'],
            'nama_ibu' => $validated['nama_ibu'],
            'pekerjaan_ibu' => $validated['pekerjaan_ibu'],
            'status' => 'submitted',
            'catatan_admin' => null, // Reset catatan admin saat resubmit
        ]);

        // Handle file uploads
        if ($request->hasFile('pas_foto')) {
            $kelengkapan->pas_foto = $request->file('pas_foto')->store('kelengkapan/pas_foto', 'public');
        }
        if ($request->hasFile('ktp')) {
            $kelengkapan->ktp = $request->file('ktp')->store('kelengkapan/ktp', 'public');
        }
        if ($request->hasFile('kk')) {
            $kelengkapan->kk = $request->file('kk')->store('kelengkapan/kk', 'public');
        }
        if ($request->hasFile('ijazah_skl')) {
            $kelengkapan->ijazah_skl = $request->file('ijazah_skl')->store('kelengkapan/ijazah_skl', 'public');
        }
        
        $kelengkapan->save();

        return redirect()->route('kelengkapan-pendaftar.index')
            ->with('success', 'Data kelengkapan berhasil diperbarui dan menunggu verifikasi admin.');
    }
}
