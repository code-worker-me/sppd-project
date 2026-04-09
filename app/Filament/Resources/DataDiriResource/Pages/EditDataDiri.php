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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Untuk menampilkan nama saat form edit dibuka
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['name'] = $this->record->user->name;
        return $data;
    }

    // Untuk menyimpan nama saat save
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->record->user->update(['name' => $data['name']]);
        unset($data['name']);
        return $data;
    }
}