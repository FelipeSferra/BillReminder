<?php

namespace App\Filament\Resources\Bills\Tables;

// Custom Imports
use App\Models\Bill;
use App\Actions\Bill\PayBillAction;
use App\Actions\Bill\ReverseBillAction;

// Filament Action Groups Imports
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;

// Filament Actions Imports
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;

// Filament Bulk Actions Imports
use Filament\Actions\BulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;

// Filament Forms Imports
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;

// Filament Schemas Imports
use Filament\Schemas\Components\Section;

// Filament Tables Imports
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Columns\TextColumn;

// Filament Notifications Imports
use Filament\Notifications\Notification;

// Filament Support Imports
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

// Illuminate Database Imports
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('relIdfBll.IDENTIF')
                    ->label('Tipo de Conta')
                    ->badge()
                    ->color(fn($record) => $record->relIdfBll?->ID_HEX
                        ? Color::hex($record->relIdfBll->ID_HEX)
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
                TextColumn::make('VENCIMENTO')
                    ->label('Vencimento')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('PARCELAS')
                    ->label('Parcelas')
                    ->numeric()
                    ->default('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('STATUS')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pagar' => 'warning',
                        'Pago' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('RECORRENTE')
                    ->label('Recorrente')
                    ->formatStateUsing(fn($state) => $state ? 'Sim' : 'Não')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('PAGO_EM')
                    ->label('Pago em')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                Filter::make('filtro_avancado')
                    ->schema([
                        Select::make('STATUS')
                            ->label('Status')
                            ->placeholder('Todos')
                            ->options([
                                'Pagar' => 'Pagar',
                                'Pago' => 'Pago',
                            ])
                            ->live(),

                        Section::make('Período de Vencimento')
                            ->hidden(fn($get) => $get('STATUS') !== 'Pagar')
                            ->schema([
                                DatePicker::make('vencimento_de')
                                    ->label('Vencimento de'),

                                DatePicker::make('vencimento_ate')
                                    ->label('Vencimento até'),
                            ]),

                        Section::make('Período de Pagamento')
                            ->hidden(fn($get) => $get('STATUS') !== 'Pago')
                            ->schema([
                                DatePicker::make('pago_de')
                                    ->label('Pago em'),

                                DatePicker::make('pago_ate')
                                    ->label('Pago até'),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $query->when(
                            filled($data['STATUS']),
                            fn($q) => $q->where('STATUS', $data['STATUS'])
                        );

                        if (($data['STATUS'] ?? null) === 'Pagar') {
                            $query
                                ->when(
                                    filled($data['vencimento_de']),
                                    fn($q) => $q->whereDate('VENCIMENTO', '>=', $data['vencimento_de'])
                                )
                                ->when(
                                    filled($data['vencimento_ate']),
                                    fn($q) => $q->whereDate('VENCIMENTO', '<=', $data['vencimento_ate'])
                                );
                        }

                        if (($data['STATUS'] ?? null) === 'Pago') {
                            $query
                                ->when(
                                    filled($data['pago_de']),
                                    fn($q) => $q->whereDate('PAGO_EM', '>=', $data['pago_de'])
                                )
                                ->when(
                                    filled($data['pago_ate']),
                                    fn($q) => $q->whereDate('PAGO_EM', '<=', $data['pago_ate'])
                                );
                        }

                        return $query;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (filled($data['STATUS'])) {
                            $indicators[] = Indicator::make('Status: ' . $data['STATUS'])
                                ->removeField('STATUS');
                        }

                        if (filled($data['vencimento_de'])) {
                            $indicators[] = Indicator::make('Vencimento a partir de: ' . $data['vencimento_de'])
                                ->removeField('vencimento_de');
                        }

                        if (filled($data['vencimento_ate'])) {
                            $indicators[] = Indicator::make('Vencimento até: ' . $data['vencimento_ate'])
                                ->removeField('vencimento_ate');
                        }

                        if (filled($data['pago_de'])) {
                            $indicators[] = Indicator::make('Pago em: ' . $data['pago_de'])
                                ->removeField('pago_de');
                        }

                        if (filled($data['pago_ate'])) {
                            $indicators[] = Indicator::make('Pago até: ' . $data['pago_ate'])
                                ->removeField('pago_ate');
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
                                ->title('Conta editada')
                                ->body('A conta foi editada com sucesso.')
                        )
                        ->hidden(fn(Bill $record) => $record->trashed()),
                    Action::make('Pagar')
                        ->icon(Heroicon::DocumentCheck)
                        ->color(Color::Green)
                        ->visible(fn(Bill $record) => $record->PAGO_EM === null)
                        ->action(function (Bill $record) {
                            if (PayBillAction::run($record)) {
                                Notification::make()
                                    ->success()
                                    ->title('Conta paga')
                                    ->body('A conta foi paga com sucesso.')
                                    ->send();
                            }
                        })
                        ->hidden(fn(Bill $record) => $record->trashed()),
                    Action::make('Cancelar Pagamento')
                        ->icon(Heroicon::ArrowUturnLeft)
                        ->color(Color::Amber)
                        ->visible(fn(Bill $record) => $record->PAGO_EM !== null)
                        ->requiresConfirmation()
                        ->modalHeading('Cancelar Pagamento')
                        ->modalSubheading('Você tem certeza que deseja cancelar o pagamento?')
                        ->action(function (Bill $record) {
                            if (ReverseBillAction::run($record)) {
                                Notification::make()
                                    ->success()
                                    ->title('Pagamento cancelado')
                                    ->body('O pagamento foi cancelado com sucesso.')
                                    ->send();
                            }
                        })
                        ->hidden(fn(Bill $record) => $record->trashed()),
                    DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Conta excluída')
                                ->body('A conta foi excluída com sucesso.')
                        ),
                    RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Conta restaurada')
                                ->body('A conta foi restaurada com sucesso.')
                        ),
                    ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Conta excluída permanentemente')
                                ->body('A conta foi excluída permanentemente.')
                        ),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('Pagar selecionado')
                        ->icon(Heroicon::DocumentCheck)
                        ->color(Color::Green)
                        ->action(function (Collection $records) {
                            $paid = $records->filter(fn($record) => PayBillAction::run($record))->count();
                            $skipped = $records->count() - $paid;

                            if ($paid > 0) {
                                Notification::make()
                                    ->success()
                                    ->title("{$paid} conta(s) paga(s)")
                                    ->body($skipped > 0 ? "{$skipped} conta(s) já estavam pagas." : 'Todas as contas foram pagas com sucesso.')
                                    ->send();
                            }
                        }),
                    BulkAction::make('Cancelar pagamento selecionado')
                        ->icon(Heroicon::ArrowUturnLeft)
                        ->color(Color::Amber)
                        ->requiresConfirmation()
                        ->modalHeading('Cancelar pagamentos selecionados')
                        ->modalSubheading('Você tem certeza que deseja cancelar os pagamentos selecionados?')
                        ->action(function (Collection $records) {
                            $reversed = $records->filter(fn($record) => ReverseBillAction::run($record))->count();
                            $skipped = $records->count() - $reversed;

                            if ($reversed > 0) {
                                Notification::make()
                                    ->success()
                                    ->title("{$reversed} pagamento(s) cancelado(s)")
                                    ->body($skipped > 0 ? "{$skipped} conta(s) não estavam pagas." : 'Todos os pagamentos foram cancelados com sucesso.')
                                    ->send();
                            }
                        }),
                    DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Contas excluídas')
                                ->body('As contas foram excluídas com sucesso.')
                        ),
                    ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Contas excluídas permanentemente')
                                ->body('As contas foram excluídas permanentemente.')
                        ),
                    RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Contas restauradas')
                                ->body('As contas foram restauradas com sucesso.')
                        ),
                ]),
            ])
            ->defaultSort('STATUS', 'asc')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all']);
    }
}
