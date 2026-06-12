<?php

namespace App\Filament\Resources\Identifiers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class IdentifierForm
{
    private static $identifiers_type = [
        'Receita',
        'Gasto',
        'Reserva',
        'Investimento'
    ];


    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('IDENTIF')
                    ->label('Identificador')
                    ->required()
                    ->columnSpanFull(),
                Select::make('TIPO_IDENTIFICADOR')
                    ->label('Tipo de Identificador')
                    ->options(self::$identifiers_type)
                    ->native(false)
                    ->required()
                    ->columnSpanFull(),
                ColorPicker::make('ID_HEX')
                    ->label('Cor')
                    ->required()
                    ->regex('/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})\b$/')
                    ->default('#FFFFFF')
                    ->columnSpanFull(),
            ]);
    }
}
