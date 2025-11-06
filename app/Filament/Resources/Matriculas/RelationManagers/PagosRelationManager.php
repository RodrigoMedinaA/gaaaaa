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

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

use Filament\Resources\RelationManagers\RelationManager;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Carbon\Carbon;

use Filament\Schemas\Schema;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

use App\Enums\EstadoPago;

use Illuminate\Database\Eloquent\Model;

class PagosRelationManager extends RelationManager
{
    protected static string $relationship = 'pagos';
    protected static ?string $title = 'Historial de Pagos Mensuales';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\DatePicker::make('fecha_vencimiento')
                    ->label('Fecha de Vencimiento')
                    // ->disabled()
                    ->required(),
                
                Forms\Components\Select::make('estado')
                    ->options(EstadoPago::class)
                    ->required()
                    ->default(EstadoPago::PENDIENTE)
                    ->disabled()
                    ->live(),
                
                // Campos para cuando se realiza el pago
                Forms\Components\DatePicker::make('fecha_pago')
                    ->label('Fecha de Pago (si aplica)')
                    ->nullable()
                    ->disabled(),
                
                Forms\Components\Select::make('metodo_pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'yape' => 'Yape',
                        'plin' => 'Plin',
                        'transferencia' => 'Transferencia',
                    ])
                    ->visible(fn (Get $get): bool => $get('evidencia') !== null)
                    ->nullable(),
                FileUpload::make('evidencia')
                    ->label('Adjuntar Evidencia de Pago')
                    ->image() // Si solo quieres imágenes, o quita para cualquier archivo
                    ->directory('pagos-evidencias') // Carpeta donde se guardarán los archivos en 'storage/app/public'
                    ->disk('public') // Usa el disco 'public' (requiere 'php artisan storage:link')
                    ->nullable()
                    ->columnSpanFull() // Ocupa todo el ancho
                    ->live() // ¡IMPORTANTE! Hace que el campo sea reactivo
                    // --- LÓGICA REACTIVA: SI SE SUBE EVIDENCIA ---
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                        if ($state) { // Si hay un archivo (string con la ruta)
                            $set('estado', EstadoPago::PAGADO); // Cambia el estado a 'Pagado'
                            $set('fecha_pago', Carbon::now()->format('Y-m-d')); // Establece la fecha actual
                        } else { // Si se quita el archivo
                            $set('estado', EstadoPago::PENDIENTE); // Vuelve al estado Pendiente
                            $set('fecha_pago', null); // Borra la fecha de pago
                        }
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('codigo')
            ->columns([
                Tables\Columns\ImageColumn::make('evidencia')
                    ->label('Evidencia')
                    ->square() // Hace que la imagen se vea cuadrada
                    ->extraImgAttributes(fn (Model $record): array => [
                        'alt' => $record->codigo,
                        'loading' => 'lazy',
                        'title' => 'Evidencia de pago',
                    ]),
                TextColumn::make('codigo')
                    ->label('Cód. Pago')
                    ->searchable(),

                TextColumn::make('fecha_vencimiento')
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
                    // ->default('N/A')
                    , // Si es nulo
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

                        $dni = $matricula->estudiante->nro_documento ?? 'SINDNI';
                        $partesMatricula = explode('-', $matricula->codigo);
                        $xxxx = $partesMatricula[0]; // Asume que el código de matrícula es 'XXXX-DNI'
                        $conteoPagos = $matricula->pagos()->count();
                        $numeroPago = str_pad($conteoPagos + 1, 2, '0', STR_PAD_LEFT);

                        // Asignamos el código al formulario
                        $data['codigo'] = "{$dni}{$xxxx}{$numeroPago}";
                    
                        return $data;
                    }),
                // AssociateAction::make(),
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
