<?php

namespace App\Exports;

use App\Models\DataPerjalanan;
use App\Models\Pagu;
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
    protected $saldoUmum;
    protected $saldoPu;
    protected $currentSaldoUmum;
    protected $currentSaldoPu;
    protected $counter = 0;

    public function __construct($year = null, $month = null)
    {
        $this->year  = $year;
        $this->month = $month;

        $pagu = Pagu::where('tahun_anggaran', $year ?? date('Y'))->first();

        $this->saldoUmum        = $pagu ? $pagu->saldo_umum : 0;
        $this->saldoPu          = $pagu ? $pagu->saldo_pu   : 0;
        $this->currentSaldoUmum = $this->saldoUmum;
        $this->currentSaldoPu   = $this->saldoPu;
    }

    public function collection()
    {
        $query = DataPerjalanan::with(['sppd.users.dataDiri'])
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

        return $query->oldest('created_at')->get();
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
            'Saldo Pengembangan Usaha (PU)',
            'Panjar Kerja',
            'Keterangan',
        ];
    }

    public function map($perjalanan): array
    {
        $this->counter++;

        $jumlah   = $perjalanan->jumlah_sppd ?? 0;
        $kategori = $perjalanan->sppd->kategori ?? 'umum';

        // Saldo berkurang & tampil HANYA sesuai kategori
        if ($kategori === 'umum') {
            $this->currentSaldoUmum -= $jumlah;
            $saldoUmumBaris = $this->currentSaldoUmum;
            $saldoPuBaris   = '';
        } else {
            $this->currentSaldoPu -= $jumlah;
            $saldoUmumBaris = '';
            $saldoPuBaris   = $this->currentSaldoPu;
        }

        $namaUser = $perjalanan->sppd->users->pluck('name')->join(', ') ?: '-';

        return [
            $this->counter,
            $namaUser,
            $perjalanan->sppd->deskripsi ?? '-',
            $perjalanan->sppd->tg_berangkat ? $perjalanan->sppd->tg_berangkat->format('Y-m-d') : '-',
            $perjalanan->sppd->tg_pulang    ? $perjalanan->sppd->tg_pulang->format('Y-m-d')    : '-',
            ucfirst($perjalanan->tipe_perjalanan ?? 'Darat'),
            $perjalanan->sppd->st ?? '-',
            $perjalanan->uang_harian            ?? 0,
            $perjalanan->uang_representasi      ?? 0,
            $perjalanan->tiket_pergi            ?? 0,
            $perjalanan->tiket_pulang           ?? 0,
            $perjalanan->transport_lokal_pergi  ?? 0,
            $perjalanan->transport_lokal_pulang ?? 0,
            $perjalanan->bbm_tol                ?? 0,
            $perjalanan->hotel                  ?? 0,
            $jumlah,
            $saldoUmumBaris,
            $saldoPuBaris,
            '-',
            '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getRowDimension(1)->setRowHeight(40);
        $sheet->getStyle('A1:T1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 11,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'C2CEFC'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'A0A0A0'],
                ],
            ],
        ]);

        $sheet->getStyle('C:C')->applyFromArray([
            'alignment' => ['wrapText' => true],
        ]);

        foreach (['A:A', 'B:B', 'D:T'] as $col) {
            $sheet->getStyle($col)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        $highestRow = $sheet->getHighestRow();
        if ($highestRow > 1) {
            $sheet->getStyle('H2:R' . $highestRow)->applyFromArray([
                'numberFormat' => ['formatCode' => '"Rp" #,##0'],
            ]);
        }
    }

    public function columnWidths(): array
    {
        return [
            'C' => 35,
            'Q' => 20,
            'R' => 25,
        ];
    }

    public function title(): string
    {
        return 'SPPD ' . ($this->year ?? date('Y'));
    }
}