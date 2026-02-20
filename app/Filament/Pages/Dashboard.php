<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DataPegawai;
use App\Filament\Widgets\DataPerjalanan;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard SPPD';

    protected static ?string $navigationLabel = 'Dashboard';

    public function getHeading(): string
    {
        return 'Dashboard Perjalanan Dinas';
    }

    public function getSubheading(): ?string
    {
        return 'Statistik dan ringkasan perjalanan dinas pegawai';
    }

    public function getWidgets(): array
    {
        return [
            DataPerjalanan::class,
            DataPegawai::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 3,
            'lg' => 3,
            'xl' => 3,
        ];
    }
}
