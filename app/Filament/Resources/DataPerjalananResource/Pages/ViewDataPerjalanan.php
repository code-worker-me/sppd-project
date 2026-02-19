<?php

namespace App\Filament\Resources\DataPerjalananResource\Pages;

use App\Filament\Resources\DataPerjalananResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewDataPerjalanan extends ViewRecord
{
    protected static string $resource = DataPerjalananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label("Edit Data"),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Detail Perjalanan');
    }
}
