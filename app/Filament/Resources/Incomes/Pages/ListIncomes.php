<?php

namespace App\Filament\Resources\Incomes\Pages;

use App\Actions\Income\AddFixedIncomeAction;
use App\Filament\Resources\Incomes\IncomeResource;
use App\Models\Income;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListIncomes extends ListRecords
{
    protected static string $resource = IncomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar receita')
                ->icon(Heroicon::OutlinedDocumentPlus)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Recebimento criado')
                        ->body('O recebimento foi criado com sucesso.')
                )
                ->mutateDataUsing(function (array $data): array {
                    $data['ID_USR'] = auth()->id();

                    return $data;
                })
                ->using(function (array $data, string $model): Income {
                    $recriar = (int) ($data['RECRIAR_RECEITA'] ?? 0);
                    $quantidade = (int) ($data['RECRIAR_QUANTIDADE'] ?? 1);

                    unset($data['RECRIAR_RECEITA'], $data['RECRIAR_QUANTIDADE']);

                    $record = $model::create($data);

                    if ((int) $record->VALOR_FIXO === 1 && $recriar === 1) {
                        AddFixedIncomeAction::run(
                            record: $record,
                            quantidade: $quantidade,
                        );
                    }

                    return $record;
                })
                ->keyBindings(['shift+n']),
        ];
    }
}
