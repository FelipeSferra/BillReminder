<?php

namespace App\Actions\Investiment;

use App\Models\Investiment;
use App\Models\InvestimentHistory;
use Filament\Notifications\Notification;
use Lorisleiva\Actions\Concerns\AsAction;

class AddRemoveInvestimentValue
{
    use AsAction;

    public function handle(Investiment $record, array $data): bool
    {
        if ($data['VALOR'] <= 0) {
            Notification::make()
                ->warning()
                ->title('Valor inválido')
                ->body('O valor enviado para realizar essa operação é inválido.')
                ->send();
            return false;
        } elseif ($record->VALOR <= 0 && $data['TIPO_OPERACAO'] === 'RETIRAR') {
            Notification::make()
                ->warning()
                ->title('Saldo insuficiente')
                ->body('O saldo da reserva é insuficiente para realizar a operação.')
                ->send();
            return false;
        }

        if ($data['TIPO_OPERACAO'] === 'APLICAR') {
            $record->increment('VALOR', $data['VALOR']);
            InvestimentHistory::create([
                'ID_INVESTIMENT' => $record->id,
                'ID_USR' => auth()->id(),
                'VALOR' => $data['VALOR'],
                'TIPO_OPERACAO' => 'APLICAR',
                'DATA_OPERACAO' => now(),
            ]);
        } elseif ($data['TIPO_OPERACAO'] === 'RESGATAR') {
            $record->decrement('VALOR', $data['VALOR']);
            InvestimentHistory::create([
                'ID_INVESTIMENT' => $record->id,
                'ID_USR' => auth()->id(),
                'VALOR' => $data['VALOR'],
                'TIPO_OPERACAO' => 'RESGATAR',
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
