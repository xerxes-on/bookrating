<?php

namespace App\Filament\Admin\Resources\ReviewResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_approved')
                    ->default(false)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('content')
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_approved')
                    ->query(fn (Builder $query): Builder => $query->where('is_approved', true)),
                Tables\Filters\Filter::make('not_approved')
                    ->query(fn (Builder $query): Builder => $query->where('is_approved', false)),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('approve')
                    ->action(fn ($record) => $record->update(['is_approved' => true]))
                    ->visible(fn ($record) => !$record->is_approved)
                    ->icon('heroicon-o-check-circle')
                    ->color('success'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true]))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
