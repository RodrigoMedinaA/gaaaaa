<?php

namespace App\Filament\Resources\Seccions\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use App\Filament\Resources\Seccions\SeccionResource; // <-- Importante para la URL
use Illuminate\Database\Eloquent\Model;

use Filament\Tables\Columns\TagsColumn;

class SeccionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('modulo.nombre')
                    ->searchable(),
                TextColumn::make('codigo'),
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('docente.nombres')
                    ->numeric(),
                // TextColumn::make('fecha_inicio')
                //     ->date()
                //     ->sortable(),
                // TextColumn::make('fecha_fin')
                //     ->date()
                //     ->sortable(),
                TagsColumn::make('dias_estudio')
                    ->label('Días de Estudio'),
                // TextColumn::make('hora_inicio')
                //     ->time()
                //     ->time('h:i A')
                //     ->sortable(),
                // TextColumn::make('hora_fin')
                //     ->time()
                //     ->time('h:i A')
                //     ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('modulo.nombre')
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make(),
                Action::make('ver-matriculados')
                    ->label('Ver alumnos')
                    ->color('info')
                    ->icon('heroicon-o-users')
                    ->url(fn (Model $record): string =>
                        // Genera la URL a la página 'ver-matriculados' (que crearemos en el Paso 2)
                        // y le pasa el ID de la sección actual ($record).
                        SeccionResource::getUrl('ver-matriculados', ['record' => $record])
                    ),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
