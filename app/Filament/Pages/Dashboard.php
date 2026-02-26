<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DataPegawai;
use App\Filament\Widgets\DataPerjalanan;
use App\Filament\Widgets\PerjalananOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'SPPD';

    protected static ?string $navigationLabel = 'Dashboard';

    public function getHeading(): string
    {
        return 'Dashboard SPPD';
    }

    public function getSubheading(): ?string
    {
        return 'Table dan ringkasan perjalanan dinas pegawai SPPD.';
    }

    public function getWidgets(): array
    {
        return [
            DataPerjalanan::class,
            PerjalananOverview::class,
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
