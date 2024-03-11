<?php

namespace App\Console\Commands;

use App\Mail\AllExpenses;
use App\Models\BillModel;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class AutoExpensesMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expenses-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia o email (mensal/quinzenal) de todos os gastos do mes inteiro (caso mensal) ou das duas primeiras semanas do mes atual (caso quinzenal)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo "\t\tIniciando rotina de envio de email | Todas as contas\n\n";

        $users = User::where('dump', '')->where('NOTIFICAR_GASTO', 'S')->get();

        if (count($users) > 0) {
            foreach ($users as $user) {
                $allExpenses = array();
                $totalExpense = 0;
                $expenses = BillModel::where('dump', '')->where('ID_USR', $user->id)
                    ->where('STATUS', 'Pago')->get();
                foreach ($expenses as $expense) {
                    $date_paid  = Carbon::parse($expense->PAGO_EM);
                    $date_send = Carbon::parse($user->EMAIL_GASTO);
                    $date = Carbon::now();

                    if ($user->TIPO_NOTIF_GASTO == "Quinzenal" && $date_paid->diffInDays($date) >= 0 && $date_paid->diffInDays($date) <= 15) {
                        $allExpenses[] = $this->getExpense($expense, $date_paid);
                        $totalExpense += (float)$expense->VALOR;
                    } elseif ($user->TIPO_NOTIF_GASTO == "Mensal" && $date_paid->diffInMonths($date) == 1) {
                        $allExpenses[] = $this->getExpense($expense, $date_paid);
                        $totalExpense += (float)$expense->VALOR;
                    }
                }
                if (!empty($allExpenses) && ($date_send->diffInDays($date) == 15 || $date_send->diffInMonths($date) == 1)) {
                    User::where('id', $user->id)->where('dump', '')->update(['EMAIL_GASTO' => $date]);
                    $totalExpense = number_format($totalExpense, 2, ",", ".");
                    Mail::to($user->email)->send(new AllExpenses($user, $allExpenses, $totalExpense));
                    echo "\t\tEmail enviado para " . $user->email . " \n\n";
                } else {
                    echo "\t\tSem gastos mensais/quinzenais para " . $user->email . " \n\n";
                }
            }
            echo "\t\tFinalizando a rotina\n\n";
        } else {
            echo "\t\tFinalizando a rotina | Sem usuario para envio\n\n";
        }
    }

    private function getExpense($expense, $date_paid)
    {
        $expense->PAGO_EM = $date_paid->format('d/m/Y');
        $expense->VALOR = number_format($expense->VALOR, 2, ",", ".");
        return $expense;
    }
}
