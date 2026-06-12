<?php

namespace App\Actions\Bill;

use App\Models\Bill;
use Filament\Notifications\Notification;
use Lorisleiva\Actions\Concerns\AsAction;

class ReverseBillAction
{
    use AsAction;

    public function handle(Bill $record): bool
    {
        if ($record->STATUS === 'Pagar') {
            Notification::make()
                ->warning()
                ->title('Conta não está paga')
                ->body("A conta \"{$record->DESCRICAO}\" ainda não foi paga.")
                ->send();

            return false;
        }

        $record->update([
            'STATUS' => 'Pagar',
            'PAGO_EM' => null
        ]);

        $rec_bill = Bill::where('ID_REC', $record->id)->first();
        $rec_bill?->forceDelete();

        return true;
    }
}
