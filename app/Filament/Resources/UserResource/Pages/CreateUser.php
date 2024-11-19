<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\CreateRecordAndRedirectToIndex;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecordAndRedirectToIndex
{
    protected static string $resource = UserResource::class;
}
