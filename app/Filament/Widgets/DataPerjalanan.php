<?php

namespace App\Filament\Widgets;

use App\Models\DataPerjalanan as ModelsDataPerjalanan;
use App\Models\DataSppd;
use App\Models\Pagu;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DataPerjalanan extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Perjalanan Dinas', ModelsDataPerjalanan::count())
                ->description('Seluruh perjalanan dinas')
                ->descriptionIcon('heroicon-m-map')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Total Pegawai', User::has('sppds')->count())
                ->description('Pegawai yang pernah dinas')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning')
                ->chart([3, 5, 8, 7, 4, 3, 5, 9]),
        ];
    }
}
