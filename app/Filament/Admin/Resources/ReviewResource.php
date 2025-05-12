<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ReviewResource\Pages;
use App\Filament\Admin\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('book_id')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->required(),
                Forms\Components\RichEditor::make('content')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\Select::make('rating')
                    ->options([
                        1 => '⭐',
                        2 => '⭐⭐',
                        3 => '⭐⭐⭐',
                        4 => '⭐⭐⭐⭐',
                        5 => '⭐⭐⭐⭐⭐',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->nullable(),
                Forms\Components\Toggle::make('is_approved')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('book.title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => str_repeat('⭐', $state)),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        1 => '1 Star',
                        2 => '2 Stars',
                        3 => '3 Stars',
                        4 => '4 Stars',
                        5 => '5 Stars',
                    ]),
                Tables\Filters\Filter::make('is_approved')
                    ->query(fn (Builder $query): Builder => $query->where('is_approved', true)),
                Tables\Filters\Filter::make('not_approved')
                    ->query(fn (Builder $query): Builder => $query->where('is_approved', false)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->action(fn (Collection $records) => $records->each->update(['is_approved' => true]))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'view' => Pages\ViewReview::route('/{record}'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
