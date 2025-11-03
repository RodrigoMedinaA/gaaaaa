<?php

namespace App\Filament\Resources\Matriculas;

use App\Filament\Resources\Matriculas\Pages\CreateMatricula;
use App\Filament\Resources\Matriculas\Pages\EditMatricula;
use App\Filament\Resources\Matriculas\Pages\ListMatriculas;
use App\Filament\Resources\Matriculas\Schemas\MatriculaForm;
use App\Filament\Resources\Matriculas\Tables\MatriculasTable;
use App\Models\Matricula;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MatriculaResource extends Resource
{
    protected static ?string $model = Matricula::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return MatriculaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MatriculasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PagosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMatriculas::route('/'),
            'create' => CreateMatricula::route('/create'),
            'edit' => EditMatricula::route('/{record}/edit'),
        ];
    }
}
