<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataPerjalananResource\Pages;
use App\Models\DataPerjalanan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DataPerjalananResource extends Resource
{
    protected static ?string $model = DataPerjalanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Perjalanan';

    protected static ?string $modelLabel = 'Perjalanan';

    protected static ?string $pluralModelLabel = 'Data Perjalanan';

    protected static ?string $navigationGroup = 'SPPD Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Perjalanan')
                    ->schema([
                        Select::make('sppd_id')
                            ->label('Pilih Surat Tugas')
                            ->relationship('sppd', 'st')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false)
                            ->live(),

                        Select::make('tipe_perjalanan')
                            ->label('Perjalanan')
                            ->options([
                                'darat' => 'Darat',
                                'laut' => 'Laut',
                                'udara' => 'Udara',
                            ])
                            ->native(false)
                            ->required(),

                        FileUpload::make('tiket')
                            ->label('Bukti Tiket')
                            ->image()
                            ->disk('public')
                            ->directory('tiket')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->downloadable()
                            ->openable()
                            ->previewable(true) // PENTING: Bisa preview
                            ->imagePreviewHeight('250')
                            ->panelLayout('integrated')
                            ->deletable(true) // PENTING: Bisa delete gambar lama
                            ->reorderable(false)
                            ->columnSpanFull(),

                        FileUpload::make('hotel')
                            ->label('Bukti Hotel')
                            ->image()
                            ->disk('public')
                            ->directory('hotel')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->downloadable()
                            ->openable()
                            ->previewable(true)
                            ->imagePreviewHeight('250')
                            ->panelLayout('integrated')
                            ->deletable(true)
                            ->reorderable(false)
                            ->columnSpanFull(),

                        TextInput::make('uang_saku')
                            ->label('Uang Saku')
                            ->numeric()
                            ->prefix('Rp. ')
                            ->default(0)
                            ->required(),

                        TextInput::make('transport')
                            ->label('Transport')
                            ->numeric()
                            ->prefix('Rp. ')
                            ->default(0),
                    ])->columns(2),

                Section::make('Summary')
                    ->schema([
                        Placeholder::make('total_biaya')
                            ->label('Total Biaya')
                            ->content(function (Get $get) {
                                $uangSaku = (int) $get('uang_saku');
                                $transport = (int) $get('transport');
                                $total = $uangSaku + $transport;

                                return 'Rp '.number_format($total, 0, ',', ',');
                            }),
                    ])->visibleOn(['create', 'edit']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sppd.user.name')
                    ->label('Pegawai')
                    ->searchable(),

                TextColumn::make('tipe_perjalanan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'darat' => 'gray',
                        'laut' => 'warning',
                        'udara' => 'success',
                    })
                    ->icons([
                        'heroicon-o-truck' => 'darat',
                        'heroicon-o-cloud' => 'laut',
                        'heroicon-o-paper-airplane' => 'udara',
                    ])
                    ->sortable(),

                TextColumn::make('sppd.kota')
                    ->label('Kota/Tujuan')
                    ->searchable(),

                TextColumn::make('uang_saku')
                    ->label('Uang Saku')
                    ->money('IDR'),

                TextColumn::make('transport')
                    ->label('Biaya Transport')
                    ->money('IDR'),
            ])
            ->filters([
                SelectFilter::make('tipe_perjalanan')
                    ->options([
                        'darat' => 'Darat',
                        'laut' => 'Laut',
                        'udara' => 'Udara',
                    ])->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Informasi SPPD
                InfolistSection::make('Informasi Surat Tugas')
                    ->description('Detail dari perjalanan dinas yang diajukan.')
                    ->schema([
                        TextEntry::make('sppd.st')
                            ->label('Nomor Surat Tugas')
                            ->weight('bold')
                            ->color('primary')
                            ->icon('heroicon-o-document-text'),

                        TextEntry::make('sppd.user.name')
                            ->label('Nama Pegawai')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('sppd.deskripsi')
                            ->label('Uraian Perjalanan')
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('sppd.kota')
                                    ->label('Kota Tujuan')
                                    ->icon('heroicon-o-map-pin')
                                    ->color('success'),

                                TextEntry::make('sppd.tg_berangkat')
                                    ->label('Tanggal Berangkat')
                                    ->date('d F Y')
                                    ->icon('heroicon-o-calendar'),

                                TextEntry::make('sppd.tg_pulang')
                                    ->label('Tanggal Pulang')
                                    ->date('d F Y')
                                    ->icon('heroicon-o-calendar'),
                            ]),
                    ])
                    ->columns(2),

                // Informasi Perjalanan
                InfolistSection::make('Detail Perjalanan')
                    ->description('Informasi detail perjalanan dan biaya.')
                    ->schema([
                        TextEntry::make('tipe_perjalanan')
                            ->label('Tipe Perjalanan')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'darat' => 'gray',
                                'laut' => 'warning',
                                'udara' => 'success',
                            })
                            ->formatStateUsing(fn (?string $state): ?string => $state ? ucwords($state) : '-'),

                        TextEntry::make('uang_saku')
                            ->label('Uang Saku')
                            ->money('IDR')
                            ->icon('heroicon-o-banknotes'),

                        TextEntry::make('transport')
                            ->label('Biaya Transport')
                            ->money('IDR')
                            ->icon('heroicon-o-truck'),

                        TextEntry::make('total_biaya')
                            ->label('Total Biaya')
                            ->money('IDR')
                            ->weight('bold')
                            ->color('success')
                            ->icon('heroicon-o-calculator')
                            ->size(TextEntry\TextEntrySize::Large),
                    ])
                    ->columns(2),

                // Dokumen Lampiran
                InfolistSection::make('Dokumen Lampiran')
                    ->description('Bukti atau dokumen pendukung perjalanan.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                InfolistSection::make('Bukti Tiket')
                                    ->schema([
                                        ImageEntry::make('tiket')
                                            ->hiddenLabel()
                                            ->defaultImageUrl(url('/images/no-image.png'))
                                            ->size(400)
                                            ->extraImgAttributes([
                                                'alt' => 'Bukti Tiket',
                                                'loading' => 'lazy',
                                                'class' => 'rounded-lg shadow-md object-cover',
                                            ]),
                                    ])
                                    ->collapsible(),

                                InfolistSection::make('Bukti Hotel')
                                    ->schema([
                                        ImageEntry::make('hotel')
                                            ->hiddenLabel()
                                            ->defaultImageUrl(url('/images/no-image.png'))
                                            ->size(400)
                                            ->extraImgAttributes([
                                                'alt' => 'Bukti Hotel',
                                                'loading' => 'lazy',
                                                'class' => 'rounded-lg shadow-md object-cover',
                                            ]),
                                    ])
                                    ->collapsible(),
                            ]),
                    ])
                    ->collapsible(),
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
            'index' => Pages\ListDataPerjalanans::route('/'),
            'create' => Pages\CreateDataPerjalanan::route('/create'),
            'view' => Pages\ViewDataPerjalanan::route('/{record}'),
            'edit' => Pages\EditDataPerjalanan::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
