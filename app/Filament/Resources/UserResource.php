<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\DataDiriRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\SppdsRelationManager;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter; // Alias agar tidak bentrok dengan Form Section
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'User';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Data User';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi User')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->placeholder('IR. Sugi Mukhlis, ST, M.eng')
                            ->required(fn (string $context): bool => $context === 'create'),
                        TextInput::make('email')
                            ->email()
                            ->placeholder('mukhlis@gmail.com')
                            ->required(fn (string $context): bool => $context === 'create')
                            ->disabledOn('edit')
                            ->unique()
                            ->validationMessages([
                                'unique' => 'Oops! Email ini sudah terdaftar, Silahkan gunakan email lain.'
                            ]),
                        TextInput::make('password')
                            ->password()
                            ->placeholder('*********')
                            ->required(fn (string $context): bool => $context === 'create')
                            ->hiddenOn('edit'),
                        Select::make('Hak Akses')
                            ->options([
                                'user' => 'User',
                                'admin' => 'Admin',
                                'staff' => 'Staff',
                            ])
                            ->required(fn (string $context): bool => $context === 'create')
                            ->default('user')
                            ->native(false),
                    ]),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'success',
                        'staff' => 'warning',
                        'user' => 'gray',
                    }),
                TextColumn::make('sppds_count')
                    ->counts('sppds')
                    ->label('Total SPPD')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'user' => 'User',
                        'admin' => 'Admin',
                        'staff' => 'Staff',
                    ])->native(false),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data User')
                    ->modalDescription('Apakah Anda yakin ingin menghapus user ini? Seluruh data yang terkait akan ikut terhapus dan tidak dapat dikembalikan.')
                    ->modalSubmitActionLabel('Ya, Hapus Sekarang')
                    ->modalCancelActionLabel('Batal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SppdsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
