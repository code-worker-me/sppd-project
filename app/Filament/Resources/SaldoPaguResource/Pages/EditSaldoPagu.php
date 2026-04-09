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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
{
    $record = $this->getRecord();

    // Hitung total transaksi yang sudah ada
    $totalTransaksiUmum = \App\Models\DataPerjalanan::whereHas('sppd', fn($q) =>
        $q->where('kategori', 'umum')
    )->sum('jumlah_sppd');

    $totalTransaksiPu = \App\Models\DataPerjalanan::whereHas('sppd', fn($q) =>
        $q->where('kategori', 'pu')
    )->sum('jumlah_sppd');

    // Gunakan pagu awal baru jika diisi, jika tidak pakai yang lama
    $data['pagu_awal_umum'] = $data['pagu_awal_umum'] ?? $record->pagu_awal_umum;
    $data['pagu_awal_pu'] = $data['pagu_awal_pu'] ?? $record->pagu_awal_pu;

    // Hitung ulang saldo otomatis
    $data['saldo_umum'] = $data['pagu_awal_umum'] - $totalTransaksiUmum;
    $data['saldo_pu'] = $data['pagu_awal_pu'] - $totalTransaksiPu;

    return $data;
}
}
