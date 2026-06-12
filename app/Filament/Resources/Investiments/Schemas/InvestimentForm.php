<?php

namespace App\Filament\Resources\Investiments\Schemas;

use App\Models\Identifier;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvestimentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ID_IDENTIF')
                    ->label('Tipo de Investimento')
                    ->options(
                        Identifier::query()
                            ->where('TIPO_IDENTIFICADOR', 3)
                            ->pluck('IDENTIF', 'id')
                    )
                    ->native(false)
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
            ]);
    }
}
