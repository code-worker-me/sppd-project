<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SppdsRelationManager extends RelationManager
{
    protected static string $relationship = 'sppds';

    protected static ?string $title = 'SPPD';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('st')
                    ->label('Nomor ST')
                    ->maxLength(255)
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('st')
            ->columns([
                TextColumn::make('st')
                    ->label('Nomor ST'),
                TextColumn::make('kota')
                    ->label('Tujuan'),
                TextColumn::make('tg_berangkat')
                    ->label('Berangkat')
                    ->date('d/m/Y'),
                TextColumn::make('tg_pulang')
                    ->label('Pulang')
                    ->date('d/m/Y'),
                TextColumn::make('durasi')
                    ->label('Durasi')
                    ->suffix(' hari'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
