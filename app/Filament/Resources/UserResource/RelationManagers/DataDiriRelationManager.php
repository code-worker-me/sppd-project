<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DataDiriRelationManager extends RelationManager
{
    protected static string $relationship = 'dataDiri';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nip')
                    ->label('NIP')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->maxLength(255),

                TextInput::make('pangkat')
                    ->label('Pangkat')
                    ->maxLength(255)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip')
                    ->label('NIP'),
                TextColumn::make('unit_kerja')
                    ->label('Unit Kerja'),
                TextColumn::make('pangkat')
                    ->label('Pangkat')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
