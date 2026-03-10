<?php

namespace App\Filament\Resources\SaldoPaguResource\Pages;

use App\Filament\Resources\SaldoPaguResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSaldoPagu extends EditRecord
{
    protected static string $resource = SaldoPaguResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
