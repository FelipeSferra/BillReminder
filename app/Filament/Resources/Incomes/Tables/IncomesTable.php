<?php

namespace App\Filament\Resources\Incomes\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IncomesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('relIdfInc.IDENTIF')
                    ->label('Tipo de Receita')
                    ->badge()
                    ->color(fn($record) => $record->relIdfInc?->ID_HEX
                        ? Color::hex($record->relIdfInc->ID_HEX)
                        : 'gray')
                    ->default('Não Encontrado')
                    ->sortable(),
                TextColumn::make('DESCRICAO')
                    ->label('Descrição')
                    ->searchable(),
                TextColumn::make('VALOR')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('RECEBIDO_EM')
                    ->label('Data Recebida')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('VALOR_FIXO')
                    ->label('Valor Fixo')
                    ->badge()
                    ->color(fn($record) => $record->VALOR_FIXO
                        ? Color::hex('#10b981')
                        : 'gray')
                    ->formatStateUsing(fn($record) => $record->VALOR_FIXO ? 'Sim' : 'Não')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                Filter::make('filtro_avancado')
                    ->schema([
                        Section::make('Período de Recebimento')
                            ->schema([
                                DatePicker::make('recebido_de')
                                    ->label('Recebido de'),

                                DatePicker::make('recebido_ate')
                                    ->label('Recebido até'),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        $query
                            ->when(
                                filled($data['recebido_de']),
                                fn($q) => $q->whereDate('RECEBIDO_EM', '>=', $data['recebido_de'])
                            )
                            ->when(
                                filled($data['recebido_ate']),
                                fn($q) => $q->whereDate('RECEBIDO_EM', '<=', $data['recebido_ate'])
                            );


                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (filled($data['recebido_de'])) {
                            $indicators[] = Indicator::make('Recebido de: ' . $data['recebido_de'])
                                ->removeField('recebido_de');
                        }

                        if (filled($data['recebido_ate'])) {
                            $indicators[] = Indicator::make('Recebido até: ' . $data['recebido_ate'])
                                ->removeField('recebido_ate');
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Recebimento editado')
                                ->body('O recebimento foi editado com sucesso.')
                        ),
                    DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Recebimento excluído')
                                ->body('O recebimento foi excluído com sucesso.')
                        ),
                    RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Recebimento restaurado')
                                ->body('O recebimento foi restaurado com sucesso.')
                        ),
                    ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Recebimento excluído permanentemente')
                                ->body('O recebimento foi excluído permanentemente.')
                        ),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Recebimentos excluídos')
                                ->body('Os recebimentos foram excluídos com sucesso.')
                        ),
                    ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Recebimentos excluídos permanentemente')
                                ->body('Os recebimentos foram excluídos permanentemente.')
                        ),
                    RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Recebimentos restaurados')
                                ->body('Os recebimentos foram restaurados com sucesso.')
                        ),
                ]),
            ])
            ->defaultSort('RECEBIDO_EM', 'desc')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all']);
    }
}
