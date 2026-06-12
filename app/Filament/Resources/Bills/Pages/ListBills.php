<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Filament\Resources\Bills\BillResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListBills extends ListRecords
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar conta')
                ->icon(Heroicon::OutlinedDocumentPlus)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Conta criada')
                        ->body('A conta foi criada com sucesso.')
                )
                ->mutateDataUsing(function (array $data): array {
                    $data['ID_USR'] = auth()->id();

                    return $data;
                })
                ->keyBindings(['shift+n']),
        ];
    }
}
