<?php

namespace App\Filament\Resources\DataDiriResource\Pages;

use App\Filament\Resources\DataDiriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataDiris extends ListRecords
{
    protected static string $resource = DataDiriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data'),
        ];
    }
}
