<?php

namespace App\Filament\Resources\DataDiriResource\Pages;

use App\Filament\Resources\DataDiriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataDiri extends EditRecord
{
    protected static string $resource = DataDiriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
