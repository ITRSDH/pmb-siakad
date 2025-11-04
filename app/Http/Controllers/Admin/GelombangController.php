<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GelombangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $gelombangs = Gelombang::latest()->paginate(10);
        return view('admin.gelombang.index', compact('gelombangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.gelombang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_gelombang' => 'required|string|max:255|unique:gelombang,nama_gelombang',
            'deskripsi' => 'nullable|string',
        ]);

        Gelombang::create([
            'nama_gelombang' => $request->nama_gelombang,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('gelombang.index')
            ->with('success', 'Gelombang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gelombang $gelombang): View
    {
        return view('admin.gelombang.show', compact('gelombang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gelombang $gelombang): View
    {
        return view('admin.gelombang.edit', compact('gelombang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gelombang $gelombang): RedirectResponse
    {
        $request->validate([
            'nama_gelombang' => 'required|string|max:255|unique:gelombang,nama_gelombang,' . $gelombang->id,
            'deskripsi' => 'nullable|string',
        ]);

        $gelombang->update([
            'nama_gelombang' => $request->nama_gelombang,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('gelombang.index')
            ->with('success', 'Gelombang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gelombang $gelombang): RedirectResponse
    {
        $gelombang->delete();

        return redirect()->route('gelombang.index')
            ->with('success', 'Gelombang berhasil dihapus!');
    }
}
