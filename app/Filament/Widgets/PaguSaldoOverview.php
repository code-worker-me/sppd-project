<?php

namespace App\Filament\Widgets;

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

        $pagu = Pagu::where('tahun_anggaran', $tahunIni)->first();
        $saldoSisaUmum = $pagu ? $pagu->saldo_umum : 0;
        $saldoSisaPu = $pagu ? $pagu->saldo_pu : 0;
        $paguAwalUmum = $pagu ? $pagu->pagu_awal_umum : 0;
        $paguAwalPu = $pagu ? $pagu->pagu_awal_pu : 0;

        return [
            Stat::make('Saldo Pagu Umum', 'Rp '.number_format($saldoSisaUmum, 0, ',', '.'))
                ->description('Pagu Awal: Rp '.number_format($paguAwalUmum, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Saldo Pagu PU', 'Rp '.number_format($saldoSisaPu, 0, ',', '.'))
                ->description('Pagu Awal: Rp '.number_format($paguAwalPu, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info')
                ->chart([3, 15, 4, 17, 7, 2, 10]),
        ];
    }
}