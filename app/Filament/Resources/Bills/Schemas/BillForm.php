<?php

namespace App\Filament\Resources\Bills\Schemas;

use App\Models\Identifier;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('STATUS')
                    ->default('Pagar'),
                Select::make('ID_IDENTIF')
                    ->label('Tipo de Conta')
                    ->options(
                        Identifier::query()
                            ->where('TIPO_IDENTIFICADOR', 1)
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
                    ->numeric()
                    ->required(),
                DatePicker::make('VENCIMENTO')
                    ->label('Vencimento')
                    ->required(),
                Select::make('RECORRENTE')
                    ->label('Recorrente')
                    ->options([
                        1 => 'Sim',
                        0 => 'Não',
                    ])
                    ->native(false)
                    ->required()
                    ->default(1)
                    ->live()
                    ->columnSpan(fn($get) => $get('RECORRENTE') === 0 ? 1 : 'full'),
                TextInput::make('PARCELAS')
                    ->label('Parcelas')
                    ->numeric()
                    ->required()
                    ->hidden(fn($get): bool => $get('RECORRENTE') !== 0)
                    ->columnSpan(1),
            ]);
    }
}
