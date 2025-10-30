<?php

namespace App\Filament\Resources\Seccions;

use App\Filament\Resources\Seccions\Pages\CreateSeccion;
use App\Filament\Resources\Seccions\Pages\EditSeccion;
use App\Filament\Resources\Seccions\Pages\ListSeccions;
use App\Filament\Resources\Seccions\Schemas\SeccionForm;
use App\Filament\Resources\Seccions\Tables\SeccionsTable;
use App\Models\Seccion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use UnitEnum;

class SeccionResource extends Resource
{
    #Nombre en la navegación
    protected static ?string $navigationLabel = 'Secciones';
    
    protected static ?string $model = Seccion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static string | UnitEnum | null $navigationGroup = 'Gestión Académica';

    public static function form(Schema $schema): Schema
    {
        return SeccionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SeccionsTable::configure($table);
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
            'index' => ListSeccions::route('/'),
            'create' => CreateSeccion::route('/create'),
            'edit' => EditSeccion::route('/{record}/edit'),
        ];
    }
}
