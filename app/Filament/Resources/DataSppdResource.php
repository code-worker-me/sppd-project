<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataSppdResource\Pages;
use App\Models\DataSppd;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class DataSppdResource extends Resource
{
    protected static ?string $model = DataSppd::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'SPPD (SDM)';

    protected static ?string $modelLabel = 'SPPD';

    protected static ?string $pluralModelLabel = 'Data SPPD';

    protected static ?string $navigationGroup = 'SPPD Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('SPPD Information')
                    ->schema([
                        Select::make('jenis_st')
                            ->label('Kategori Surat Tugas')
                            ->options([
                                'umum' => 'DIPA Umum',
                                'pu' => 'DIPA Pekerjaan Umum (PU)',
                            ])
                            ->required(),

                        Select::make('users')
                            ->label('Pegawai yang Ditugaskan')
                            ->relationship(name: 'users', titleAttribute: 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required(),

                        TextInput::make('st')
                            ->label('Nomor Surat Tugas')
                            ->required()
                            ->placeholder('019')
                            ->unique()
                            ->maxLength(255),

                        TextInput::make('kota')
                            ->required()
                            ->placeholder('Jakarta')
                            ->label('Kota Tujuan')
                            ->maxLength(255),

                        Select::make('angkutan')
                            ->required()
                            ->label('Angkutan')
                            ->options([
                                'darat' => 'Darat',
                                'udara' => 'Udara',
                                'laut' => 'Laut',
                            ])
                            ->default('darat'),

                        Textarea::make('deskripsi')
                            ->label('Uraian Kegiatan')
                            ->placeholder('Penyampaian Proposal Penawaran Kerjasama Program Ramadhan 2026.')
                            ->rows(3)
                            ->columnSpanFull(),

                        DatePicker::make('tg_berangkat')
                            ->label('Tanggal Berangkat')
                            ->native(false)
                            ->displayFormat('d-m-Y')
                            ->placeholder('20-01-2026')
                            ->required(),

                        DatePicker::make('tg_pulang')
                            ->label('Tanggal Pulang')
                            ->native(false)
                            ->displayFormat('d-m-Y')
                            ->after('tg_berangkat')
                            ->placeholder('27-01-2026')
                            ->required(),
                    ])->columns(2),

                Section::make('Lampiran Dokumen SPPD')
                    ->relationship('lampiran')
                    ->schema([
                        FileUpload::make('laporan_perjalanan')
                            ->label('Laporan Perjalanan')
                            ->multiple(true)
                            ->disk('public')
                            ->directory('lampiran-perjalanan')
                            ->reorderable(true)
                            ->openable(true)
                            ->downloadable(true),

                        FileUpload::make('foto_kegiatan')
                            ->label('Foto Kegiatan')
                            ->multiple(true)
                            ->disk('public')
                            ->directory('lampiran-kegiatan')
                            ->reorderable(true)
                            ->openable(true)
                            ->downloadable(true),

                        FileUpload::make('blanko_sppd')
                            ->label('Blanko SPPD')
                            ->multiple(true)
                            ->disk('public')
                            ->directory('lampiran-blanko')
                            ->reorderable(true)
                            ->openable(true)
                            ->downloadable(true),

                        FileUpload::make('surat_tugas')
                            ->label('Surat Tugas')
                            ->multiple(true)
                            ->disk('public')
                            ->directory('lampiran-st')
                            ->reorderable(true)
                            ->openable(true)
                            ->downloadable(true),
                    ])
                    ->columns(2)
                    ->collapsed(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('st')
                    ->label('Surat Tugas Nomor')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('users.name')
                    ->label('Pegawai')
                    ->listWithLineBreaks()
                    ->limitList(1)
                    ->searchable(),

                TextColumn::make('kota')
                    ->label('Kota/Tujuan')
                    ->icon('ionicon-pin-sharp')
                    ->iconColor('warning')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('angkutan')
                    ->label('Angkutan')
                    ->sortable()
                    ->formatStateUsing(fn (?string $state): ?string => $state ? ucwords($state) : '-')
                    ->searchable()
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
            ])
            ->filters([
                Filter::make('tg_berangkat')
                    ->form([
                        DatePicker::make('from')
                            ->label('Berangkat')
                            ->placeholder('20-02-2025')
                            ->native(false),
                        DatePicker::make('until')
                            ->label('Pulang')
                            ->placeholder('26-02-2025')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tg_berangkat', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tg_berangkat', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Lihat'),
                EditAction::make(),
                DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Data Pilihan'),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make('Informasi Data SPPD')
                    ->description('Detail SPPD')
                    ->schema([
                        TextEntry::make('st')
                            ->label('Nomor Surat Tugas')
                            ->weight('bold')
                            ->color('primary')
                            ->copyable()
                            ->icon('heroicon-o-document-text'),

                        TextEntry::make('users.name')
                            ->label('Nama Pegawai')
                            ->color('secondary')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('kota')
                            ->label('Kota Tujuan')
                            ->weight('bold')
                            ->icon('heroicon-o-map-pin')
                            ->color('warning'),

                        TextEntry::make('users.dataDiri.unit_kerja')
                            ->label('Unit Kerja')
                            ->color('secondary')
                            ->icon('heroicon-s-briefcase')
                            ->formatStateUsing(fn (?string $state): ?string => $state ? ucwords($state) : '-'),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('tg_berangkat')
                                    ->label('Tanggal Berangkat')
                                    ->date('d F Y')
                                    ->icon('heroicon-s-calendar'),

                                TextEntry::make('tg_pulang')
                                    ->label('Tanggal Pulang')
                                    ->date('d F Y')
                                    ->icon('heroicon-s-calendar'),

                            ]),

                        TextEntry::make('durasi')
                            ->label('Durasi Perjalanan')
                            ->weight('bold')
                            ->icon('heroicon-s-clock')
                            ->prefix('hari: ')
                            ->color('secondary'),

                        TextEntry::make('angkutan')
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

                        TextEntry::make('deskripsi')
                            ->label('Uraian Perjalanan')
                            ->columnSpanFull(),
                    ])->columns(2),

                InfolistSection::make('Lampiran Dokumen SPPD')
                    ->description('Daftar file dan dokumentasi perjalanan dinas.')
                    ->schema([
                        ImageEntry::make('lampiran.foto_kegiatan')
                            ->label('Foto Kegiatan')
                            ->disk('public')
                            ->stacked(true)
                            ->extraImgAttributes(['style' => 'border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);']),

                        TextEntry::make('lampiran.laporan_perjalanan')
                            ->label('Laporan Perjalanan')
                            ->html()
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return '<span class="text-gray-500">- Belum ada file -</span>';
                                }

                                $files = is_string($state) ? (json_decode($state, true) ?: [$state]) : $state;
                                $html = '<div class="flex flex-col gap-2">';
                                foreach ($files as $index => $file) {
                                    $url = Storage::disk('public')->url($file);
                                    $html .= '<a href="'.$url.'" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-800 hover:underline font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Laporan '.($index + 1).'
                              </a>';
                                }

                                return $html.'</div>';
                            }),

                        TextEntry::make('lampiran.blanko_sppd')
                            ->label('Blanko SPPD')
                            ->html()
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return '<span class="text-gray-500">- Belum ada file -</span>';
                                }

                                $files = is_string($state) ? (json_decode($state, true) ?: [$state]) : $state;
                                $html = '<div class="flex flex-col gap-2">';
                                foreach ($files as $index => $file) {
                                    $url = Storage::disk('public')->url($file);
                                    $html .= '<a href="'.$url.'" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-800 hover:underline font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                Blanko '.($index + 1).'
                              </a>';
                                }

                                return $html.'</div>';
                            }),

                        TextEntry::make('lampiran.surat_tugas')
                            ->label('Surat Tugas')
                            ->html()
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return '<span class="text-gray-500">- Belum ada file -</span>';
                                }

                                $files = is_string($state) ? (json_decode($state, true) ?: [$state]) : $state;
                                $html = '<div class="flex flex-col gap-2">';
                                foreach ($files as $index => $file) {
                                    $url = Storage::disk('public')->url($file);
                                    $html .= '<a href="'.$url.'" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-800 hover:underline font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                Surat Tugas '.($index + 1).'
                              </a>';
                                }

                                return $html.'</div>';
                            }),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataSppds::route('/'),
            'create' => Pages\CreateDataSppd::route('/create'),
            'view' => Pages\ViewDataSppd::route('/{record}'),
            'edit' => Pages\EditDataSppd::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
