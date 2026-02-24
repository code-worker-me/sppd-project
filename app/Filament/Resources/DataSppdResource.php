<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataSppdResource\Pages;
use App\Models\DataSppd;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DataSppdResource extends Resource
{
    protected static ?string $model = DataSppd::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'SPPD';

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
                        Select::make('user_id')
                            ->label('Pegawai/Staff')
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn ($query) => $query->has('dataDiri')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false)
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.
                                ($record->dataDiri?->nip ? ' - NIP: '.$record->dataDiri->nip : '')
                            )
                            ->helperText('Hanya pegawai yang terdaftar dapat dipilih'),

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

                TextColumn::make('user.name')
                    ->label('Pegawai')
                    ->description(fn (DataSppd $record): string => $record->user?->dataDiri?->nip ?? '-')
                    ->searchable(),

                TextColumn::make('kota')
                    ->label('Kota/Tujuan')
                    ->icon('ionicon-pin-sharp')
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
                SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

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

                        TextEntry::make('user.name')
                            ->label('Nama Pegawai')
                            ->color('secondary')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('kota')
                            ->label('Kota Tujuan')
                            ->weight('bold')
                            ->icon('heroicon-o-map-pin')
                            ->color('warning'),

                        TextEntry::make('user.dataDiri.unit_kerja')
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
                            ->prefix("hari: ")
                            ->color('secondary'),

                        TextEntry::make('angkutan')
                            ->label('Angkutan')
                            ->weight('bold')
                            ->badge()
                            ->icon(fn (string $state): string => match ($state) {
                                'darat' => 'heroicon-o-truck',
                                'udara' => 'heroicon-o-paper-airplane',
                                'laut' => 'heroicon-o-backward'
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
