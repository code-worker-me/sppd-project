<?php

namespace App\Filament\Widgets;

use App\Models\DataPerjalanan;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PerjalananOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Data Perjalanan';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DataPerjalanan::query()
                    ->with(['sppd', 'sppd.users'])
                    ->whereHas('sppd')
            )
            ->columns([
                TextColumn::make('sppd.st')
                    ->label('Surat Tugas')
                    ->searchable()
                    ->description(fn ($record): string => $record->sppd?->user?->name ?? '-'),

                TextColumn::make('sppd.kota')
                    ->label('Kota/Tujuan')
                    ->weight('bold')
                    ->icon('heroicon-o-map-pin')
                    ->color('warning'),

                TextColumn::make('sppd.angkutan')
                    ->label('Angkutan')
                    ->weight('bold')
                    ->formatStateUsing(fn (?string $state): ?string => $state ? ucwords($state) : '-')
                    ->badge()
                    ->icon(fn (string $state): string => match ($state) {
                        'darat' => 'fas-bus',
                        'udara' => 'fas-plane',
                        'laut' => 'fas-ship',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'darat' => 'gray',
                        'udara' => 'success',
                        'laut' => 'warning'
                    }),

                TextColumn::make('jumlah_sppd')
                    ->label('Total SPPD')
                    ->money('IDR')
                    ->color('success'),
            ])
            ->filters([
                SelectFilter::make('angkutan')
                    ->label('Angkutan')
                    ->options([
                        'darat' => 'Darat',
                        'udara' => 'Udara',
                        'laut' => 'Laut',
                    ])
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (DataPerjalanan $record): string => route('filament.admin.resources.data-perjalanans.view', $record))
            ]);
    }
}
