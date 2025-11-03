<?php

namespace App\Filament\Resources\Matriculas\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
// use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

use Illuminate\Database\Eloquent\Model;

class PagosRelationManager extends RelationManager
{
    protected static string $relationship = 'pagos';
    protected static ?string $title = 'Historial de Pagos Mensuales';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo')
                    ->required()
                    ->disabled() // No editable
                    ->dehydrated()
                    ->maxLength(255),
            
                DatePicker::make('fecha_vencimiento_cuota')
                    ->label('Fecha de Vencimiento')
                    ->required(),
                
                TextInput::make('monto')
                    ->required()
                    ->numeric()
                    ->prefix('S/.'),

                Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagado' => 'Pagado',
                        'anulado' => 'Anulado',
                    ])
                    ->required()
                    ->default('pendiente'),
                
                // Campos para cuando se realiza el pago
                DatePicker::make('fecha_pago')
                    ->label('Fecha de Pago (si aplica)'),
                
                Select::make('metodo_pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'yape' => 'Yape',
                        'plin' => 'Plin',
                        'transferencia' => 'Transferencia',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('codigo')
            ->columns([
                TextColumn::make('codigo')
                    ->label('Cód. Pago')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Oculto por defecto

                TextColumn::make('fecha_vencimiento_cuota')
                    ->label('Vencimiento')
                    ->date()
                    ->sortable(),

                TextColumn::make('monto')
                    ->money('PEN') // Formato de moneda
                    ->sortable(),

                BadgeColumn::make('estado') // <-- Columna de Insignia
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'pagado',
                        'danger' => 'anulado',
                    ]),
                
                TextColumn::make('fecha_pago')
                    ->label('Fecha de Pago')
                    ->date()
                    ->default('N/A'), // Si es nulo
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Registrar Próximo Pago')
                    ->mutateFormDataUsing(function (array $data): array {
                            
                        $matricula = $this->ownerRecord;
                            
                        // 1. Calcular el Monto
                        $data['monto'] = $matricula->seccion->modulo->costo ?? 0;

                        // 2. Calcular la Fecha de Vencimiento
                        $ultimoPago = $matricula->pagos()->latest('fecha_vencimiento_cuota')->first();

                        if ($ultimoPago) {
                            $nuevaFecha = $ultimoPago->fecha_vencimiento_cuota->addMonth();
                        } else {
                            $nuevaFecha = $matricula->seccion->fecha_inicio ?? now();
                        }
                            
                        $data['fecha_vencimiento_cuota'] = $nuevaFecha;

                        $dni = $matricula->estudiante->nro_documento ?? 'SINDNI';
                        $partesMatricula = explode('-', $matricula->codigo);
                        $xxxx = $partesMatricula[0]; // Asume que el código de matrícula es 'XXXX-DNI'
                        $conteoPagos = $matricula->pagos()->count();
                        $numeroPago = str_pad($conteoPagos + 1, 2, '0', STR_PAD_LEFT);

                        // Asignamos el código al formulario
                        $data['codigo'] = "{$dni}{$xxxx}{$numeroPago}";
                    
                        return $data;
                    }),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
