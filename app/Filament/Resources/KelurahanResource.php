<?php

namespace App\Filament\Resources;

use App\Filament\Imports\KelurahanImporter;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Kelurahan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KelurahanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KelurahanResource\RelationManagers;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class KelurahanResource extends Resource
{
    protected static ?string $model = Kelurahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Kelurahan'; 

    protected static ?string $modelLabel = 'Data Kelurahan';    
       
    protected static ?string $slug = 'data-kelurahan';   

    protected static ?string $navigationGroup = 'Lokasi TPS';    

    protected static ?string $navigationBadgeTooltip = 'Jumlah Kelurahan';

    
    public static function getNavigationBadge(): ?string
    {
        return Kelurahan::count();
    }    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kecamatan_id')
                    ->loadingMessage('Memuat data kecamatan...')
                    ->required()
                    ->label('Nama Kecamatan')
                    ->relationship('kecamatan', 'nama_kecamatan')
                    ->disabledOn('edit')
                    ->live()
                    ->searchable()
                    ->preload(),
                TextInput::make('nama_kelurahan')
                    ->label('Nama Kelurahan')
                    ->required()
                    ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'unique' => 'Nama kelurahan sudah ada.',
                        ]),                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelurahan')
                ->label('Nama Kelurahan')
                ->numeric()
                ->sortable()
                ->searchable(),

                TextColumn::make('kecamatan.nama_kecamatan')
                ->label('Nama Kecamatan')
                ->numeric()
                ->sortable()
                ->searchable(),
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
                    ->label('Import Data Desa / Gampong')
                    ->importer(KelurahanImporter::class)
                    ->label('Impor')
                    ->icon('heroicon-o-document-arrow-down')
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKelurahans::route('/'),
        ];
    }
}
