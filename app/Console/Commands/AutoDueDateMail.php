<?php

namespace App\Console\Commands;

use App\Mail\DueDate;
use App\Models\BillModel;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class AutoDueDateMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:due-date-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de emails com as contas que vão vencer em X dias (sendo X uma variavel informada pelo usuario)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo "\t\tIniciando rotina de envio de email | Vencimentos\n\n";

        $users = User::where('dump', '')->where('NOTIFICAR_VENC', 'S')->get();

        if (count($users) > 0) {
            foreach ($users as $user) {
                $billDueDate = array();
                $billOverDue = array();
                $bills = BillModel::where('dump', '')->where('ID_USR', $user->id)
                    ->where('STATUS', 'Pagar')->get();
                foreach ($bills as $bill) {
                    $venc = Carbon::parse($bill->VENCIMENTO);
                    $diff = $venc->diffInDays(Carbon::now()) + 1;
                    if ($venc >= Carbon::now()) {
                        if ($diff == $user->VENC_DIAS) {
                            $bill->Dias = $diff . ' Dias';
                            $billDueDate[] = $this->getBills($bill, $venc);
                        } elseif ($diff == 3) {
                            $bill->Dias = $diff . ' Dias';
                            $billDueDate[] = $this->getBills($bill, $venc);
                        } elseif ($venc->format('d/m/Y') === Carbon::now()->format('d/m/Y')) {
                            $bill->Dias = 'Hoje';
                            $billDueDate[] = $this->getBills($bill, $venc);
                        }
                    } else {
                        $bill->Atraso = $diff . ' Dias';
                        $billOverDue[] = $this->getBills($bill, $venc);
                    }
                }
                if (!empty($billDueDate) || !empty($billOverDue)) {
                    if (!empty($billOverDue))
                        Mail::to($user->email)->send(new DueDate($user, $billDueDate, $billOverDue));
                    else
                        Mail::to($user->email)->send(new DueDate($user, $billDueDate));

                    echo "\t\tEmail enviado para " . $user->email . " \n\n";
                    if (!empty($user->EMAIL_SECUNDARIO)) {
                        if (!empty($billOverDue))
                            Mail::to($user->email)->send(new DueDate($user, $billDueDate, $billOverDue));
                        else
                            Mail::to($user->email)->send(new DueDate($user, $billDueDate));

                        echo "\t\tEmail enviado para " . $user->EMAIL_SECUNDARIO . " \n\n";
                    }
                } else {
                    echo "\t\tSem contas para " . $user->email . " \n\n";
                }
            }
            echo "\t\tFinalizando a rotina\n\n";
        } else {
            echo "\t\tFinalizando a rotina | Sem usuario para envio\n\n";
        }
    }

    private function getBills($bill, $venc)
    {
        $bill->VENCIMENTO = $venc->format('d/m/Y');
        $bill->VALOR = number_format($bill->VALOR, 2, ",", ".");
        return $bill;
    }
}
