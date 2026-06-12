<?php

namespace App\Filament\Resources\Investiments\Pages;

use App\Filament\Resources\Investiments\InvestimentResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListInvestiments extends ListRecords
{
    protected static string $resource = InvestimentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar Investimento')
                ->icon(Heroicon::OutlinedDocumentPlus)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Investimento criado')
                        ->body('O Investimento foi criado com sucesso.')
                )
                ->mutateDataUsing(function (array $data): array {
                    $data['ID_USR'] = auth()->id();

                    return $data;
                })
                ->keyBindings(['shift+n'])
        ];
    }
}
