<?php

namespace App\Filament\Tables;

use App\Models\FundHistory;

use Filament\Support\Colors\Color;

use Filament\Forms\Components\Select;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;

use Illuminate\Database\Eloquent\Builder;

class FundHistoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => FundHistory::query())
            ->columns([
                TextColumn::make('VALOR')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('TIPO_OPERACAO')
                    ->label('Tipo de Operação')
                    ->color(fn($record) => $record->TIPO_OPERACAO === 'GUARDAR' ? Color::Green : Color::Red)
                    ->badge()
                    ->sortable(),
                TextColumn::make('DATA_OPERACAO')
                    ->label('Data da Operação')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('filtro_tipo_operacao')
                    ->schema([
                        Select::make('TIPO_OPERACAO')
                            ->label('Tipo de Operação')
                            ->placeholder('Todos')
                            ->options([
                                'GUARDAR' => 'Guardar',
                                'RETIRAR' => 'Retirar',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $query->when(
                            filled($data['TIPO_OPERACAO']),
                            fn($q) => $q->where('TIPO_OPERACAO', $data['TIPO_OPERACAO'])
                        );

                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (filled($data['TIPO_OPERACAO'])) {
                            $indicators[] = Indicator::make('Tipo de Operação: ' . $data['TIPO_OPERACAO'])
                                ->removeField('TIPO_OPERACAO');
                        }

                        return $indicators;
                    }),
            ])
            ->defaultSort('DATA_OPERACAO', 'desc')
            ->defaultPaginationPageOption(10)
            ->paginated([10, 25, 50, 100, 'all']);
    }
}
