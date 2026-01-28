<?php

namespace App\Exports;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Pendaftar::with(['periodePendaftaran.jalurPendaftaran', 'payments']);

        // Filter berdasarkan request
        if ($this->request->periode_id) {
            $query->where('periode_pendaftaran_id', $this->request->periode_id);
        }

        if ($this->request->bulan) {
            $query->whereMonth('created_at', $this->request->bulan);
        }

        if ($this->request->tahun) {
            $query->whereYear('created_at', $this->request->tahun);
        }

        if ($this->request->status) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->status_pembayaran) {
            $query->whereHas('payments', function($q) {
                if ($this->request->status_pembayaran === 'belum_bayar') {
                    $q->where('status', '!=', 'confirmed');
                } else {
                    $q->where('status', $this->request->status_pembayaran);
                }
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Pendaftaran',
            'Nama Lengkap',
            'Email',
            'Periode',
            'Jalur',
            'Status Pendaftaran',
            'Status Pembayaran',
            'Tanggal Daftar'
        ];
    }

    public function map($pendaftar): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        // Status pembayaran
        $statusPembayaran = 'Belum Bayar';
        if ($pendaftar->payments->isNotEmpty()) {
            $latestPayment = $pendaftar->payments->sortByDesc('created_at')->first();
            switch ($latestPayment->status) {
                case 'confirmed':
                    $statusPembayaran = 'Sudah Bayar';
                    break;
                case 'pending':
                    $statusPembayaran = 'Menunggu Verifikasi';
                    break;
                case 'rejected':
                    $statusPembayaran = 'Ditolak';
                    break;
            }
        }

        // Status pendaftaran
        $statusPendaftaran = ucfirst($pendaftar->status);

        return [
            $rowNumber,
            $pendaftar->nomor_pendaftaran,
            $pendaftar->nama_lengkap,
            $pendaftar->email,
            $pendaftar->periodePendaftaran->nama_periode ?? '-',
            $pendaftar->periodePendaftaran->jalurPendaftaran->nama_jalur ?? '-',
            $statusPendaftaran,
            $statusPembayaran,
            $pendaftar->created_at->format('d M Y H:i')
        ];
    }
}
