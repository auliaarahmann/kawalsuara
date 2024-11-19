<?php

namespace App\Filament\Resources\TpsResource\Pages;

use App\Filament\Resources\TpsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTps extends ManageRecords
{
    protected static string $resource = TpsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Data TPS')
                ->icon('heroicon-o-folder-plus'),
        ];
    }
}
