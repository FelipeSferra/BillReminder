<?php

namespace App\Filament\Schemas\Investiments;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;

class InvestimentActionForm
{
    public static function components(): array
    {
        return [
            TextInput::make('VALOR')
                ->numeric()
                ->label('Valor')
                ->prefix('R$')
                ->minValue(1)
                ->default(1)
                ->required(),
            Hidden::make('TIPO_OPERACAO')
        ];
    }
}
