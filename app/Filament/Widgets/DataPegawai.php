<?php

namespace App\Filament\Widgets;

use App\Models\DataDiri;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DataPegawai extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Data Pegawai';

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
                    ->label("Hak Akses")
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'gray',
                        'staff' => 'warning',
                        'user' => 'success',
                    })
                    ->formatStateUsing(fn (?string $state): ?string => $state ? ucwords($state) : '-')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25])
            ->poll('60s')
            ->filters([
                SelectFilter::make('unit_kerja')
                    ->label("Unit Kerja")
                    ->options(fn () => DataDiri::pluck('unit_kerja', 'unit_kerja')->unique()->toArray())
                    ->native(false),

                SelectFilter::make('role')
                    ->label("Hak Akses")
                    ->relationship('user', 'role')
                    ->preload()
                    ->options([
                        'admin' => 'Admin',
                        'staff' => 'Staff',
                        'user' => 'User'
                    ])
                    ->native(false)
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (DataDiri $record): string => route('filament.admin.resources.data-diris.view', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
