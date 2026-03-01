<?php

namespace App\Exports;

use App\Models\DataPerjalanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SppdExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    ShouldAutoSize,
    WithStyles,
    WithColumnWidths
{
    protected $year;
    protected $month;

    public function __construct($year = null, $month = null)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $query = DataPerjalanan::with(['sppd.user.dataDiri'])
            ->whereHas('sppd');

        if ($this->year) {
            $query->whereHas('sppd', function ($q) {
                $q->whereYear('tg_berangkat', $this->year);
            });
        }

        if ($this->month) {
            $query->whereHas('sppd', function ($q) {
                $q->whereMonth('tg_berangkat', $this->month);
            });
        }

        return $query->latest('created_at')->get();
    }

    public function headings(): array
    {
        return [
                'No',
                'Nama',
                'Kegiatan',
                'Tanggal Mulai',
                'Tanggal Selesai',
                'Angkutan',
                'Dasar SPPD',
                'Uang Harian',
                'Uang Representasi',
                'Tiket Pergi',
                'Tiket Pulang',
                'Transport Lokal Pergi',
                'Transport Lokal Pulang',
                'BBM + Tol',
                'Penginapan /Hotel',
                'Jumlah SPPD',
                'Saldo Umum',
                'Saldo Pengembangan Usaha',
                'Panjar Kerja',
                'Keterangan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getRowDimension(1)->setRowHeight(40);
        $sheet->getStyle('A1:T1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => '000000'], // Text putih agar kontras dengan background biru
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'C2CEFC'] // Biru (Microsoft Blue)
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true, // Wrap text untuk header panjang
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'A0A0A0']
                ],
            ],
        ]);

        $sheet->getStyle('C:C')->applyFromArray([
            'alignment' => [
                'wrapText' => true
            ]
        ]);

        $sheet->getStyle('A:A')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        $sheet->getStyle('D:T')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        $sheet->getStyle('B:B')->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        $highestRow = $sheet->getHighestRow();

        if ($highestRow > 1)
        {
            $sheet->getStyle('H2:S' . $highestRow)->applyFromArray([
                'numberFormat' => [
                    'formatCode' => 'Rp #,##0',
                ]
            ]);
        }
    }

    public function columnWidths(): array
    {
        return [
            'C' => 35,
        ];
    }

    public function map($perjalanan): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $perjalanan->sppd->user->name ?? '-',
            $perjalanan->sppd->deskripsi ?? '-',
            $perjalanan->sppd->tg_berangkat ? $perjalanan->sppd->tg_berangkat->format('Y-m-d') : '-',
            $perjalanan->sppd->tg_pulang ? $perjalanan->sppd->tg_pulang->format('Y-m-d') : '-',
            ucfirst($perjalanan->tipe_perjalanan ?? 'Darat'),
            $perjalanan->sppd->st ?? '-',
            $perjalanan->uang_harian ?? 0,
            $perjalanan->uang_representasi ?? 0,
            $perjalanan->tiket_pergi ?? 0,
            $perjalanan->tiket_pulang ?? 0,
            $perjalanan->transport_lokal_pergi ?? 0,
            $perjalanan->transport_lokal_pulang ?? 0,
            $perjalanan->bbm_tol ?? 0,
            $perjalanan->hotel ?? 0,
            $perjalanan->jumlah_sppd ?? 0,
            '', // Saldo SPPD - bisa dikosongkan atau dihitung
            '',
            '-',
            '', // Keterangan
        ];
    }

    public function title(): string
    {
        return 'SPPD ' . ($this->year ?? date('Y'));
    }
}
