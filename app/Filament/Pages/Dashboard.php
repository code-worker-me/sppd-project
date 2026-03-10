<?php

namespace App\Filament\Pages;

use App\Exports\SppdExport;
use App\Filament\Widgets\DataPegawai;
use App\Filament\Widgets\DataPerjalanan;
use App\Filament\Widgets\PaguSaldoOverview;
use App\Filament\Widgets\PerjalananOverview;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Maatwebsite\Excel\Facades\Excel;

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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_sppd')
                ->label('Export SPPD ke Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->form([
                    Select::make('year')
                        ->label('Tahun')
                        ->options(function () {
                            $years = [];
                            for ($i = date('Y'); $i <= date('Y'); $i++) {
                                $years[$i] = $i;
                            }
                            return $years;
                        })
                        ->default(date('Y'))
                        ->required()
                        ->native(false),

                    Select::make('month')
                        ->label('Bulan (Opsional)')
                        ->options([
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ])
                        ->placeholder('Semua Bulan')
                        ->native(false),
                ])
                ->action(function (array $data) {
                    $year = $data['year'];
                    $month = $data['month'] ?? null;

                    $filename = 'SPPD_TVRI_YOGYAKARTA_' . $year;
                    if ($month) {
                        $filename .= '_' . str_pad($month, 2, '0', STR_PAD_LEFT);
                    }
                    $filename .= '.xlsx';

                    return Excel::download(new SppdExport($year, $month), $filename);
                })
                ->modalHeading('Export Data SPPD')
                ->modalDescription('Pilih tahun dan bulan (opsional) untuk export data SPPD ke Excel')
                ->modalSubmitActionLabel('Export')
                ->modalWidth('md')
        ];
    }

    public function getWidgets(): array
    {
        return [
            PaguSaldoOverview::class,
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
