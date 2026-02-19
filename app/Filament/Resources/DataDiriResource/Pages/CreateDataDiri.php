<?php

namespace App\Filament\Resources\DataDiriResource\Pages;

use App\Filament\Resources\DataDiriResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateDataDiri extends CreateRecord
{
    protected static string $resource = DataDiriResource::class;

    protected ?string $heading = 'Halaman Menambah Pegawai';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Simpan & Tambah Lagi');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }
}
