<?php

namespace App\Filament\Admin\Resources\RatingResource\Pages;

use App\Filament\Admin\Resources\RatingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRating extends CreateRecord
{
    protected static string $resource = RatingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
