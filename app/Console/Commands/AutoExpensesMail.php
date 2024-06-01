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
            $today = Carbon::now();
            $fortnight = $today->copy()->subDays(15);
            $lastMonth = $today->copy()->subMonthNoOverflow();

            foreach ($users as $user) {
                $allExpenses = array();
                $totalExpense = 0;
                $expenses = BillModel::where('dump', '')->where('ID_USR', $user->id)
                    ->where('STATUS', 'Pago')->get();
                foreach ($expenses as $expense) {
                    $date_paid  = Carbon::parse($expense->PAGO_EM);
                    $date_send = Carbon::parse($user->EMAIL_GASTO);
                    $value = (float)$expense->VALOR;

                    if ($user->TIPO_NOTIF_GASTO == "Quinzenal" && $date_paid->between($fortnight, $today)) {
                        $allExpenses[] = $this->getExpense($expense, $date_paid);
                        $totalExpense += $value;
                    } elseif ($user->TIPO_NOTIF_GASTO == "Mensal" && ($date_paid->format('m') == $lastMonth->format('m') && ($date_paid->format('Y') == $lastMonth->format('Y')))) {
                        $allExpenses[] = $this->getExpense($expense, $date_paid);
                        $totalExpense += $value;
                    }
                }

                if (!empty($allExpenses) && $totalExpense > 0 && ($date_send->between($fortnight, $today) || $date_send->format('m') == $lastMonth->format('m'))) {
                    User::where('id', $user->id)->where('dump', '')->update(['EMAIL_GASTO' => $today]);
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
