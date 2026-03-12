<?php

namespace App\Filament\Widgets;

use App\Models\Pagu;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaguSaldoOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $tahunIni = date('Y');
        $pagu = Pagu::where('tahun_anggaran', $tahunIni)->first();
        $saldoUmum = $pagu ? $pagu->saldo_umum : 0;
        $saldoPu = $pagu ? $pagu->saldo_pu : 0;

        return [
            Stat::make('Total Saldo Umum', 'Rp ' . number_format($saldoUmum, 0, ',', '.'))
                ->description('Anggaran Tahun ' . $tahunIni)
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Saldo Pengembangan Usaha (PU', 'Rp ' . number_format($saldoPu, 0, ',', '.'))
                ->description('Anggaran Tahun ' . $tahunIni)
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info')
                ->chart([3, 15, 4, 17, 7, 2, 10]),
        ];
    }
}
