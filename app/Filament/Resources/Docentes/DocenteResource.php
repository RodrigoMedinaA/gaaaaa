<?php

namespace App\Filament\Resources\Docentes;

use App\Filament\Resources\Docentes\Pages;
use App\Filament\Resources\Docentes\Pages\CreateDocente;
use App\Filament\Resources\Docentes\Pages\EditDocente;
use App\Filament\Resources\Docentes\Pages\ListDocentes;
use App\Filament\Resources\Docentes\Schemas\DocenteForm;
use App\Filament\Resources\Docentes\Tables\DocentesTable;
use App\Models\Docente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use UnitEnum;

class DocenteResource extends Resource
{
    #Nombre en la navegación
    protected static ?string $navigationLabel = 'Docentes';
    
    protected static ?string $model = Docente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static string | UnitEnum | null $navigationGroup = 'Gestión Académica';

    public static function form(Schema $schema): Schema
    {
        return DocenteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocentesTable::configure($table);
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
            'index' => ListDocentes::route('/'),
            'create' => CreateDocente::route('/create'),
            'edit' => EditDocente::route('/{record}/edit'),
        ];
    }
}
