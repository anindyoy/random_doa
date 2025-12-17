<?php

namespace App\Filament\Resources\Doas\Pages;

use App\Filament\Resources\Doas\DoaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDoas extends ListRecords
{
    protected static string $resource = DoaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
