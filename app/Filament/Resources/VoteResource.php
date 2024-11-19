<?php

namespace App\Filament\Resources;

use App\Models\Tps;
use Filament\Forms;
use Filament\Tables;
use App\Models\Votes;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Actions\StaticAction;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use App\Filament\Imports\VoteImporter;
use Filament\Forms\Components\Columns;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VoteResource\Pages;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VoteResource\RelationManagers;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use GPBMetadata\Google\Api\Label;
use Illuminate\Database\Eloquent\Factories\Relationship;

use function PHPSTORM_META\type;

class VoteResource extends Resource
{
    protected static ?string $model = Votes::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Data Perolehan Suara';    

    protected static ?string $navigationLabel = 'Perolehan Suara';

    protected static ?string $modelLabel = 'Perolehan Suara';    

    protected static ?string $slug = 'data-perolehan-suara';       

    protected static ?string $navigationBadgeTooltip = 'Data belum diverifikasi';

    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status','unverified')->count();
    }    

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status','unverified')->count() > 0 ? 'warning' : 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Formulir Verifikasi data Perolehan Suara')
                ->schema([

                    Section::make('Lokasi TPS')
                    // ->description(('Note: Pastikan Kecamatan, Kelurahan/Desa/Gampong dan TPS sesuai dengan Foto Formulir C1 Plano'))
                    ->schema([

                        Select::make('kecamatan_id')
                        ->label('Kecamatan')
                        ->preload()
                        ->live()
                        ->relationship('kecamatan','nama_kecamatan')
                        ->required()
                        ->searchable()
                        ->disabledOn('edit')
                        ->afterStateUpdated(function (Set $set) { 
                                                $set('kelurahan_id', null);
                                                $set('tps_id', null);
                                            }),
                    
                    Select::make('kelurahan_id')
                        ->label('Kelurahan')
                        ->preload()
                        ->live()
                        ->options(fn (Get $get): Collection => Kelurahan::query()
                        ->where('kecamatan_id', $get('kecamatan_id'))
                        ->pluck('nama_kelurahan', 'id'))
                        ->required()
                        ->searchable()
                        ->disabledOn('edit')
                        ->afterStateUpdated(fn (Set $set) => $set('tps_id', null)),
    
                    Select::make('tps_id')
                        ->label('TPS')
                        ->preload()
                        ->live()
                        ->options(fn (Get $get): Collection => Tps::query()
                                ->where('kelurahan_id', $get('kelurahan_id'))
                                ->pluck('nama_tps', 'id')
                                ->mapWithKeys(fn ($nama, $id) => [$id => 'TPS00' . $nama]))
                        ->required()
                        ->searchable()
                        ->disabledOn('edit')
                        ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'unique' => 'Data untuk TPS ini sudah ada.',
                        ]),

                        FileUpload::make('foto_c1_plano')
                            ->label('Foto C1 Plano')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->directory('formulir-c1')
                            ->visibleOn('create'),                        

                    ])->columns(3),    
                        
                    Section::make('Perolehan Suara')
                    ->description('Note: Pastikan data perolehan suara sesuai dengan Foto C1 Plano')
                    ->schema([  
                        
                        
                        TextInput::make('paslon_1_vote')
                            ->label('Suara Paslon 1')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->rules(['integer', 'min:0']),

                        TextInput::make('paslon_2_vote')
                            ->label('Suara Paslon 2')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->rules(['integer', 'min:0']),

                        TextInput::make('paslon_3_vote')
                            ->required()
                            ->label('Suara Paslon 3')
                            ->numeric()
                            ->minValue(0)
                            ->rules(['integer', 'min:0']),
                                        
                    ])->columns(3)  

                ])->columns(3),

                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_c1_plano')
                ->square()
                ->label('C1 Plano')
                ->action(
                    Action::make('viewImage')
                        ->modalCancelAction(fn (StaticAction $action) => $action->label('Close'))                    
                        ->modalSubmitAction(false)
                        ->modalHeading('Detail Image')
                        ->modalWidth('2xl')
                        ->modalContent(fn ($record) => view('filament.modals.image-modal', [
                            'imageUrl' => asset('storage/' . $record->foto_c1_plano),
                        ]))
                ),          
                    
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'unverified' => 'warning',
                        'verified'   => 'success',
                    }),
                TextColumn::make('user.name')
                    ->Label('Verifikator')
                    ->placeholder('-'),    
                TextColumn::make('updated_at')
                    ->Label('Diverifikasi')
                    ->since()
                    ->dateTimeTooltip()
                    ->placeholder('-'),   
                TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Kecamatan'),
                TextColumn::make('kelurahan.nama_kelurahan')
                    ->label('Desa / Gampong'),
                TextColumn::make('tps.nama_tps')
                    ->label('TPS')
                    ->prefix('TPS00'),
                TextColumn::make('paslon_1_vote')
                    ->label('Paslon 1')
                    ->numeric(),
                TextColumn::make('paslon_2_vote')
                    ->label('Paslon 2')
                    ->numeric(),
                TextColumn::make('paslon_3_vote')
                    ->label('Paslon 3')
                    ->numeric(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVotes::route('/'),
            'create' => Pages\CreateVote::route('/create'),
            'edit' => Pages\EditVote::route('/{record}/edit'),
        ];
    }
}
