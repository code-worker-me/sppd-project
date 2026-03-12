<?php

namespace App\Filament\Widgets;

use App\Models\DataPerjalanan;
use App\Models\Pagu;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaguSaldoOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $tahunIni = date('Y');

        // 1. Ambil Sisa Saldo dari tabel pagus
        $pagu = Pagu::where('tahun_anggaran', $tahunIni)->first();
        $saldoSisaUmum = $pagu ? $pagu->saldo_umum : 0;
        $saldoSisaPu = $pagu ? $pagu->saldo_pu : 0;

        // 2. Hitung Total Pengeluaran dari tabel data_perjalanan tahun ini
        $pengeluaranUmum = DataPerjalanan::whereHas('sppd', function ($query) {
            $query->where('jenis_st', 'umum');
        })->whereYear('created_at', $tahunIni)->sum('jumlah_sppd');

        $pengeluaranPu = DataPerjalanan::whereHas('sppd', function ($query) {
            $query->where('jenis_st', 'pu');
        })->whereYear('created_at', $tahunIni)->sum('jumlah_sppd');

        // 3. Rumus Pagu Awal (Sisa Saldo + Total Pengeluaran)
        $paguAwalUmum = $saldoSisaUmum + $pengeluaranUmum;
        $paguAwalPu = $saldoSisaPu + $pengeluaranPu;

        return [
            Stat::make('Saldo Pagu Umum', 'Rp '.number_format($saldoSisaUmum, 0, ',', '.'))
                ->description('Total Pagu Awal: Rp '.number_format($paguAwalUmum, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Saldo Pagu PU', 'Rp '.number_format($saldoSisaPu, 0, ',', '.'))
                ->description('Total Pagu Awal: Rp '.number_format($paguAwalPu, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info')
                ->chart([3, 15, 4, 17, 7, 2, 10]),
        ];
    }
}
