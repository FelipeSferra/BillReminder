<?php

namespace App\Filament\Resources\Funds;

use App\Filament\Resources\Funds\Pages\ListFunds;
use App\Filament\Resources\Funds\Schemas\FundForm;
use App\Filament\Resources\Funds\Tables\FundsTable;
use App\Models\Fund;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class FundResource extends Resource
{
    protected static ?string $model = Fund::class;

    protected static string|UnitEnum|null $navigationGroup = 'Finanças';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-piggy-bank';

    //protected static ?string $recordTitleAttribute = 'Fund';

    public static function getModelLabel(): string
    {
        return 'Reserva';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Reservas';
    }

    public static function form(Schema $schema): Schema
    {
        return FundForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FundsTable::configure($table);
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
            'index' => ListFunds::route('/'),
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
