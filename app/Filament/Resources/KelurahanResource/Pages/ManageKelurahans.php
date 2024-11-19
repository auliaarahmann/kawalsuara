<?php

namespace App\Filament\Resources\KelurahanResource\Pages;

use App\Filament\Resources\KelurahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKelurahans extends ManageRecords
{
    protected static string $resource = KelurahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                    ->label('Buat Kelurahan')
                    ->icon('heroicon-o-folder-plus'),
        ];
    }
}
