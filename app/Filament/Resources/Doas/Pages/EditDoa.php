<?php

namespace App\Filament\Resources\Doas\Pages;

use App\Filament\Resources\Doas\DoaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDoa extends EditRecord
{
    protected static string $resource = DoaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
