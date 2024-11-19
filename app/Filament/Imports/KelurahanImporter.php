<?php

namespace App\Filament\Imports;

use App\Models\Kelurahan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class KelurahanImporter extends Importer
{
    protected static ?string $model = Kelurahan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama_kelurahan')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('kecamatan_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?Kelurahan
    {
        // return Kelurahan::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Kelurahan();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your kelurahan import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
