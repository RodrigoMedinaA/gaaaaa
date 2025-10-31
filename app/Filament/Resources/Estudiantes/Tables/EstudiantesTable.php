<?php

namespace App\Filament\Resources\Estudiantes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class EstudiantesTable
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
                TextColumn::make('direccion')
                    ->default('por completar...')
                    ->searchable()
                    ->color(fn (?string $state): string => match (true) {
                        blank($state) => 'warning',
                        default => 'red',
                    }),
                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->default('por completar')
                    ->searchable(),
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
                SelectFilter::make('estado_edad')
                    ->label('Edad')
                    ->options([
                        'mayor' => 'Mayor de edad',
                        'menor' => 'Menor de edad',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        // Esta es la fecha de corte (hoy hace 18 años)
                        $cutoffDate = Carbon::now()->subYears(18)->toDateString();

                        if ($data['value'] === 'mayor') {
                            // Si es 'mayor', busca fechas ANTERIORES O IGUALES a la fecha de corte
                            return $query->whereDate('fecha_nacimiento', '<=', $cutoffDate);
                        } else {
                            // Si es 'menor', busca fechas POSTERIORES a la fecha de corte
                            return $query->whereDate('fecha_nacimiento', '>', $cutoffDate);
                        }
                    }),
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
