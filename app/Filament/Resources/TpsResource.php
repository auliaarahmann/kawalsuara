<?php

namespace App\Filament\Resources;

use App\Models\Tps;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Filament\Resources\TpsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TpsResource\RelationManagers;
use Filament\Forms\Set;

class TpsResource extends Resource
{
    protected static ?string $model = Tps::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Data TPS';  
    
    protected static ?string $modelLabel = 'Data TPS';

    protected static ?string $slug = 'data-tps';       

    protected static ?string $navigationGroup = 'Lokasi TPS';   
    
    protected static ?string $navigationBadgeTooltip = 'Jumlah TPS';

    
    public static function getNavigationBadge(): ?string
    {
        return Tps::count();
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
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(fn (Set $set) => $set('kelurahan_id', null)),
                    
                Select::make('kelurahan_id')
                    ->loadingMessage('Memuat data kelurahan...')
                    ->required()
                    ->label('Nama Kelurahan')
                    ->disabledOn('edit')
                    ->live()
                    ->options(fn (Get $get): Collection => Kelurahan::query()
                    ->where('kecamatan_id', $get('kecamatan_id'))
                    ->pluck('nama_kelurahan', 'id'))
                    ->reactive()
                    ->preload()
                    ->searchable(),                

                TextInput::make('nama_tps')
                    ->label('Nama TPS')
                    ->prefix('TPS')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->rules(['integer', 'min:0'])
                    ->helperText('Isian hanya berupa angka satu digit, contoh: 1 atau 2, dst.')
                    ->disabledOn('edit')
                    ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'unique' => 'Data untuk TPS ini sudah ada.',
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('nama_tps')
            ->prefix('TPS00')
            ->label('Nama TPS')
            ->searchable(),

            TextColumn::make('kecamatan.nama_kecamatan')
            ->label('Kecamatan')
            ->sortable()
            ->searchable(),

            TextColumn::make('kelurahan.nama_kelurahan')
            ->label('Kelurahan / Desa / Gampong')
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTps::route('/'),
        ];
    }
}
