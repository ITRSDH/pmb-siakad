<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $prodis = Prodi::latest()->paginate(10);
        $lastSync = session('last_prodi_sync');
        return view('admin.prodi.index', compact('prodis', 'lastSync'));
    }

    /**
     * Sync data from API
     */
    public function sync(): RedirectResponse
    {
        try {
            // Get data from API
            $apiUrl = Config::get('api.base_url') . 'landing/prodi';
            Log::info('Attempting to sync from: ' . $apiUrl);
            
            $response = Http::get($apiUrl);
            
            Log::info('API Response Status: ' . $response->status());
            
            if (!$response->successful()) {
                Log::error('API Sync Failed: ' . $response->body());
                return redirect()->route('prodi.index')->with('error', 'Gagal menghubungi API server!');
            }

            $data = $response->json();
            
            if (!$data['status'] || !isset($data['data'])) {
                return redirect()->route('prodi.index')->with('error', 'Format response API tidak valid!');
            }

            $apiProdis = $data['data'];
            $syncCount = 0;
            $updateCount = 0;

            DB::beginTransaction();
            
            foreach ($apiProdis as $apiProdi) {
                // Check if prodi exists by kode_prodi
                $existingProdi = Prodi::where('kode_prodi', $apiProdi['kode_prodi'])->first();
                
                // Only sync fields that exist in table
                $prodiData = [
                    'nama_prodi' => $apiProdi['nama_prodi'],
                    'kode_prodi' => $apiProdi['kode_prodi'],
                    'deskripsi' => null, // API doesn't have deskripsi field, set to null
                ];

                if ($existingProdi) {
                    // Update existing prodi
                    $existingProdi->update($prodiData);
                    $updateCount++;
                } else {
                    // Create new prodi
                    Prodi::create($prodiData);
                    $syncCount++;
                }
            }

            DB::commit();

            // Store last sync time
            session(['last_prodi_sync' => now()->format('d M Y H:i:s')]);

            $message = "Sync berhasil! {$syncCount} data baru, {$updateCount} data diperbarui.";
            return redirect()->route('prodi.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('prodi.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
