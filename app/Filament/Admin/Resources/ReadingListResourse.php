<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ReadingListResource\Pages;
use App\Filament\Admin\Resources\ReadingListResource\RelationManagers;
use App\Models\ReadingList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReadingListResource extends Resource
{
    protected static ?string $model = ReadingList::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    protected static ?string $navigationGroup = 'User Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_public')
                    ->default(false),
                Forms\Components\Toggle::make('is_featured')
                    ->default(false)
                    ->helperText('Featured reading lists will be displayed on the homepage'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('books_count')
                    ->counts('books')
                    ->label('Books'),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
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
                Tables\Filters\Filter::make('is_public')
                    ->query(fn (Builder $query): Builder => $query->where('is_public', true))
                    ->label('Public Only'),
                Tables\Filters\Filter::make('is_featured')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true))
                    ->label('Featured Only'),
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->label('User'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('feature')
                        ->action(fn ($records) => $records->each->update(['is_featured' => true]))
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('unfeature')
                        ->action(fn ($records) => $records->each->update(['is_featured' => false]))
                        ->icon('heroicon-o-star')
                        ->color('gray')
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BooksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReadingLists::route('/'),
            'create' => Pages\CreateReadingList::route('/create'),
            'view' => Pages\ViewReadingList::route('/{record}'),
            'edit' => Pages\EditReadingList::route('/{record}/edit'),
        ];
    }
}
