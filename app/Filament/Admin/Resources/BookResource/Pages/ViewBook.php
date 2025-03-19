<?php

namespace App\Filament\Admin\Resources\BookResource\Pages;

use App\Filament\Admin\Resources\BookResource;
use Filament\Resources\Pages\ViewRecord;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            BookResource\Widgets\RatingsOverview::class
        ];
    }
}
