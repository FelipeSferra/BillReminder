<?php

namespace App\Filament\Clusters\Admin\ManageUsers\Resources\Users\Tables;

use App\Models\User;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->limit(10)
                    ->tooltip(
                        fn($record) => $record->id
                    )
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Endereço de email')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Perfil')
                    ->badge()
                    ->color(
                        fn($record) => $record?->roles()->first()->ID_HEX
                            ? Color::hex($record?->roles()->first()->ID_HEX)
                            : 'gray'
                    )
                    ->formatStateUsing(
                        fn($record) => ucfirst($record?->roles()->first()->name ?? 'Nenhum')
                    )
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deletado em')
                    ->dateTime('d/m/Y H:i:s')
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
                                ->title('Usuário atualizado')
                                ->body('O usuário foi atualizado com sucesso.')
                        )
                        ->hidden(fn(User $record) => $record->trashed())
                        ->mutateDataUsing(function (array $data): array {
                            if (empty($data['password'])) {
                                unset($data['password']);
                            }

                            return $data;
                        }),
                    DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Usuário excluído')
                                ->body('O usuário foi excluído com sucesso.')
                        )
                        ->hidden(fn(User $record) => $record->id === auth()->id()),
                    ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Usuário excluído permanentemente')
                                ->body('O usuário foi excluído permanentemente com sucesso.')
                        )
                        ->hidden(fn(User $record) => $record->id === auth()->id()),
                    RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Usuário restaurado')
                                ->body('O usuário foi restaurado com sucesso.')
                        )
                        ->hidden(fn(User $record) => $record->id === auth()->id()),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->successNotification(null)
                        ->action(function (Collection $records) {
                            $currentUserId = auth()->id();
                            $filteredRecords = $records->filter(fn(User $record) => $record->id !== $currentUserId);

                            $filteredRecords->each->delete();

                            if ($records->count() !== $filteredRecords->count()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Exclusão parcial')
                                    ->body('O seu próprio usuário foi desconsiderado da exclusão em lote.')
                                    ->send();
                            }

                            if ($filteredRecords->isNotEmpty()) {
                                Notification::make()
                                    ->success()
                                    ->title('Usuários excluídos')
                                    ->body('Os usuários selecionados foram excluídos com sucesso.')
                                    ->send();
                            }
                        }),
                    ForceDeleteBulkAction::make()
                        ->successNotification(null)
                        ->action(function (Collection $records) {
                            $currentUserId = auth()->id();
                            $filteredRecords = $records->filter(fn(User $record) => $record->id !== $currentUserId);

                            $filteredRecords->each->forceDelete();

                            if ($records->count() !== $filteredRecords->count()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Exclusão parcial')
                                    ->body('O seu próprio usuário foi desconsiderado da exclusão permanente.')
                                    ->send();
                            }

                            if ($filteredRecords->isNotEmpty()) {
                                Notification::make()
                                    ->success()
                                    ->title('Usuários excluídos permanentemente')
                                    ->body('Os usuários selecionados foram excluídos permanentemente com sucesso.')
                                    ->send();
                            }
                        }),
                    RestoreBulkAction::make()
                        ->successNotification(null)
                        ->action(function (Collection $records) {
                            $currentUserId = auth()->id();
                            $filteredRecords = $records->filter(fn(User $record) => $record->id !== $currentUserId);

                            $filteredRecords->each->restore();

                            if ($records->count() !== $filteredRecords->count()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Restauração parcial')
                                    ->body('O seu próprio usuário foi desconsiderado da restauração.')
                                    ->send();
                            }

                            if ($filteredRecords->isNotEmpty()) {
                                Notification::make()
                                    ->success()
                                    ->title('Usuários restaurados')
                                    ->body('Os usuários selecionados foram restaurados com sucesso.')
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25, 50, 100, 'all']);
    }
}
