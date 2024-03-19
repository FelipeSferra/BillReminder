<?php

namespace App\Http\Controllers;

use App\Models\BillModel;
use App\Models\IdentifierModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BillController {
    private $objBll;
    private $objIdf;

    public function __construct() {
        $this->objBll = new BillModel();
        $this->objIdf = new IdentifierModel();
    }

    public function index() {
        return view('dashboard.main');
    }

    private function getData(int $userId, $month = '*') {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->subMonths(1)->endOfMonth();

        if ($month == '*') {
            $bills = $this->objBll->selectRaw('MONTH(PAGO_EM) as MES, YEAR(PAGO_EM) as ANO, ROUND(SUM(VALOR), 2) as TOTAL_VALOR')
                ->where('id_usr', $userId)
                ->where('STATUS', 'Pago')
                ->where('dump', ' ')
                ->whereBetween('PAGO_EM', [$startDate, $endDate])
                ->groupBy('ANO', 'MES')
                ->orderBy('ANO')
                ->orderBy('MES')
                ->get();
        } else {
            $bills = $this->objBll->selectRaw('MONTH(PAGO_EM) as MES, YEAR(PAGO_EM) as ANO, ROUND(SUM(VALOR), 2) as TOTAL_VALOR,identifier.DESCRICAO AS TIPO_CONTA_DESCRICAO, identifier.IDENTIF AS IDENTIF_CONTA,identifier.ID_HEX as ID_HEX, bills.TIPO_CONTA')
                ->join('identifier', 'bills.TIPO_CONTA', '=', 'identifier.ID')
                ->where('bills.id_usr', $userId)
                ->where('bills.STATUS', 'Pago')
                ->where('bills.dump', ' ')
                ->where('identifier.dump', ' ')
                ->whereMonth('PAGO_EM', 3)
                ->groupBy('ANO', 'MES', 'TIPO_CONTA_DESCRICAO', 'IDENTIF_CONTA', 'ID_HEX', 'TIPO_CONTA')
                ->orderBy('ANO')
                ->orderBy('MES')
                ->get();
        }


        if ($month == '*') {
            foreach ($bills as $bill) {
                $month = $bill->MES;
                $fullMonth = Carbon::create()->day(1)->month($month)->isoFormat('MMMM');
                $bill->MES = ucfirst($fullMonth);
                $bill->MES_NUM = $month;
            }
        }

        return $bills;
    }

    private function getTableData(int $userId) {
        $bills = $this->objBll
            ->select('bills.id', 'bills.TIPO_CONTA', 'bills.DESCRICAO', 'bills.VALOR', 'bills.VENCIMENTO', 'bills.PARCELAS', 'bills.STATUS', 'bills.RECRIAR', 'identifier.DESCRICAO AS TIPO_CONTA_DESCRICAO', 'identifier.IDENTIF AS IDENTIF_CONTA', 'identifier.ID_HEX as ID_HEX')
            ->join('identifier', 'bills.TIPO_CONTA', '=', 'identifier.ID')
            ->where('identifier.dump', ' ')
            ->where('bills.id_usr', $userId)
            ->whereMonth('Vencimento', Carbon::now('America/Sao_Paulo')->month)
            ->where('bills.dump', ' ')
            ->orderByDesc('VALOR')
            ->take(10)
            ->get();

        return $bills;
    }

    private function dueDateTable($userId) {
        $bills = $this->objBll
            ->select('bills.id', 'bills.TIPO_CONTA', 'bills.DESCRICAO', 'bills.VALOR', 'bills.VENCIMENTO', 'bills.PARCELAS', 'bills.STATUS', 'bills.RECRIAR', 'identifier.DESCRICAO AS TIPO_CONTA_DESCRICAO', 'identifier.IDENTIF AS IDENTIF_CONTA', 'identifier.ID_HEX as ID_HEX')
            ->join('identifier', 'bills.TIPO_CONTA', '=', 'identifier.ID')
            ->where('identifier.dump', ' ')
            ->where('bills.id_usr', $userId)
            ->where('bills.STATUS', 'Pagar')
            ->whereMonth('Vencimento', Carbon::now('America/Sao_Paulo')->month)
            ->where('bills.dump', ' ')
            ->get();

        return $bills;
    }

    public function getDashboardData() {
        $userId = Auth::user()->id;
        $allBills = $this->getData($userId);

        $monthBills = $this->getData($userId, Carbon::now('America/Sao_Paulo')->month);

        $tableData = $this->getTableData($userId);
        $tableDueDate = $this->dueDateTable($userId);

        $countBills = $this->objBll->where('ID_USR', $userId)->where('STATUS', 'Pagar')->where('dump', '')->count();
        $countDueDate = $tableDueDate->count();
        $monthlyExpenses = $this->objBll->selectRaw('ROUND(SUM(VALOR), 2) as TOTAL_VALOR')
            ->where('ID_USR', $userId)
            ->where('STATUS', 'Pago')
            ->where('dump', '')
            ->whereMonth('PAGO_EM', Carbon::now('America/Sao_Paulo')->month)
            ->first();

        if ($countDueDate === 0)
            $countDueDate = 'Sem contas à vencer!';
        if ($countBills === 0)
            $countBills = 'Sem contas em aberto!';

        $allBills = json_encode($allBills);
        $monthBills = json_encode($monthBills);
        $tableData = json_encode($tableData);
        $tableDueDate = json_encode($tableDueDate);
        $countBills = json_encode($countBills);
        $countDueDate = json_encode($countDueDate);
        $monthlyExpenses = json_encode($monthlyExpenses);

        if ($allBills  && $monthBills && $tableData && $tableDueDate  && $countBills && $countDueDate && $monthlyExpenses)
            return response()->json([
                'allBills' => $allBills,
                'monthBills' => $monthBills,
                'tableData' => $tableData,
                'tableDueDate' => $tableDueDate,
                'countBills' => $countBills,
                'countDueDate' => $countDueDate,
                'monthlyExpenses' => $monthlyExpenses
            ])->header('Content-Type', 'application/json');
        else
            return response()->json(['error' => true, 'errorMessage' => 'Ocorreu ao recuperar os dados']);
    }
}
