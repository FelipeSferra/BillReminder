<?php

namespace App\Filament\Clusters\Admin\ManageUsers\Resources\Roles\Pages;

use App\Filament\Clusters\Admin\ManageUsers\Resources\Roles\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Adicionar perfil')
                ->icon('hugeicons-add-circle-half-dot')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Perfil criado')
                        ->body('O perfil foi criado com sucesso.')
                )
                ->mutateDataUsing(function (array $data): array {
                    $data['guard_name'] = 'web';

                    return $data;
                })
                ->modalWidth('md'),
        ];
    }
}
