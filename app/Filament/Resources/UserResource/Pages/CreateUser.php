<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\CreateRecordAndRedirectToIndex;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecordAndRedirectToIndex
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {        
        $data['created_by'] = auth::id(); // Tambahkan ID user yang sedang login ke kolom created_by
    
        return $data;
    }    
}
