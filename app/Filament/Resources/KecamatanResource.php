<?php

namespace App\Filament\Resources;

use App\Filament\Imports\KecamatanImporter;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Kecamatan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KecamatanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KecamatanResource\RelationManagers;

class KecamatanResource extends Resource
{
    protected static ?string $model = Kecamatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationLabel = 'Kecamatan';
                             
    protected static ?string $modelLabel = 'Data Kecamatan';
    
    protected static ?string $slug = 'data-kecamatan';    
    
    protected static ?string $navigationGroup = 'Lokasi TPS';    

    protected static ?string $navigationBadgeTooltip = 'Jumlah Kecamatan';

    
    public static function getNavigationBadge(): ?string
    {
        return Kecamatan::count();
    }    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kecamatan')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'unique' => 'Nama kecamatan sudah ada.'
                        ]), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kecamatan')
                    ->label('Nama Kecamatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(KecamatanImporter::class)
                    ->label('Impor')
                    ->icon('heroicon-o-document-arrow-down')
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKecamatans::route('/'),
        ];
    }
}
