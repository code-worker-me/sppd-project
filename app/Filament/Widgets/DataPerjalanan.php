<?php

namespace App\Filament\Widgets;

use App\Models\DataPerjalanan as ModelsDataPerjalanan;
use App\Models\DataSppd;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DataPerjalanan extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Stat::make('Total Perjalanan Dinas', ModelsDataPerjalanan::count())
            //     ->description('Seluruh perjalanan dinas')
            //     ->descriptionIcon('heroicon-m-map')
            //     ->color('success')
            //     ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            // Stat::make('Total SPPD', DataSppd::count())
            //     ->description('Surat tugas yang diterbitkan')
            //     ->descriptionIcon('heroicon-m-document-text')
            //     ->color('info')
            //     ->chart([2, 3, 4, 5, 6, 7, 9, 12]),

            // Stat::make('Total Pegawai', User::has('sppds')->count())
            //     ->description('Pegawai yang pernah dinas')
            //     ->descriptionIcon('heroicon-m-user-group')
            //     ->color('warning'),

            // Stat::make('Perjalanan Bulan Ini', $this->getPerjalananBulanIni())
            //     ->description('Perjalanan di '.now()->translatedFormat('F Y'))
            //     ->descriptionIcon('heroicon-m-calendar')
            //     ->color('primary'),

            // Stat::make('Rata-rata Biaya', $this->formatRupiah($this->getRataBiaya()))
            //     ->description('Per perjalanan dinas')
            //     ->descriptionIcon('heroicon-m-calculator')
            //     ->color('info')
            //     ->chart([8, 3, 6, 3, 2, 6, 9, 12]),

        ];
    }

    private function formatRupiah(int|float $angka): string
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    private function getPerjalananBulanIni(): int
    {
        return DataSppd::whereMonth('tg_berangkat', now()->month)
            ->whereYear('tg_berangkat', now()->year)
            ->count();
    }

    private function getTotalBiaya(): int
    {
        return ModelsDataPerjalanan::selectRaw('SUM(uang_saku + transport) as total')
            ->value('total') ?? 0;
    }

    private function getRataBiaya(): float
    {
        $count = ModelsDataPerjalanan::count();
        return $count > 0 ? $this->getTotalBiaya() / $count : 0;
    }
}
