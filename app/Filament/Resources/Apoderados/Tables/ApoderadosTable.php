<?php

namespace App\Filament\Resources\Apoderados\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class ApoderadosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipo_documento')
                    ->searchable(),
                TextColumn::make('nro_documento')
                    ->searchable(),
                TextColumn::make('nombres')
                    ->searchable(),
                TextColumn::make('apellido_paterno')
                    ->searchable(),
                TextColumn::make('apellido_materno')
                    ->searchable(),
                TextColumn::make('telefono')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('estudiantes.nombres') // 'students' es la relación, 'name' es la columna en la tabla Student
                    ->label('Estudiantes a Cargo')
                    ->listWithLineBreaks() // Muestra cada estudiante en una nueva línea
                    ->limitList(3) // Muestra un máximo de 3 nombres
                    ->expandableLimitedList() // Agrega un botón "y X más" si hay más de 3
                    ->default('Sin estudiantes asignados')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        // Habilitar búsqueda en nombres de estudiantes
                        return $query->whereHas('estudiantes', fn(Builder $q) => $q->where('nombres', 'like', "%{$search}%"));
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
