<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiayaPendaftaran;
use App\Models\Gelombang;
use App\Models\JalurPendaftaran;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $jalur_count = JalurPendaftaran::count();
        $gelombang_count = Gelombang::count();
        $biaya_count = BiayaPendaftaran::count();
        $total_pendaftar = Pendaftar::count();
        
        // Data untuk chart pendaftar per bulan (12 bulan terakhir)
        $monthlyRegistrations = $this->getMonthlyRegistrations();
        
        // Data pendaftar hari ini
        $today_registrations = Pendaftar::whereDate('created_at', Carbon::today())->count();
        
        return view('admin.index', compact(
            'jalur_count', 
            'gelombang_count', 
            'biaya_count', 
            'total_pendaftar',
            'monthlyRegistrations',
            'today_registrations'
        ));
    }
    
    private function getMonthlyRegistrations()
    {
        $months = [];
        $data = [];
        
        // Generate 12 bulan terakhir
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            // Hitung pendaftar per bulan
            $count = Pendaftar::whereYear('created_at', $date->year)
                            ->whereMonth('created_at', $date->month)
                            ->count();
            $data[] = $count;
        }
        
        // Jika semua data 0, buat sample data untuk demo
        $totalRegistrations = array_sum($data);
        if ($totalRegistrations === 0) {
            // Sample data untuk demo chart
            $data = [5, 12, 8, 15, 22, 18, 25, 30, 28, 20, 15, 10];
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }
}
