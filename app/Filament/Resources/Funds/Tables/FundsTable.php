<?php

namespace App\Filament\Resources\Funds\Tables;

use App\Models\Fund;
use App\Actions\Fund\AddRemoveFundValue;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;

use App\Filament\Schemas\Funds\FundActionForm;

use Filament\Notifications\Notification;

use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;

class FundsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('relIdfFnd.IDENTIF')
                    ->label('Tipo de Reserva')
                    ->badge()
                    ->color(fn($record) => $record->relIdfFnd?->ID_HEX
                        ? Color::hex($record->relIdfFnd->ID_HEX)
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
                TextColumn::make('META')
                    ->label('Meta')
                    ->money('BRL')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Reserva atualizada')
                                ->body('A reserva foi atualizada com sucesso.')
                        ),
                    Action::make('adicionarValor')
                        ->label('Adicionar Valor')
                        ->icon('hugeicons-add-circle-half-dot')
                        ->color(Color::Emerald)
                        ->schema(FundActionForm::components())
                        ->fillForm(fn(): array => [
                            'VALOR' => 1,
                            'TIPO_OPERACAO' => 'GUARDAR',
                        ])
                        ->action(function (Fund $record, array $data): void {
                            if (AddRemoveFundValue::run($record, $data)) {
                                Notification::make()
                                    ->success()
                                    ->title('Valor adicionado')
                                    ->body('O valor foi adicionado à reserva com sucesso.')
                                    ->send();
                            }
                        })
                        ->modalWidth('md')
                        ->modalSubmitActionLabel('Confirmar operação')
                        ->modalSubmitAction(
                            fn(Action $action) => $action->color('primary')
                        ),
                    Action::make('retirarValor')
                        ->label('Retirar Valor')
                        ->icon('hugeicons-remove-circle-half-dot')
                        ->color(Color::Red)
                        ->schema(FundActionForm::components())
                        ->fillForm(fn(): array => [
                            'VALOR' => 1,
                            'TIPO_OPERACAO' => 'RETIRAR',
                        ])
                        ->action(function (Fund $record, array $data): void {
                            if (AddRemoveFundValue::run($record, $data)) {
                                Notification::make()
                                    ->success()
                                    ->title('Valor retirado')
                                    ->body('O valor foi retirado da reserva com sucesso.')
                                    ->send();
                            }
                        })
                        ->modalWidth('md')
                        ->modalSubmitActionLabel('Confirmar operação')
                        ->modalSubmitAction(
                            fn(Action $action) => $action->color('primary')
                        )
                        ->hidden(fn(Fund $record) => $record->VALOR <= 0),
                    Action::make('fundHistory')
                        ->label('Histórico de transações')
                        ->icon('grommet-history')
                        ->color(Color::Blue)
                        ->modalContent(fn(Fund $record) => view('components.modals.fund-history-modal', [
                            'fundId' => $record->id,
                        ]))
                        ->modalWidth(Width::FiveExtraLarge)
                        ->stickyModalHeader()
                        ->stickyModalFooter()
                        ->modalSubmitAction(false),
                    DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Reserva excluída')
                                ->body('A reserva foi excluída com sucesso.')
                        ),
                    RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Reserva restaurada')
                                ->body('A reserva foi restaurada com sucesso.')
                        ),
                    ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Reserva excluída permanentemente')
                                ->body('A reserva foi excluída permanentemente.')
                        ),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Reservas excluídas')
                                ->body('As reservas foram excluídas com sucesso.')
                        ),
                    ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Reservas excluídas permanentemente')
                                ->body('As reservas foram excluídas permanentemente.')
                        ),
                    RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Reservas restauradas')
                                ->body('As reservas foram restauradas com sucesso.')
                        ),
                ]),
            ])
            ->recordAction('fundHistory');
    }
}
