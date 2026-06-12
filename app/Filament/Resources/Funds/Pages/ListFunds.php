<?php

namespace App\Filament\Resources\Funds\Pages;

use App\Filament\Resources\Funds\FundResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListFunds extends ListRecords
{
    protected static string $resource = FundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar reserva')
                ->icon(Heroicon::OutlinedDocumentPlus)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Reserva criada')
                        ->body('A reserva foi criada com sucesso.')
                )
                ->mutateDataUsing(function (array $data): array {
                    $data['ID_USR'] = auth()->id();

                    return $data;
                })
                ->keyBindings(['shift+n']),
        ];
    }
}
