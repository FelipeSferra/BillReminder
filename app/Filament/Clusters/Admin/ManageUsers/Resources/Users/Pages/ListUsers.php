<?php

namespace App\Filament\Clusters\Admin\ManageUsers\Resources\Users\Pages;

use App\Filament\Clusters\Admin\ManageUsers\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar usuário')
                ->icon('hugeicons-user-add-01')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Usuário criado')
                        ->body('O usuário foi criado com sucesso.')
                ),
        ];
    }
}
