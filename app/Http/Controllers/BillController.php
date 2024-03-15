<?php

namespace App\Http\Controllers;

use App\Models\BillModel;
use App\Models\IdentifierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    private $objBll;
    private $objIdf;

    public function __construct()
    {
        $this->objBll = new BillModel();
        $this->objIdf = new IdentifierModel();
    }

    // Retorna a view principal
    public function index()
    {
        return view('bill.main');
    }

    // cadastra no banco com os dados
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $data = $this->objBll->create([
            'ID_USR' => $userId,
            'TIPO_CONTA' => $request->tipo_conta,
            'DESCRICAO' => $request->descricao,
            'VALOR' => $request->valor,
            'VENCIMENTO' => $request->vencimento,
            'PARCELAS' => $request->parcelas,
            'RECRIAR' => $request->recriar,
            'STATUS' => $request->status,
            'CRIADO_EM' => Carbon::now(),
        ]);

        if ($data)
            return response()->json(['message' => 'A conta foi criado com sucesso!']);
        else
            return response()->json(['error' => true, 'errorMessage' => 'Erro ao criar a conta, tente novamente!']);
    }

    public function edit(string $id)
    {
        $userId = Auth::user()->id;
        $bills = $this->objBll->select('id', 'TIPO_CONTA', 'DESCRICAO', 'VALOR', 'VENCIMENTO', 'PARCELAS', 'STATUS', 'RECRIAR')->where('id_usr', $userId)
            ->where('id', $id)
            ->where('dump', ' ')
            ->first();

        if (!$bills) {
            return response()->json(['notFound' => true]);
        }

        return response()->json($bills);
    }

    // faz o update na tabela
    public function update(Request $request, string $id)
    {
        $userId = Auth::user()->id;
        $data = $this->objBll->where('id', $id)
            ->where('id_usr', $userId)
            ->where('dump', ' ')->update([
                'TIPO_CONTA' => $request->tipo_contaEdt,
                'DESCRICAO' => $request->descricaoEdt,
                'VALOR' => $request->valorEdt,
                'VENCIMENTO' => $request->vencimentoEdt,
                'PARCELAS' => $request->parcelasEdt,
                'STATUS' => $request->statusEdt,
                'RECRIAR' => $request->recriarEdt,
            ]);

        if ($data)
            return response()->json(['message' => 'A conta foi editada com sucesso!']);
        else
            return response()->json(['error' => true, 'errorMessage' => 'Erro ao editar a conta, tente novamente!']);
    }

    // excluir da tabela (logicamente)
    public function destroy(string $id)
    {
        $ids = explode(',', $id);

        $userId = Auth::user()->id;
        $data = $this->objBll->whereIn('id', $ids)->where('id_usr', $userId)
            ->where('dump', ' ')
            ->update(['dump' => '*']);
        if ($data)
            return response()->json(['message' => 'A conta foi excluída com sucesso!']);
        else
            return response()->json(['error' => true, 'errorMessage' => 'Não foi possível excluir a conta!']);
    }

    public function concluded(string $id)
    {
        try {

            $ids = explode(',', $id);

            $userId = Auth::user()->id;

            foreach ($ids as $id) {
                $dataRec = $this->objBll->select('*')->where('id_usr', $userId)
                    ->where('id', $id)
                    ->where('dump', '')
                    ->firstOrFail();


                if ($dataRec->STATUS == 'Pago') {
                    return response()->json(['error' => true, 'errorMessage' => 'A conta já está no status "Pago"']);
                }

                $parcelas = max(1, $dataRec->PARCELAS - 1);

                if ($dataRec->RECRIAR == "Sim" || $dataRec->PARCELAS > 1) {

                    $this->recreateBill($dataRec, $userId, $parcelas);
                }
            }


            $this->objBll->where('id_usr', $userId)
                ->whereIn('id', $ids)
                ->where('dump', '')
                ->update([
                    'STATUS' => 'Pago',
                    'PAGO_EM' => Carbon::now()->format('Y-m-d'),
                ]);

            return response()->json(['message' => 'A conta foi atualizada para o status "Pago" com sucesso!']);
        } catch (\Throwable $e) {
            return response()->json(['error' => true, 'errorMessage' => 'Ocorreu um erro ao processar a solicitação.']);
        }
    }

    private function recreateBill($dataRec, $userId, $parcelas)
    {
        $newVenc = Carbon::parse($dataRec->VENCIMENTO);
        $newVenc = $newVenc->addMonth()->format('Y-m-d');

        $this->objBll->create([
            'ID_USR' => $userId,
            'TIPO_CONTA' => $dataRec->TIPO_CONTA,
            'DESCRICAO' => $dataRec->DESCRICAO,
            'VALOR' => $dataRec->VALOR,
            'VENCIMENTO' => $newVenc,
            'PARCELAS' => $parcelas,
            'RECRIAR' => $dataRec->RECRIAR,
            'STATUS' => $dataRec->STATUS,
            'CRIADO_EM' => Carbon::now()
        ]);
    }

    protected function groupByValue($array, $key)
    {
        $result = [];
        foreach ($array as $item) {
            if (!isset($result[$item[$key]])) {
                $result[$item[$key]] = (object) [
                    $key => $item[$key],
                    'TOTAL_VALOR' => 0
                ];
            }

            $result[$item[$key]]->TOTAL_VALOR += floatval($item['VALOR']);
        }

        return array_values($result);
    }

    public function filter($status, $tipo)
    {
        $userId = Auth::user()->id;
        if ($status != 'Todos') {
            $bills = DB::table('bills')
                ->select('bills.id', 'bills.TIPO_CONTA', 'bills.DESCRICAO', 'bills.VALOR', 'bills.VENCIMENTO', 'bills.PARCELAS', 'bills.STATUS', 'bills.RECRIAR', 'identifier.DESCRICAO AS TIPO_CONTA_DESCRICAO', 'identifier.IDENTIF AS IDENTIF_CONTA', 'identifier.ID_HEX as ID_HEX')
                ->join('identifier', 'bills.TIPO_CONTA', '=', 'identifier.ID')
                ->where('bills.id_usr', $userId)
                ->where('bills.STATUS', $status)
                ->where('bills.dump', ' ')
                ->get();
        } else {
            $bills = DB::table('bills')
                ->select('bills.id', 'bills.TIPO_CONTA', 'bills.DESCRICAO', 'bills.VALOR', 'bills.VENCIMENTO', 'bills.PARCELAS', 'bills.STATUS', 'bills.RECRIAR', 'identifier.DESCRICAO AS TIPO_CONTA_DESCRICAO', 'identifier.IDENTIF AS IDENTIF_CONTA', 'identifier.ID_HEX as ID_HEX')
                ->join('identifier', 'bills.TIPO_CONTA', '=', 'identifier.ID')
                ->where('bills.id_usr', $userId)
                ->where('bills.dump', ' ')
                ->get();
        }
        if ($bills) {
            if ($tipo == "Simplificado") {
                $bills = json_decode(json_encode($bills), true);
                $billsPerType = $this->groupByValue($bills, 'TIPO_CONTA');
                foreach ($billsPerType as $billPerType) {
                    foreach ($bills as $bill) {
                        if ($billPerType->TIPO_CONTA == $bill['TIPO_CONTA']) {
                            $billPerType->DESCRICAO =  $bill['IDENTIF_CONTA'] . " - " . $bill['TIPO_CONTA_DESCRICAO'];
                            $billPerType->TIPO_CONTA_DESCRICAO = $bill['TIPO_CONTA_DESCRICAO'];
                        }
                    }
                }
                $bills = $billsPerType;
            }

            $bills = json_encode($bills);
            return response()->json($bills)->header('Content-Type', 'application/json');
        } else
            return response()->json(['error' => true, 'errorMessage' => 'Ocorreu um erro ao processar a solicitação.']);
    }

    public function getBillsData()
    {
        $userId = Auth::user()->id;
        $bills = DB::table('bills')
            ->select('bills.id', 'bills.TIPO_CONTA', 'bills.DESCRICAO', 'bills.VALOR', 'bills.VENCIMENTO', 'bills.PARCELAS', 'bills.STATUS', 'bills.RECRIAR', 'identifier.DESCRICAO AS TIPO_CONTA_DESCRICAO', 'identifier.IDENTIF AS IDENTIF_CONTA', 'identifier.ID_HEX as ID_HEX')
            ->join('identifier', 'bills.TIPO_CONTA', '=', 'identifier.ID')
            ->where('identifier.dump', ' ')
            ->where('bills.id_usr', $userId)
            ->where('bills.STATUS', 'Pagar')
            ->where('bills.dump', ' ')
            ->get();

        $bills = json_decode(json_encode($bills), true);
        $billsPerType = $this->groupByValue($bills, 'TIPO_CONTA');
        foreach ($billsPerType as $billPerType) {
            foreach ($bills as $bill) {
                if ($billPerType->TIPO_CONTA == $bill['TIPO_CONTA']) {
                    $billPerType->DESCRICAO =  $bill['IDENTIF_CONTA'] . " - " . $bill['TIPO_CONTA_DESCRICAO'];
                    $billPerType->TIPO_CONTA_DESCRICAO = $bill['TIPO_CONTA_DESCRICAO'];
                }
            }
        }

        $identifiers = $this->objIdf
            ->select('id', 'DESCRICAO', 'ATIVO')
            ->where('id_usr', $userId)
            ->where('dump', ' ')
            ->get();

        $bills = json_encode($bills);
        $billsPerType = json_encode($billsPerType);
        $identifiers = json_encode($identifiers);
        if ($bills  && $billsPerType && $identifiers)
            return response()->json(['bills' => $bills, 'billsPerType' => $billsPerType, 'identifiers' => $identifiers])->header('Content-Type', 'application/json');
        else
            return response()->json(['error' => true, 'errorMessage' => 'Ocorreu ao recuperar os dados']);
    }
}
