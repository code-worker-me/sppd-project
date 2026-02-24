<?php

namespace App\Filament\Widgets;

use App\Models\DataPerjalanan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PerjalananOverview extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                DataPerjalanan::query()
                    ->with(['sppd', 'sppd.user'])
                    ->whereHas('sppd')
            )
            ->columns([
                // ...
            ]);
    }
}
