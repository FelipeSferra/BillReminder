<?php

namespace App\Filament\Resources\Identifiers\Pages;

use App\Filament\Resources\Identifiers\IdentifierResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListIdentifiers extends ListRecords
{

    protected static string $resource = IdentifierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar identificador')
                ->icon('hugeicons-add-circle-half-dot')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Identificador criado')
                        ->body('O identificador foi criado com sucesso.')
                )
                ->mutateDataUsing(function (array $data): array {
                    $data['ID_USR'] = auth()->id();

                    return $data;
                })
                ->keyBindings(['shift+n']),
        ];
    }
}
