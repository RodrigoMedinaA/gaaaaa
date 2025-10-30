<?php

namespace App\Filament\Resources\Estudiantes;


use App\Filament\Resources\EstudianteResource\Pages;
use App\Filament\Resources\Estudiantes\Pages\CreateEstudiante;
use App\Filament\Resources\Estudiantes\Pages\EditEstudiante;
use App\Filament\Resources\Estudiantes\Pages\ListEstudiantes;
use App\Filament\Resources\Estudiantes\Schemas\EstudianteForm;
use App\Filament\Resources\Estudiantes\Tables\EstudiantesTable;

use App\Models\Estudiante;

use BackedEnum;

use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Form; // <-- 2. Importa la clase Form

use UnitEnum;

class EstudianteResource extends Resource
{
    protected static ?string $model = Estudiante::class;

    #                                                          Icono de estudiante
    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static string | UnitEnum | null $navigationGroup = 'Gestión Estudiantil';

    public static function form(Schema $schema): Schema
    {
        return EstudianteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstudiantesTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEstudiantes::route('/'),
            'create' => CreateEstudiante::route('/create'),
            'edit' => EditEstudiante::route('/{record}/edit'),
        ];
    }
}
