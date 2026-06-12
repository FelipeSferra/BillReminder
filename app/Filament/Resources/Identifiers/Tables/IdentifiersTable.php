<?php

namespace App\Filament\Resources\Identifiers\Tables;

use App\Models\Identifier;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class IdentifiersTable
{
    private static $identifiers_type = [
        'Receita',
        'Gasto',
        'Reserva',
        'Investimento'
    ];

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('IDENTIF')
                    ->label('Identificador')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('TIPO_IDENTIFICADOR')
                    ->label('Tipo de Identificador')
                    ->formatStateUsing(fn(string $state): string => self::$identifiers_type[$state] ?? $state)
                    ->searchable(),
                ColorColumn::make('ID_HEX')
                    ->label('Cor')
                    ->searchable(),
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
                                ->title('Identificador atualizado')
                                ->body('O identificador foi atualizado com sucesso.')
                        )
                        ->hidden(fn(Identifier $record) => $record->trashed()),
                    DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Identificador excluído')
                                ->body('O identificador foi excluído com sucesso.')
                        ),
                    RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Identificador restaurado')
                                ->body('O identificador foi restaurado com sucesso.')
                        ),
                    ForceDeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Identificador excluído permanentemente')
                                ->body('O identificador foi excluído permanentemente.')
                        ),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Identificadores excluídos')
                                ->body('Os identificadores foram excluídos com sucesso.')
                        ),
                    ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Identificadores excluídos permanentemente')
                                ->body('Os identificadores foram excluídos permanentemente.')
                        ),
                    RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Identificadores restaurados')
                                ->body('Os identificadores foram restaurados com sucesso.')
                        ),
                ]),
            ]);
    }
}
