<?php

namespace App\Filament\Resources\Incomes\Schemas;

use App\Models\Identifier;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class IncomeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ID_IDENTIF')
                    ->label('Tipo de Recebimento')
                    ->options(
                        Identifier::query()
                            ->where('TIPO_IDENTIFICADOR', 0)
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
                DatePicker::make('RECEBIDO_EM')
                    ->label('Data Recebida')
                    ->default(now())
                    ->required(),
                Select::make('VALOR_FIXO')
                    ->label('Valor Fixo')
                    ->options([
                        1 => 'Sim',
                        0 => 'Não',
                    ])
                    ->native(false)
                    ->required()
                    ->default(0)
                    ->live()
                    ->columnSpanFull(),
                Radio::make('RECRIAR_RECEITA')
                    ->label('Deseja recriar a receita?')
                    ->options([
                        1 => 'Sim',
                        0 => 'Não',
                    ])
                    ->default(0)
                    ->live()
                    ->hiddenOn('edit')
                    ->visible(fn(Get $get): bool => (int) $get('VALOR_FIXO') === 1),
                TextInput::make('RECRIAR_QUANTIDADE')
                    ->label('Quantos meses deseja recriar?')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->visible(
                        fn(Get $get): bool =>
                        (int) $get('VALOR_FIXO') === 1 &&
                            (int) $get('RECRIAR_RECEITA') === 1
                    )
                    ->hiddenOn('edit')
                    ->required(
                        fn(Get $get): bool =>
                        (int) $get('VALOR_FIXO') === 1 &&
                            (int) $get('RECRIAR_RECEITA') === 1
                    ),
            ]);
    }
}
