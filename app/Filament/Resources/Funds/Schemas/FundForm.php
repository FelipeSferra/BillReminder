<?php

namespace App\Filament\Resources\Funds\Schemas;

use App\Models\Identifier;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FundForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ID_IDENTIF')
                    ->label('Tipo de Reserva')
                    ->options(
                        Identifier::query()
                            ->where('TIPO_IDENTIFICADOR', 2)
                            ->pluck('IDENTIF', 'id')
                    )
                    ->native(false)
                    ->disabledOn('edit')
                    ->required(),
                TextInput::make('DESCRICAO')
                    ->label('Descrição')
                    ->maxLength(100)
                    ->required(),
                TextInput::make('VALOR')
                    ->label('Valor')
                    ->prefix('R$')
                    ->minValue(1)
                    ->default(1)
                    ->required()
                    ->numeric(),
                TextInput::make('META')
                    ->label('Meta')
                    ->prefix('R$')
                    ->minValue(1)
                    ->default(1)
                    ->required()
                    ->numeric(),
            ]);
    }
}
