<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\QuoteResource\RelationManagers\QuotesRelationManager;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('category_name')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('category_name'),
                Tables\Columns\TextColumn::make('books_count')
                    ->counts('books')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quotes_count')
                    ->counts('quotes')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            \App\Filament\Admin\Resources\CategoryResource\RelationManagers\BooksRelationManager::class,
            QuotesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\CategoryResource\Pages\ListCategories::route('/'),
            'create' => \App\Filament\Admin\Resources\CategoryResource\Pages\CreateCategory::route('/create'),
            'view' => \App\Filament\Admin\Resources\CategoryResource\Pages\ViewCategory::route('/{record}'),
            'edit' => \App\Filament\Admin\Resources\CategoryResource\Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
