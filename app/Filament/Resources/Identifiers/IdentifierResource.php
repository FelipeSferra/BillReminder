<?php

namespace App\Filament\Resources\Identifiers;

use App\Filament\Resources\Identifiers\Pages\ListIdentifiers;
use App\Filament\Resources\Identifiers\Schemas\IdentifierForm;
use App\Filament\Resources\Identifiers\Tables\IdentifiersTable;
use App\Models\Identifier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Override;
use UnitEnum;

class IdentifierResource extends Resource
{
    protected static ?string $model = Identifier::class;

    protected static string|UnitEnum|null $navigationGroup = 'Sistema';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    //protected static ?string $recordTitleAttribute = 'DESCRICAO';

    public static function getModelLabel(): string
    {
        return 'Identificador';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Identificadores';
    }

    /* public static function getRecordTitle(?object $record): string
    {
        return $record?->DESCRICAO;
    }
 */

    public static function form(Schema $schema): Schema
    {
        return IdentifierForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IdentifiersTable::configure($table);
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
            'index' => ListIdentifiers::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    #[Override]
    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
