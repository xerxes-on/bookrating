<?php

namespace App\Filament\Admin\Resources\QuoteResource\Pages;

use App\Filament\Admin\Resources\QuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuotes extends ListRecords
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
