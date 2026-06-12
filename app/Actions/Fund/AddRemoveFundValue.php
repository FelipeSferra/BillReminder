<?php

namespace App\Actions\Fund;

use App\Models\Fund;
use App\Models\FundHistory;
use Filament\Notifications\Notification;
use Lorisleiva\Actions\Concerns\AsAction;

class AddRemoveFundValue
{
    use AsAction;

    public function handle(Fund $record, array $data): bool
    {
        if ($data['VALOR'] <= 0) {
            Notification::make()
                ->warning()
                ->title('Valor inválido')
                ->body('O valor enviado para realizar essa operação é inválido.')
                ->send();
            return false;
        } elseif (($record->VALOR <= 0 || $data['VALOR'] > $record->VALOR) && $data['TIPO_OPERACAO'] === 'RETIRAR') {
            Notification::make()
                ->warning()
                ->title('Saldo insuficiente')
                ->body('O saldo da reserva é insuficiente para realizar a operação.')
                ->send();
            return false;
        }

        if ($data['TIPO_OPERACAO'] === 'GUARDAR') {
            $record->increment('VALOR', $data['VALOR']);
            FundHistory::create([
                'ID_FUND' => $record->id,
                'ID_USR' => auth()->id(),
                'VALOR' => $data['VALOR'],
                'TIPO_OPERACAO' => 'GUARDAR',
                'DATA_OPERACAO' => now(),
            ]);
        } elseif ($data['TIPO_OPERACAO'] === 'RETIRAR') {
            $record->decrement('VALOR', $data['VALOR']);
            FundHistory::create([
                'ID_FUND' => $record->id,
                'ID_USR' => auth()->id(),
                'VALOR' => $data['VALOR'],
                'TIPO_OPERACAO' => 'RETIRAR',
                'DATA_OPERACAO' => now(),
            ]);
        } else {
            Notification::make()
                ->warning()
                ->title('Tipo de operação inválido')
                ->body('O tipo de operação é inválido, tente novamente.')
                ->send();
            return false;
        }

        return true;
    }
}
