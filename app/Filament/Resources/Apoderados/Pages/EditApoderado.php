<?php

namespace App\Filament\Resources\Apoderados\Pages;

use App\Filament\Resources\Apoderados\ApoderadoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApoderado extends EditRecord
{
    protected static string $resource = ApoderadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
