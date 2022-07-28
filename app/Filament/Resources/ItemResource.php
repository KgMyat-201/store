<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use App\Models\Item;
use Filament\Tables;
use App\Models\Category;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ItemResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemResource\RelationManagers;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    
                    Select::make('category_id')
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->searchable(),
                    TextInput::make('name')
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, $state) {
                                $set('slug', Str::slug($state));
                            }),
                    TextInput::make('slug'),
                    RichEditor::make('content'),
                    Toggle::make('is_published')
                            ->onIcon('heroicon-s-lightning-bolt')
                            ->offIcon('heroicon-s-user'),
                    
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category_id')
                            ->label('Category')
                            ->searchable(),
                TextColumn::make('title')
                            ->sortable()
                            ->searchable(),
                TextColumn::make('slug')
                            ->toggleable(isToggledHiddenByDefault : true),
                TextColumn::make('content')
                            ->toggleable(isToggledHiddenByDefault : true),
                BooleanColumn::make('is_published')
                            ->toggleable(isToggledHiddenByDefault : false),
                
            ])
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }    
}
