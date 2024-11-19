<?php

namespace App\Filament\Resources\VoteResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\StaticAction;
// use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\VoteResource;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\EditRecordAndRedirectToIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;
use App\Filament\Resources\VoteResource\Pages\getResource;
use Filament\Facades\Filament;

class EditVote extends EditRecordAndRedirectToIndex
{
    protected static string $resource = VoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
                /**
                 * Create a button to display image on modal
                 */
                Action::make('viewImage')
                    ->label('Lihat Foto C1 Plano')
                    ->icon('heroicon-o-photo')
                    ->modalCancelAction(fn (StaticAction $action) => $action->label('Close'))                    
                    ->modalSubmitAction(false)
                    ->modalHeading('Foto C1 Plano')
                    ->modalWidth('2xl')
                    ->modalContent(fn ($record) => view('filament.modals.image-modal', [
                        'imageUrl' => asset('storage/' . $record->foto_c1_plano), 
                    ]))
        ];
    }

     public function getTitle(): string|Htmlable
    {
        return 'Ubah Data Perlolehan Suara';
    }

    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ambil data record yang sedang diedit
        $record = $this->getRecord();
    
        // Cek apakah status sudah 'verified'
        if ($record && $record->status === 'verified') {
            // Tampilkan pesan kesalahan menggunakan notifikasi
            Notification::make()
                ->title('Error')
                ->body('Data sudah diverifikasi sebelumnya.')
                ->danger() 
                ->send();
                   
            // Lemparkan pengecualian agar form tidak bisa disimpan
            throw ValidationException::withMessages([
                'status' => ['Data sudah diverifikasi sebelumnya.'],
            ]);
        }
    
        // Jika status belum verified, lanjutkan
        if (empty($data['status']) || $data['status'] === 'unverified') {
            $data['status'] = 'verified';
        }
    
        // Set kolom verified_by dengan ID user yang sedang login
        $data['verified_by'] = auth::id();
            
        return $data;
    }
     
    
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data berhasil diperbarui.';
    }    

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Verifikasi dan simpan')
                ->icon('heroicon-o-check-badge'),
                
            $this->getCancelFormAction()
                ->icon('heroicon-o-x-circle'),
        ];
    }    
}
