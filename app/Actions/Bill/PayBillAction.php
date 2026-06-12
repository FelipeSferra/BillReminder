<?php

namespace App\Actions\Bill;

use App\Models\Bill;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Lorisleiva\Actions\Concerns\AsAction;

class PayBillAction
{
    use AsAction;

    public function handle(Bill $record): bool
    {
        if ($record->STATUS === 'Pago') {
            Notification::make()
                ->warning()
                ->title('Conta já paga')
                ->body("A conta \"{$record->DESCRICAO}\" já foi paga.")
                ->send();

            return false;
        }

        $record->update([
            'STATUS' => 'Pago',
            'PAGO_EM' => now()
        ]);

        if ($record->PARCELAS > 1) {
            $record->create([
                'ID_IDENTIF' => $record->ID_IDENTIF,
                'DESCRICAO' => $record->DESCRICAO,
                'VALOR' => $record->VALOR,
                'VENCIMENTO' => Carbon::parse($record->VENCIMENTO)->addMonth(),
                'PARCELAS' => $record->PARCELAS - 1,
                'RECORRENTE' => $record->RECORRENTE,
                'ID_USR' => $record->ID_USR,
                'STATUS' => 'Pagar',
                'ID_REC' => $record->id,
            ]);
        } elseif ($record->RECORRENTE) {
            $record->create([
                'ID_IDENTIF' => $record->ID_IDENTIF,
                'DESCRICAO' => $record->DESCRICAO,
                'VALOR' => $record->VALOR,
                'VENCIMENTO' => Carbon::parse($record->VENCIMENTO)->addMonth(),
                'RECORRENTE' => $record->RECORRENTE,
                'ID_USR' => $record->ID_USR,
                'STATUS' => 'Pagar',
                'ID_REC' => $record->id,
            ]);
        }

        return true;
    }
}
