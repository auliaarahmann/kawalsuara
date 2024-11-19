<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Data Pengguna';
                             
    protected static ?string $modelLabel = 'Data Pengguna';
    
    protected static ?string $slug = 'data-pengguna';    
    
    protected static ?string $navigationGroup = 'Pengaturan';
    
    protected static ?string $navigationBadgeTooltip = 'Jumlah Pengguna';    
    
    public static function getNavigationBadge(): ?string
    {
        return User::count();
    }    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->autocapitalize()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->unique(ignoreRecord:true)
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('role')
                ->native(false)
                ->default('user')
                ->options(function () {
                    /**
                     * Batasi role yang bisa dipilih berdasarkan level user
                     * admin hanya bisa memilih role admin dan user
                     */
                    $user = Auth::user();            
                    if ($user->role === 'admin') {
                        return [
                            'admin' => 'Admin',
                            'user' => 'User',
                        ];
                    }            
                        return [
                            'super_admin' => 'Super Admin',
                            'admin' => 'Admin',
                            'user' => 'User',
                        ];
                }),
                                
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->revealable()
                    ->maxLength(255)
                    ->visibleOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Profil'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
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

    /**
     * Jika role 'admin' yang login, data tampil hanya role 'admin' dan 'user'
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        $user = $user = Auth::user();
        
        if ($user->role === 'admin') {
            $query->whereIn('role', ['admin', 'user']);
        }

        return $query;
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

}
