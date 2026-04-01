<?php

namespace App\Filament\Resources\DataPerjalananResource\Pages;

use App\Filament\Resources\DataPerjalananResource;
use App\Models\Pagu;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateDataPerjalanan extends CreateRecord
{
    protected static string $resource = DataPerjalananResource::class;

    protected ?string $heading = 'Halaman Menambah Data Perjalanan';

    protected function afterCreate(): void
{
    $perjalanan = $this->record;
    $sppd = $perjalanan->sppd;
    $totalBiaya = $perjalanan->jumlah_sppd;
    $pagu = Pagu::where('tahun_anggaran', date('Y'))->first();

    if ($pagu && $sppd) {
        if ($sppd->kategori === 'umum') {
            $pagu->saldo_umum -= $totalBiaya;
        } elseif ($sppd->kategori === 'pu') {
            $pagu->saldo_pu -= $totalBiaya;
        }

        $pagu->save();
    }
}

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
