<?php

namespace App\Filament\Resources\Incomes;

use App\Filament\Resources\Incomes\Pages\ListIncomes;
use App\Filament\Resources\Incomes\Schemas\IncomeForm;
use App\Filament\Resources\Incomes\Tables\IncomesTable;
use App\Models\Income;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static string|UnitEnum|null $navigationGroup = 'Finanças';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-money-add-02';

    //protected static ?string $recordTitleAttribute = 'Income';
    public static function getModelLabel(): string
    {
        return 'Receita';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Receitas';
    }

    public static function form(Schema $schema): Schema
    {
        return IncomeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IncomesTable::configure($table);
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
            'index' => ListIncomes::route('/'),
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
