<?php

namespace App\Filament\Resources\DataSppdResource\Pages;

use App\Filament\Resources\DataSppdResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewDataSppd extends ViewRecord
{
    protected static string $resource = DataSppdResource::class;

    protected function getActions(): array
    {
        return [
            EditAction::make()
                ->label('Edit Data'),
            DeleteAction::make()
                ->label('Hapus Data')
                ->modalHeading('Hapus Data SPPD')
                ->modalDescription('Apakah Anda yakin ingin menghapus data sppd ini? Seluruh data yang terkait akan ikut terhapus dan tidak dapat dikembalikan.')
                ->modalSubmitActionLabel('Ya, Hapus Sekarang')
                ->modalCancelActionLabel('Batal'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Detail SPPD');
    }
}
