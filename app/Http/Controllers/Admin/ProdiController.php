<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $prodis = Prodi::latest()->paginate(10);
        return view('admin.prodi.index', compact('prodis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.prodi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'kode_prodi' => 'required|string|max:100|unique:prodi,kode_prodi',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_prodi.required' => 'Nama program studi wajib diisi',
            'kode_prodi.required' => 'Kode prodi wajib diisi',
            'kode_prodi.unique' => 'Kode prodi sudah digunakan',
        ]);

        Prodi::create($validated);

        return redirect()->route('prodi.index')->with('success', 'Program studi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prodi $prodi): View
    {
        return view('admin.prodi.show', compact('prodi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prodi $prodi): View
    {
        return view('admin.prodi.edit', compact('prodi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prodi $prodi): RedirectResponse
    {
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'kode_prodi' => 'required|string|max:100|unique:prodi,kode_prodi,' . $prodi->id . ',id',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_prodi.required' => 'Nama program studi wajib diisi',
            'kode_prodi.required' => 'Kode prodi wajib diisi',
            'kode_prodi.unique' => 'Kode prodi sudah digunakan',
        ]);

        $prodi->update($validated);

        return redirect()->route('prodi.index')->with('success', 'Program studi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prodi $prodi): RedirectResponse
    {
        try {
            $prodi->delete();
            return redirect()->route('prodi.index')->with('success', 'Program studi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('prodi.index')->with('error', 'Gagal menghapus program studi!');
        }
    }
}
