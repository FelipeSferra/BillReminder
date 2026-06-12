<?php

namespace App\Filament\Clusters\Admin\ManageUsers\Resources\Roles\Tables;

use App\Models\Role;
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
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                ColorColumn::make('ID_HEX')
                    ->label('Cor'),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Deletado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                                ->title('Perfil atualizado')
                                ->body('O perfil foi atualizado com sucesso.')
                        )
                        ->hidden(fn(Role $record) => $record->trashed())
                        ->modalWidth('md'),
                    DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Perfil deletado')
                                ->body('O perfil foi deletado com sucesso.')
                        ),
                    RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Perfil restaurado')
                                ->body('O perfil foi restaurado com sucesso.')
                        ),
                    ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Perfil excluído permanentemente')
                                ->body('O perfil foi excluído permanentemente com sucesso.')
                        )
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Perfis deletados')
                                ->body('Os perfis foram deletados com sucesso.')
                        ),
                    ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Perfis deletados permanentemente')
                                ->body('Os perfis foram deletados permanentemente com sucesso.')
                        ),
                    RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Perfis restaurados')
                                ->body('Os perfis foram restaurados com sucesso.')
                        ),
                ]),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all']);
    }
}
