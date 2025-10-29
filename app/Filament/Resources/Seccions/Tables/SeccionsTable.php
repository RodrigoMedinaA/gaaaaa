<?php

namespace App\Filament\Resources\Seccions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeccionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('modulo.nombre')
                    ->searchable(),
                TextColumn::make('nombre')
                    ->searchable(),
                // TextColumn::make('docente_id')
                TextColumn::make('docente.nombres')
                    ->numeric(),
                TextColumn::make('modalidad')
                    ->searchable(),
                TextColumn::make('fecha_inicio')
                    ->date()
                    ->sortable(),
                TextColumn::make('fecha_fin')
                    ->date()
                    ->sortable(),
                TextColumn::make('turno')
                    ->searchable(),
                TextColumn::make('hora_inicio')
                    ->time()
                    ->sortable(),
                TextColumn::make('hora_fin')
                    ->time()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
