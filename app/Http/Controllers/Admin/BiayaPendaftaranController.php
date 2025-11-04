<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiayaPendaftaran;
use App\Models\JalurPendaftaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BiayaPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $biayaPendaftarans = BiayaPendaftaran::with('jalurPendaftaran')->latest()->paginate(10);

        return view('admin.biaya_pendaftaran.index', compact('biayaPendaftarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $jalurPendaftarans = JalurPendaftaran::all();
        return view('admin.biaya_pendaftaran.create', compact('jalurPendaftarans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'nama_biaya' => 'required|string|max:255',
                'jumlah_biaya' => 'required|integer|min:0',
                'jalur_pendaftaran_id' => 'required|exists:jalur_pendaftaran,id',
            ],
            [
                'nama_biaya.required' => 'Nama biaya wajib diisi',
                'nama_biaya.string' => 'Nama biaya harus berupa text',
                'nama_biaya.max' => 'Nama biaya maksimal 255 karakter',
                'jumlah_biaya.required' => 'Jumlah biaya wajib diisi',
                'jumlah_biaya.integer' => 'Jumlah biaya harus berupa angka',
                'jumlah_biaya.min' => 'Jumlah biaya tidak boleh kurang dari 0',
                'jalur_pendaftaran_id.required' => 'Jalur pendaftaran wajib dipilih',
                'jalur_pendaftaran_id.exists' => 'Jalur pendaftaran yang dipilih tidak valid',
            ],
        );

        // Cek duplikasi berdasarkan nama_biaya dan jalur_pendaftaran_id
        $exists = BiayaPendaftaran::where('nama_biaya', $validated['nama_biaya'])->where('jalur_pendaftaran_id', $validated['jalur_pendaftaran_id'])->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'duplicate' => 'Biaya dengan nama yang sama sudah ada untuk jalur pendaftaran ini!',
                ])
                ->withInput();
        }

        BiayaPendaftaran::create($validated);

        return redirect()->route('biaya-pendaftaran.index')->with('success', 'Biaya pendaftaran berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BiayaPendaftaran $biayaPendaftaran): View
    {
        $biayaPendaftaran->load('jalurPendaftaran');
        return view('admin.biaya_pendaftaran.show', compact('biayaPendaftaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BiayaPendaftaran $biayaPendaftaran): View
    {
        $jalurPendaftarans = JalurPendaftaran::all();
        return view('admin.biaya_pendaftaran.edit', compact('biayaPendaftaran', 'jalurPendaftarans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BiayaPendaftaran $biayaPendaftaran): RedirectResponse
    {
        $validated = $request->validate(
            [
                'nama_biaya' => 'required|string|max:255',
                'jumlah_biaya' => 'required|integer|min:0',
                'jalur_pendaftaran_id' => 'required|exists:jalur_pendaftarans,id',
            ],
            [
                'nama_biaya.required' => 'Nama biaya wajib diisi',
                'nama_biaya.string' => 'Nama biaya harus berupa text',
                'nama_biaya.max' => 'Nama biaya maksimal 255 karakter',
                'jumlah_biaya.required' => 'Jumlah biaya wajib diisi',
                'jumlah_biaya.integer' => 'Jumlah biaya harus berupa angka',
                'jumlah_biaya.min' => 'Jumlah biaya tidak boleh kurang dari 0',
                'jalur_pendaftaran_id.required' => 'Jalur pendaftaran wajib dipilih',
                'jalur_pendaftaran_id.exists' => 'Jalur pendaftaran yang dipilih tidak valid',
            ],
        );

        // Cek duplikasi berdasarkan nama_biaya dan jalur_pendaftaran_id (kecuali record saat ini)
        $exists = BiayaPendaftaran::where('nama_biaya', $validated['nama_biaya'])->where('jalur_pendaftaran_id', $validated['jalur_pendaftaran_id'])->where('id', '!=', $biayaPendaftaran->id)->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'duplicate' => 'Biaya dengan nama yang sama sudah ada untuk jalur pendaftaran ini!',
                ])
                ->withInput();
        }

        $biayaPendaftaran->update($validated);

        return redirect()->route('biaya-pendaftaran.index')->with('success', 'Biaya pendaftaran berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BiayaPendaftaran $biayaPendaftaran): RedirectResponse
    {
        try {
            $biayaPendaftaran->delete();
            return redirect()->route('biaya-pendaftaran.index')->with('success', 'Biaya pendaftaran berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('biaya-pendaftaran.index')->with('error', 'Gagal menghapus biaya pendaftaran!');
        }
    }
}
