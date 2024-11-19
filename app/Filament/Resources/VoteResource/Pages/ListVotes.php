<?php

namespace App\Filament\Resources\VoteResource\Pages;

use App\Filament\Resources\VoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListVotes extends ListRecords
{
    protected static string $resource = VoteResource::class;

    protected static ?string $recordTitle = 'Data Perolehan Suara';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Data Baru')
                ->icon('heroicon-o-folder-plus'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Data Perolehan Suara';
    }
}
