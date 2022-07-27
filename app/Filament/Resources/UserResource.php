<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Livewire\Component;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                    TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                    
                    TextInput::make('password')
                                ->password()
                                ->required()
                                ->maxLength(255),
                    MultiSelect::make('roles')->relationship('roles', 'name')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                            ->sortable()
                            ->searchable(),
                TextColumn::make('email'),
                TextColumn::make('email_varified_at')
                            ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('created_at')
                            ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('updated_at')
                            ->toggleable(isToggledHiddenByDefault : true),
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
