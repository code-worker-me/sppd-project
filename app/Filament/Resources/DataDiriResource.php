<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataDiriResource\Pages;
use App\Models\DataDiri;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\ViewAction;

class DataDiriResource extends Resource
{
    protected static ?string $model = DataDiri::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Pengaturan Pegawai';

    protected static ?string $navigationLabel = 'Data Pegawai';

    protected static ?string $modelLabel = 'Staff';

    protected static ?string $pluralModelLabel = 'Data Pegawai/Staff';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pegawai/Staff SPPD')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pilih Pegawai (Pilih atau Buat Baru)')
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query, $record) => $query
                                    ->whereDoesntHave('dataDiri')
                                    ->when($record, fn ($q) => $q->orWhere('id', $record->user_id)))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->unique()
                            ->native(false)
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->placeholder('IR. Sugi Mukhlis, ST, M.eng')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->email()
                                    ->label('Email')
                                    ->required()
                                    ->unique('users', 'email')
                                    ->placeholder('mukhlis@gmail.com')
                                    ->maxLength(255)
                                    ->validationMessages([
                                        'unique' => 'Oops! Email ini sudah terdaftar, Silahkan gunakan email lain.'
                                    ]),

                                TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->required()
                                    ->placeholder('*********')
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),

                                Select::make('role')
                                    ->label('Hak Akses')
                                    ->options([
                                        'user' => 'User',
                                        'staff_sdm' => 'Staff SDM',
                                        'staff_keuangan' => 'Staff Keuangan',
                                        'admin' => 'Admin',
                                    ])
                                    ->default('user')
                                    ->required(),
                            ])
                            ->createOptionAction(fn (Action $action) => $action
                                ->modalHeading('Buat Akun Login baru')
                                ->modalSubmitActionLabel('Simpan Akun')
                                ->modalCancelActionLabel('Batal')
                            ),

                        TextInput::make('nip')
                            ->label('NIP')
                            ->placeholder('198001012003001001')
                            ->numeric()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('unit_kerja')
                            ->label('Unit Kerja')
                            ->options([
                                'sekretariat / tata usaha' => 'Sekretariat/Tata Usaha',
                                'program' => 'Program',
                                'berita' => 'Berita',
                                'teknik' => 'Teknik',
                                'pengembangan usaha' => 'Pengembangan Usaha',
                                'konten Media Baru' => 'Konten Media Baru',
                                'keuangan' => 'Keuangan',
                                'umum' => 'Umum',
                            ])
                            ->default('staff')
                            ->required(),

                        TextInput::make('pangkat')
                            ->label('Pangkal/Gol')
                            ->maxLength(255)
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Nama Pegawai')
                    ->searchable(),
                TextColumn::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->formatStateUsing(fn (?string $state): ?string => $state ? ucwords($state) : '-')
                    ->searchable(),
                TextColumn::make('pangkat')
                    ->label('Pangkat/Gol')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Pegawai')
                    ->modalDescription('Apakah Anda yakin ingin menghapus akun pegawai ini? Seluruh data yang terkait akan ikut terhapus dan tidak dapat dikembalikan.')
                    ->modalSubmitActionLabel('Ya, Hapus Sekarang')
                    ->modalCancelActionLabel('Batal'),
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
                InfolistSection::make('Informasi Data Pegawai')
                    ->schema([
                        TextEntry::make('nip')
                            ->label("NIP")
                            ->weight("bold"),

                        TextEntry::make("user.name")
                            ->label("Nama Pegawai")
                            ->icon('heroicon-o-user'),

                        TextEntry::make("user.email")
                            ->label("Email Pegawai")
                            ->copyable()
                            ->icon('heroicon-o-at-symbol'),

                        TextEntry::make('unit_kerja')
                            ->label("Unit Kerja")
                            ->icon('heroicon-s-briefcase')
                            ->formatStateUsing(fn (?string $state): ?string => $state ? ucwords($state) : '-'),

                        TextEntry::make('pangkat')
                            ->label('Pangkat/Gol'),

                        TextEntry::make('user.role')
                            ->label('Hak Akses')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): ?string => $state ? ucwords($state) : '-'),
                    ])->columns(2)
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
            'index' => Pages\ListDataDiris::route('/'),
            'create' => Pages\CreateDataDiri::route('/create'),
            'view' => Pages\ViewDataDiri::route('/{record}'),
            'edit' => Pages\EditDataDiri::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
