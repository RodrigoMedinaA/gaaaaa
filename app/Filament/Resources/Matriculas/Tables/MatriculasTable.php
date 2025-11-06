<?php

namespace App\Filament\Resources\Matriculas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MatriculasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')
                    ->searchable(),
                TextColumn::make('estudiante.nombres')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('seccion.nombre')
                    ->numeric()
                    ->sortable(),
                BadgeColumn::make('estado'),
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
                SelectFilter::make('estudiante')
                    ->relationship('estudiante', 'nombres') // Busca en la relación 'estudiante'
                    ->searchable() // <-- Permite escribir para buscar
                    ->preload()
                    ->label('Estudiante'),

                SelectFilter::make('seccion')
                    ->relationship('seccion', 'nombre') // Busca en la relación 'seccion'
                    ->searchable() // <-- Permite escribir para buscar
                    ->preload()
                    ->label('Sección'),
                SelectFilter::make('estado')
                    ->options([
                        'activa' => 'Activa',
                        'inactiva' => 'Inactiva / Trunca',
                        'culminada' => 'Culminada',
                    ])
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
