<?php

namespace App\Filament\Resources\SaldoPaguResource\Pages;

use App\Filament\Resources\SaldoPaguResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSaldoPagu extends CreateRecord
{
    protected static string $resource = SaldoPaguResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
