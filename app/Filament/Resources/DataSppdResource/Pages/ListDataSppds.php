<?php

namespace App\Filament\Resources\DataSppdResource\Pages;

use App\Filament\Resources\DataSppdResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataSppds extends ListRecords
{
    protected static string $resource = DataSppdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label("Tambah Data"),
        ];
    }
}
