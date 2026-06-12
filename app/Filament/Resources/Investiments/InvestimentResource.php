<?php

namespace App\Filament\Resources\Investiments;

use App\Filament\Resources\Investiments\Pages\ListInvestiments;
use App\Filament\Resources\Investiments\Schemas\InvestimentForm;
use App\Filament\Resources\Investiments\Tables\InvestimentsTable;
use App\Models\Investiment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class InvestimentResource extends Resource
{
    protected static ?string $model = Investiment::class;

    protected static string|UnitEnum|null $navigationGroup = 'Finanças';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-chart-up';

    //protected static ?string $recordTitleAttribute = 'Investiment';

    public static function getModelLabel(): string
    {
        return 'Investimento';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Investimentos';
    }

    public static function form(Schema $schema): Schema
    {
        return InvestimentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvestimentsTable::configure($table);
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
            'index' => ListInvestiments::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('ID_USR', auth()->id());
    }
}
