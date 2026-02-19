<?php

namespace App\Filament\Resources\DataPerjalananResource\Pages;

use App\Filament\Resources\DataPerjalananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataPerjalanans extends ListRecords
{
    protected static string $resource = DataPerjalananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label("Tambah Data"),
        ];
    }
}
