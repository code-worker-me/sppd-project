<?php

namespace App\Filament\Resources\DataSppdResource\Pages;

use App\Filament\Resources\DataSppdResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateDataSppd extends CreateRecord
{
    protected static string $resource = DataSppdResource::class;

    protected ?string $heading = 'Halaman Menambah Data SPPD';

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
