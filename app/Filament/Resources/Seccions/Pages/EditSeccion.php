<?php

namespace App\Filament\Resources\Seccions\Pages;

use App\Filament\Resources\Seccions\SeccionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSeccion extends EditRecord
{
    protected static string $resource = SeccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
