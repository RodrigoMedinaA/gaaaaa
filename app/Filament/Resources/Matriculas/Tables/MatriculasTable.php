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
use Filament\Tables\Columns\IconColumn;

use Filament\Actions\Action;


class MatriculasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // IMPORTANTe en Filament v4: usa modifyQueryUsing para evitar N+1
            ->modifyQueryUsing(fn ($query) => $query->with(['estudiante', 'seccion']))

            ->columns([
                TextColumn::make('codigo')
                    ->searchable(),

                TextColumn::make('estudiante.nombres')
                    ->sortable(),

                // OPCIÓN B: Ícono justo al costado del alumno (déjalo si quieres esta UI)
                IconColumn::make('descargar_pdf')
                    ->label('') // sin label = compacto
                    ->icon('heroicon-o-arrow-down-tray')
                    ->tooltip('Descargar PDF')
                    ->url(fn ($record) => route('matriculas.pdf', $record))
                    ->openUrlInNewTab(),

                TextColumn::make('seccion.nombre')
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
                    ->relationship('estudiante', 'nombres')
                    ->searchable()
                    ->preload()
                    ->label('Estudiante'),

                SelectFilter::make('seccion')
                    ->relationship('seccion', 'nombre')
                    ->searchable()
                    ->preload()
                    ->label('Sección'),

                SelectFilter::make('estado')->options([
                    'activa'    => 'Activa',
                    'inactiva'  => 'Inactiva / Trunca',
                    'culminada' => 'Culminada',
                ]),
            ])

            ->recordActions([
                EditAction::make(),

                // OPCIÓN A: botón "PDF" en la columna de acciones
                Action::make('pdf')
    ->label('PDF')
    ->icon('heroicon-o-arrow-down-tray')
    ->tooltip('Ver/descargar PDF de matrícula')
    ->url(fn ($record) => route('matriculas.pdf', $record)) // sin ?download
    ->openUrlInNewTab(),
    

                DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
