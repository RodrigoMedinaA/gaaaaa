<?php

namespace App\Filament\Resources\Seccions\Pages;

use App\Filament\Resources\Seccions\SeccionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListSeccions extends ListRecords
{
    protected static string $resource = SeccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs() : array
    {
        return [
            null => Tab::make('All'),
            'Presencial' => Tab::make()->query(fn($query) => $query->where('modalidad', 'Presencial')),
            'Virtual' => Tab::make()->query(fn($query) => $query->where('modalidad', 'Virtual')),
            'Semipresencial' => Tab::make()->query(fn($query) => $query->where('modalidad', 'Semipresencial')),
        ];
    }
}
