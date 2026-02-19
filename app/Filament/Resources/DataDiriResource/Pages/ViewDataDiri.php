<?php

namespace App\Filament\Resources\DataDiriResource\Pages;

use App\Filament\Resources\DataDiriResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDataDiri extends ViewRecord
{
    protected static string $resource = DataDiriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
