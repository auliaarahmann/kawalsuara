<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\EditRecordAndRedirectToIndex;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecordAndRedirectToIndex
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
        ];
    }
    
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data berhasil diperbarui.';
    }  
    
}
