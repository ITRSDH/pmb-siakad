<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JalurPendaftaran;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JalurPendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jalurPendaftarans = JalurPendaftaran::latest()->paginate(10);
        return view('admin.jalur_pendaftaran.index', compact('jalurPendaftarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.jalur_pendaftaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_jalur' => 'required|string|max:255|unique:jalur_pendaftaran,nama_jalur',
            'deskripsi' => 'nullable|string',
        ]);

        JalurPendaftaran::create([
            'nama_jalur' => $request->nama_jalur,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('jalur-pendaftaran.index')
            ->with('success', 'Jalur Pendaftaran berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(JalurPendaftaran $jalurPendaftaran): View
    {
        return view('admin.jalur_pendaftaran.show', compact('jalurPendaftaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JalurPendaftaran $jalurPendaftaran): View
    {
        return view('admin.jalur_pendaftaran.edit', compact('jalurPendaftaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JalurPendaftaran $jalurPendaftaran): RedirectResponse
    {
        $request->validate([
            'nama_jalur' => 'required|string|max:255|unique:jalur_pendaftaran,nama_jalur,' . $jalurPendaftaran->id,
            'deskripsi' => 'nullable|string',
        ]);

        $jalurPendaftaran->update([
            'nama_jalur' => $request->nama_jalur,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('jalur-pendaftaran.index')
            ->with('success', 'Jalur Pendaftaran berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JalurPendaftaran $jalurPendaftaran): RedirectResponse
    {
        $jalurPendaftaran->delete();

        return redirect()->route('jalur-pendaftaran.index')
            ->with('success', 'Jalur Pendaftaran berhasil dihapus!');
    }
}
