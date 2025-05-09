<?php

namespace App\Filament\Admin\Resources\RatingResource\Pages;

use App\Filament\Admin\Resources\RatingResource;
use App\Filament\Admin\Resources\RatingResource\Widgets\RatingStatistics;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRatings extends ListRecords
{
    protected static string $resource = RatingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RatingStatistics::class,
        ];
    }
}
