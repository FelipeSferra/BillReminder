<?php

namespace App\Filament\Resources\Investiments\Tables;

use App\Actions\Investiment\AddRemoveInvestimentValue;
use App\Filament\Schemas\Investiments\InvestimentActionForm;
use App\Models\Investiment;
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
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class InvestimentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('relIdfInv.IDENTIF')
                    ->label('Tipo de Investimento')
                    ->badge()
                    ->color(fn($record) => $record->relIdfInv?->ID_HEX
                        ? Color::hex($record->relIdfInv->ID_HEX)
                        : 'gray')
                    ->default('Não Encontrado')
                    ->searchable(),
                TextColumn::make('DESCRICAO')
                    ->label('Descrição')
                    ->searchable(),
                TextColumn::make('VALOR')
                    ->label('Valor')
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
                                ->title('Investimento atualizado')
                                ->body('O investimento foi atualizado com sucesso.')
                        ),
                    Action::make('adicionarValor')
                        ->label('Adicionar Valor')
                        ->icon('hugeicons-add-circle-half-dot')
                        ->color(Color::Emerald)
                        ->schema(InvestimentActionForm::components())
                        ->fillForm(fn(): array => [
                            'VALOR' => 1,
                            'TIPO_OPERACAO' => 'APLICAR',
                        ])
                        ->action(function (Investiment $record, array $data): void {
                            if (AddRemoveInvestimentValue::run($record, $data)) {
                                Notification::make()
                                    ->success()
                                    ->title('Valor adicionado')
                                    ->body('O valor foi adicionado ao investimento com sucesso.')
                                    ->send();
                            }
                        })
                        ->modalWidth('md')
                        ->modalSubmitActionLabel('Confirmar operação')
                        ->modalSubmitAction(
                            fn(Action $action) => $action->color('primary')
                        ),
                    Action::make('resgatarValor')
                        ->label('Resgatar Valor')
                        ->icon('hugeicons-remove-circle-half-dot')
                        ->color(Color::Red)
                        ->schema(InvestimentActionForm::components())
                        ->fillForm(fn(): array => [
                            'VALOR' => 1,
                            'TIPO_OPERACAO' => 'RESGATAR',
                        ])
                        ->action(function (Investiment $record, array $data): void {
                            if (AddRemoveInvestimentValue::run($record, $data)) {
                                Notification::make()
                                    ->success()
                                    ->title('Valor resgatado')
                                    ->body('O valor foi resgatado do investimento com sucesso.')
                                    ->send();
                            }
                        })
                        ->modalWidth('md')
                        ->modalSubmitActionLabel('Confirmar operação')
                        ->modalSubmitAction(
                            fn(Action $action) => $action->color('primary')
                        )
                        ->hidden(fn(Investiment $record) => $record->VALOR <= 0),
                    Action::make('investimentHistory')
                        ->label('Histórico de transações')
                        ->icon('grommet-history')
                        ->color(Color::Blue)
                        ->modalContent(fn(Investiment $record) => view('components.modals.investiment-history-modal', [
                            'investimentId' => $record->id,
                        ]))
                        ->modalWidth(Width::FiveExtraLarge)
                        ->stickyModalHeader()
                        ->stickyModalFooter()
                        ->modalSubmitAction(false),
                    DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Investimento excluído')
                                ->body('O investimento foi excluído com sucesso.')
                        ),
                    RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Investimento restaurado')
                                ->body('O investimento foi restaurado com sucesso.')
                        ),
                    ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Investimento excluído permanentemente')
                                ->body('O investimento foi excluído permanentemente.')
                        ),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Investimentos excluídos')
                                ->body('Os investimentos foram excluídos com sucesso.')
                        ),
                    ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Investimentos excluídos permanentemente')
                                ->body('Os investimentos foram excluídos permanentemente.')
                        ),
                    RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Investimentos restaurados')
                                ->body('Os investimentos foram restaurados com sucesso.')
                        ),
                ]),
            ])
            ->recordAction('investimentHistory');
    }
}
