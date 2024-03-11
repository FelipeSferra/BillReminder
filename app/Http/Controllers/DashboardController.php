<?php

namespace App\Http\Controllers;

use App\Models\BillModel;
use App\Models\IdentifierModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BillController
{
    private $objBll;
    private $objIdf;
    private $objUsr;

    public function __construct()
    {
        $this->objBll = new BillModel();
        $this->objIdf = new IdentifierModel();
        $this->objUsr = new User();
    }

    public function index()
    {
        $userId = Auth::user()->id;

        $allBillsByType = $this->getData($userId);
        $monthBillsByType = $this->getData($userId, Carbon::now('America/Sao_Paulo')->month);

        $tableData = $this->getTableData($userId);
        $tableDueDate = $this->dueDateTable($userId);

        $identifiers = $this->objIdf
            ->select('id', 'ID_HEX')
            ->where('id_usr', $userId)
            ->where('dump', ' ')
            ->get();

        $countBills = $this->objBll->where('ID_USR', $userId)->where('STATUS', 'Pagar')->where('dump', '')->count();
        $countDueDate = $tableDueDate->count();
        $monthlyExpenses = $this->objBll->selectRaw('SUM(VALOR) as TOTAL_VALOR')
            ->where('ID_USR', $userId)
            ->where('STATUS', 'Pago')
            ->where('dump', '')
            ->whereMonth('PAGO_EM', Carbon::now('America/Sao_Paulo')->month)
            ->first();

        return view('dashboard.main', compact('allBillsByType', 'monthBillsByType', 'tableData', 'identifiers', 'tableDueDate', 'countBills', 'monthlyExpenses', 'countDueDate'));
    }

    private function getData(int $userId, $month = '*')
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->subMonths(1)->endOfMonth();

        if ($month == '*') {
            $bills = $this->objBll->selectRaw('MONTH(PAGO_EM) as MES, YEAR(PAGO_EM) as ANO, SUM(VALOR) as TOTAL_VALOR')
                ->where('id_usr', $userId)
                ->where('STATUS', 'PAGO')
                ->where('dump', ' ')
                ->whereBetween('PAGO_EM', [$startDate, $endDate])
                ->groupBy('ANO')
                ->groupBy('MES')
                ->orderBy('ANO')
                ->orderBy('MES')
                ->get();
        } else {
            $bills = $this->objBll->select('*')->where('id_usr', $userId)
                ->where('STATUS', 'Pago')
                ->whereMonth('PAGO_EM', $month)
                ->where('dump', ' ')
                ->get();
        }

        $uniqueTipoConta = $bills->pluck('TIPO_CONTA')->unique();

        $identifiers = $this->objIdf
            ->select('*')
            ->where('id_usr', $userId)
            ->whereIn('id', $uniqueTipoConta)
            ->where('dump', ' ')
            ->get();
        if ($month != '*') {
            $bills = $this->groupByValue($bills, 'TIPO_CONTA');
            foreach ($bills as $bill) {
                foreach ($identifiers as $identifier) {
                    if ($bill->TIPO_CONTA == $identifier->id) {
                        $bill->DESCRICAO =  $identifier->DESCRICAO;
                        $bill->ID_HEX = $identifier->ID_HEX;
                    }
                }
            }
        } else {
            foreach ($bills as $bill) {
                $month = $bill->MES;
                $fullMonth = Carbon::create()->day(1)->month($month)->isoFormat('MMMM');
                $bill->MES = ucfirst($fullMonth);
                $bill->MES_NUM = $month;
            }
        }

        return $bills;
    }

    private function getTableData(int $userId)
    {
        $bills = $this->objBll->select('*')->where('id_usr', $userId)
            ->whereMonth('Vencimento', Carbon::now('America/Sao_Paulo')->month)
            ->where('dump', ' ')
            ->orderByDesc('VALOR')
            ->take(10)
            ->get();

        /*         foreach ($bills as $bill) {
            $dataDoBanco = Carbon::parse($bill->VENCIMENTO);

            $bill->data_formatada = $dataDoBanco->format('d/m/Y');
        }

        $uniqueTipoConta = $bills->pluck('TIPO_CONTA')->unique();

        $identifiers = $this->objIdf
            ->select('*')
            ->where('id_usr', $userId)
            ->whereIn('id', $uniqueTipoConta)
            ->where('dump', ' ')
            ->get();

        $bills = $this->groupByValue($bills, 'TIPO_CONTA');

        foreach ($bills as $bill) {
            foreach ($identifiers as $identifier) {
                if ($bill->TIPO_CONTA == $identifier->id) {
                    $bill->DESCRICAO =  $identifier->DESCRICAO;
                }
            }
        }
 */
        return $bills;
    }

    private function dueDateTable($userId)
    {
        $billDueDate = array();

        $user = $this->objUsr->select('*')->where('id', $userId)->where('dump', '')->first();

        $bills = $this->objBll->select('*')
            ->where('ID_USR', $userId)
            ->where('STATUS', 'Pagar')
            ->whereMonth('Vencimento', Carbon::now('America/Sao_Paulo')->month)
            ->where('dump', '')
            ->get();
        $bills = $this->getBills($bills);

        return $bills;
    }

    private function getBills($bills)
    {
        foreach ($bills as $bill) {
            $venc = Carbon::parse($bill->VENCIMENTO);
            $bill->VENCIMENTO = $venc->format('d/m/Y');
            $bill->VALOR = number_format($bill->VALOR, 2, ",", ".");
        }
        return $bills;
    }
}
