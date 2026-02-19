<?php

namespace App\Filament\Resources\DataPerjalananResource\Pages;

use App\Filament\Resources\DataPerjalananResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditDataPerjalanan extends EditRecord
{
    protected static string $resource = DataPerjalananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Lihat'),
            Actions\DeleteAction::make()
                ->label('Hapus'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Simpan');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ambil data lama dari database
        $oldData = $this->record;

        // Jika tiket diubah/dihapus, hapus file lama
        if (isset($data['tiket']) && $data['tiket'] !== $oldData->tiket) {
            if ($oldData->tiket && Storage::disk('public')->exists($oldData->tiket)) {
                Storage::disk('public')->delete($oldData->tiket);
            }
        }

        // Jika hotel diubah/dihapus, hapus file lama
        if (isset($data['hotel']) && $data['hotel'] !== $oldData->hotel) {
            if ($oldData->hotel && Storage::disk('public')->exists($oldData->hotel)) {
                Storage::disk('public')->delete($oldData->hotel);
            }
        }

        // Jika field kosong (null), set ke null di database
        if (empty($data['tiket'])) {
            $data['tiket'] = null;
        }
        if (empty($data['hotel'])) {
            $data['hotel'] = null;
        }

        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data perjalanan berhasil diupdate';
    }
}
