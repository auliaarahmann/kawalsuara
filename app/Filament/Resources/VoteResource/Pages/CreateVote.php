<?php

namespace App\Filament\Resources\VoteResource\Pages;

use App\Filament\CreateRecordAndRedirectToIndex;
use App\Filament\Resources\VoteResource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class CreateVote extends CreateRecordAndRedirectToIndex
{
    protected static string $resource = VoteResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data berhasil dikirim.';
    } 

    public function getTitle(): string|Htmlable
    {
        return 'Kirim Data Perolehan Suara';
    }

    protected function onValidationError(ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }    

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Kirim Data')
                ->icon('heroicon-o-paper-airplane'),
                ];
    }   
    
    protected function getRedirectUrl(): string
        {
            return $this->getUrl(['/']);
        }        

}
