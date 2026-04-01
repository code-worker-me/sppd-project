<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaldoPaguResource\Pages;
use App\Models\Pagu;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SaldoPaguResource extends Resource
{
    protected static ?string $model = Pagu::class;

    protected static ?string $navigationIcon = 'far-money-bill-alt';

    protected static ?string $navigationLabel = 'Input Pagu';

    protected static ?string $modelLabel = 'Pagu';

    protected static ?string $pluralModelLabel = ' Input Pagu';

    protected static ?string $navigationGroup = 'SPPD Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Input Pagu')
                    ->schema([
                        TextInput::make('pagu_awal_umum')
                            ->label('Pagu Umum')
                            ->numeric()
                            ->prefix('Rp. ')
                            ->default(0)
                            ->required(),

                        TextInput::make('pagu_awal_pu')
                            ->label('Pagu Pengembangan Usaha (PU)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp. ')
                            ->default(0),

                        TextInput::make('tahun_anggaran')
                            ->label('Tahun Anggaran')
                            ->default(date('Y'))
                            ->numeric()
                            ->minLength(4)
                            ->maxLength(4)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('saldo_umum')
                    ->label('Pagu Umum')        
                    ->money('IDR'),
                TextColumn::make('saldo_pu')
                    ->label('Pagu Pengembangan Usaha (PU)')  
                    ->money('IDR'),
                TextColumn::make('tahun_anggaran')
                    ->label('Tahun Anggaran')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSaldoPagus::route('/'),
            'create' => Pages\CreateSaldoPagu::route('/create'),
            'edit' => Pages\EditSaldoPagu::route('/{record}/edit'),
        ];
    }
}
