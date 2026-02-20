<?php

namespace App\Filament\Widgets;

use App\Models\DataDiri;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DataPegawai extends BaseWidget
{
    // Atau spesifik jumlah kolom
    protected int|string|array $columnSpan = 3; // 3 kolom dari grid

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DataDiri::query()
                    ->with(['user', 'user.sppds']) // Eager loading
                    ->whereHas('user')
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-user')
                    ->description(fn (DataDiri $record): string => $record->user->email ?? '-'),

                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('NIP berhasil disalin!')
                    ->placeholder('Belum ada NIP'),

                TextColumn::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-building-office')
                    ->placeholder('Belum diisi'),

                TextColumn::make('pangkat')
                    ->label('Pangkat')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-star')
                    ->placeholder('Belum diisi'),

                TextColumn::make('user.role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'gray',
                        'staff' => 'warning',
                        'user' => 'success',
                    })
                    ->formatStateUsing(fn (?string $state): ?string => $state ? ucwords($state) : '-')
                    ->sortable(),
            ]);
    }
}
