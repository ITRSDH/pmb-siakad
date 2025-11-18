<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenPendaftar;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DokumenPendaftarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $dokumenPendaftars = DokumenPendaftar::latest()->paginate(15);
        return view('admin.dokumen-pendaftar.index', compact('dokumenPendaftars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.dokumen-pendaftar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
        ]);

        DokumenPendaftar::create($validated);

        return redirect()->route('dokumen-pendaftar.index')
            ->with('success', 'Dokumen pendaftar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DokumenPendaftar $dokumenPendaftar): View
    {
        return view('admin.dokumen-pendaftar.show', compact('dokumenPendaftar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DokumenPendaftar $dokumenPendaftar): View
    {
        return view('admin.dokumen-pendaftar.edit', compact('dokumenPendaftar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DokumenPendaftar $dokumenPendaftar): RedirectResponse
    {
        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
        ]);

        $dokumenPendaftar->update($validated);

        return redirect()->route('dokumen-pendaftar.index')
            ->with('success', 'Dokumen pendaftar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DokumenPendaftar $dokumenPendaftar): RedirectResponse
    {
        $dokumenPendaftar->delete();

        return redirect()->route('dokumen-pendaftar.index')
            ->with('success', 'Dokumen pendaftar berhasil dihapus.');
    }
}
