<?php

namespace App\Actions\Income;

use App\Models\Income;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class AddFixedIncomeAction
{
    use AsAction;

    public function handle(Income $record, int $quantidade = 1): bool
    {
        if ((int) $record->VALOR_FIXO !== 1) {
            return false;
        }

        for ($i = 1; $i <= $quantidade; $i++) {
            Income::create([
                'ID_IDENTIF' => $record->ID_IDENTIF,
                'ID_USR' => $record->ID_USR,
                'DESCRICAO' => $record->DESCRICAO,
                'VALOR' => $record->VALOR,
                'VALOR_FIXO' => $record->VALOR_FIXO,
                'RECEBIDO_EM' => Carbon::parse($record->RECEBIDO_EM)->addMonths($i),
            ]);
        }

        return true;
    }
}
