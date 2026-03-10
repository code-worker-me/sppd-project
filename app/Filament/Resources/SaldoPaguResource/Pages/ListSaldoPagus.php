<?php

namespace App\Filament\Resources\SaldoPaguResource\Pages;

use App\Filament\Resources\SaldoPaguResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSaldoPagus extends ListRecords
{
    protected static string $resource = SaldoPaguResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah'),
        ];
    }
}
