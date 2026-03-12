<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataPerjalananResource\Pages;
use App\Models\DataPerjalanan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid as ComponentsGrid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DataPerjalananResource extends Resource
{
    protected static ?string $model = DataPerjalanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Perjalanan (Keuangan)';

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
                            ->label('Pilih Nomor ST')
                            ->relationship(
                                name: 'sppd',
                                titleAttribute: 'st',
                                modifyQueryUsing: fn ($query) => $query
                                    ->doesntHave('perjalanan') // Hanya SPPD yang belum punya perjalanan
                                    ->with('users')
                                    ->orderBy('created_at', 'desc')
                            )
                            ->searchable(['st', 'kota'])
                            ->preload()
                            ->required()
                            ->native(false)
                            ->placeholder('Pilih nomor surat tugas')
                            ->live()
                            ->helperText('Hanya surat tugas yang belum memiliki data perjalanan'),

                        ComponentsGrid::make(2)
                            ->schema([
                                TextInput::make('tiket_pergi')
                                    ->label('Tiket Pergi')
                                    ->numeric()
                                    ->default(0)
                                    ->placeholder('1.069.900')
                                    ->prefix('Rp. ')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotal($set, $get)),

                                TextInput::make('tiket_pulang')
                                    ->label('Tiket Pulang')
                                    ->numeric()
                                    ->default(0)
                                    ->placeholder('1.069.900')
                                    ->prefix('Rp. ')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotal($set, $get)),
                            ]),

                        TextInput::make('hotel')
                            ->label('Hotel/Penginapan')
                            ->default(0)
                            ->numeric()
                            ->placeholder('1.069.900')
                            ->prefix('Rp. ')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotal($set, $get)),

                        ComponentsGrid::make(2)
                            ->schema([
                                TextInput::make('uang_harian')
                                    ->label('Uang Harian')
                                    ->numeric()
                                    ->default(0)
                                    ->placeholder('1.069.900')
                                    ->prefix('Rp. ')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotal($set, $get)),

                                TextInput::make('uang_representasi')
                                    ->label('Uang Representasi')
                                    ->numeric()
                                    ->default(0)
                                    ->placeholder('1.069.900')
                                    ->prefix('Rp. ')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotal($set, $get)),
                            ]),

                        ComponentsGrid::make(2)
                            ->schema([
                                TextInput::make('transport_lokal_pergi')
                                    ->label('Transport Lokal (Pergi)')
                                    ->numeric()
                                    ->default(0)
                                    ->placeholder('1.069.900')
                                    ->prefix('Rp. ')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotal($set, $get)),

                                TextInput::make('transport_lokal_pulang')
                                    ->label('Transport Lokal (Pulang)')
                                    ->numeric()
                                    ->default(0)
                                    ->placeholder('1.069.900')
                                    ->prefix('Rp. ')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotal($set, $get)),
                            ]),

                        TextInput::make('bbm_tol')
                            ->label('BBM + Toll')
                            ->numeric()
                            ->default(0)
                            ->placeholder('1.069.900')
                            ->prefix('Rp. ')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotal($set, $get)),

                        Hidden::make('jumlah_sppd')
                            ->default(0),
                    ])->columns(2),

                Section::make('Lampiran')
                    ->schema([
                        FileUpload::make('lampiran')
                            ->label('Lampiran (Bisa lebih dari 1)')
                            ->multiple(true)
                            ->disk('public')
                            ->directory('lampiran-perjalanan')
                            ->reorderable(true)
                            ->openable(true)
                            ->downloadable(true)
                            ->columnSpanFull(),
                    ]),

                Section::make('Jumlah SPPD')
                    ->schema([
                        Placeholder::make('total_display')
                            ->label('Total biaya yang dikeluarkan')
                            ->content(function (Get $get) {
                                $total = self::calculateTotal($get);

                                return 'Rp '.number_format($total, 0, ',', '.');
                            }),
                    ])->visibleOn(['create', 'edit']),
            ]);
    }

    public static function calculateTotal(Get $get): int
    {
        $tiketPergi = (int) ($get('tiket_pergi') ?? 0);
        $tiketPulang = (int) ($get('tiket_pulang') ?? 0);
        $hotel = (int) ($get('hotel') ?? 0);
        $uangHarian = (int) ($get('uang_harian') ?? 0);
        $uangRepresentasi = (int) ($get('uang_representasi') ?? 0);
        $transportLokalPergi = (int) ($get('transport_lokal_pergi') ?? 0);
        $transportLokalPulang = (int) ($get('transport_lokal_pulang') ?? 0);
        $bbmTol = (int) ($get('bbm_tol') ?? 0);

        return $tiketPergi + $tiketPulang + $hotel + $uangHarian +
               $uangRepresentasi + $transportLokalPergi +
               $transportLokalPulang + $bbmTol;
    }

    public static function updateTotal(Set $set, Get $get): void
    {
        $total = self::calculateTotal($get);
        $set('jumlah_sppd', $total);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sppd.st')
                    ->label('Nomor Surat Tugas')
                    ->searchable(),

                TextColumn::make('sppd.users.name')
                    ->label('Pegawai'),

                TextColumn::make('sppd.kota')
                    ->label('Kota/Tujuan')
                    ->description(fn ($record): string => $record->sppd?->angkutan ?? '-')
                    ->searchable(),

                TextColumn::make('jumlah_sppd')
                    ->label('Total SPPD')
                    ->money('IDR'),
            ])
            ->filters([
                SelectFilter::make('angkutan')
                    ->options([
                        'darat' => 'Darat',
                        'laut' => 'Laut',
                        'udara' => 'Udara',
                    ])->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                DeleteAction::make()
                    ->label('Hapus'),
                EditAction::make(),
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
                    ->description('Detail SPPD perjalanan dinas yang diajukan.')
                    ->schema([
                        TextEntry::make('sppd.st')
                            ->label('Nomor Surat Tugas')
                            ->weight('bold')
                            ->color('primary')
                            ->icon('heroicon-o-document-text'),

                        TextEntry::make('sppd.user.name')
                            ->label('Nama Pegawai')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('sppd.tg_berangkat')
                            ->label('Tanggal Berangkat')
                            ->date('d F Y')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('sppd.tg_pulang')
                            ->label('Tanggal Pulang')
                            ->date('d F Y')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('sppd.deskripsi')
                            ->label('Uraian Perjalanan')
                            ->columnSpanFull(),

                        TextEntry::make('sppd.kota')
                            ->label('Kota Tujuan')
                            ->icon('heroicon-o-map-pin')
                            ->color('success'),

                        TextEntry::make('sppd.angkutan')
                            ->label('Angkutan')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): ?string => $state ? ucwords($state) : '-')
                            ->color(fn (string $state): string => match ($state) {
                                'darat' => 'gray',
                                'udara' => 'success',
                                'laut' => 'warning'
                            })
                            ->icon(fn (string $state): string => match ($state) {
                                'darat' => 'fas-bus',
                                'udara' => 'fas-plane',
                                'laut' => 'fas-ship',
                            }),
                    ])
                    ->columns(2),

                // Informasi Perjalanan
                InfolistSection::make('Detail Perjalanan')
                    ->description('Informasi detail biaya dikeluarkan.')
                    ->schema([
                        TextEntry::make('tiket_pergi')
                            ->label('Tiket Pergi')
                            ->money('IDR')
                            ->icon('heroicon-o-banknotes'),

                        TextEntry::make('tiket_pulang')
                            ->label('Tiket Pulang')
                            ->money('IDR')
                            ->icon('heroicon-o-banknotes'),

                        TextEntry::make('hotel')
                            ->label('Tiket Hotel')
                            ->money('IDR')
                            ->icon('heroicon-o-banknotes'),

                        TextEntry::make('uang_harian')
                            ->label('Uang Harian')
                            ->money('IDR')
                            ->icon('heroicon-o-banknotes'),

                        TextEntry::make('uang_representasi')
                            ->label('Uang Representasi')
                            ->money('IDR')
                            ->icon('heroicon-o-banknotes'),

                        TextEntry::make('transport_lokal_pergi')
                            ->label('Transport Lokal (Pergi)')
                            ->money('IDR')
                            ->icon('heroicon-o-banknotes'),

                        TextEntry::make('transport_lokal_pulang')
                            ->label('Transport Lokal (Pulang)')
                            ->money('IDR')
                            ->icon('heroicon-o-banknotes'),

                        TextEntry::make('bbm_tol')
                            ->label('BBM + Toll')
                            ->money('IDR')
                            ->icon('heroicon-o-banknotes'),

                        TextEntry::make('jumlah_sppd')
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
                        ImageEntry::make('lampiran')
                            ->label('Bukti Foto')
                            ->extraImgAttributes([
                                'loading' => 'lazy',
                                'class' => 'rounded-lg shadow-md object-cover',
                            ]),
                    ])
                    ->collapsed(true),
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
