<?php

namespace App\Http\Controllers;

use App\Models\BillModel;
use App\Models\IdentifierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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
        $userId = Auth::user()->id;
        $bills = $this->objBll->select('*')->where('id_usr', $userId)
            ->where('STATUS', 'Pagar')
            ->where('dump', ' ')
            ->get();

        $identifiers = $this->objIdf
            ->select('*')
            ->where('id_usr', $userId)
            ->where('dump', ' ')
            ->get();

        foreach ($bills as $bill) {
            $dataDoBanco = Carbon::parse($bill->VENCIMENTO);

            $bill->data_formatada = $dataDoBanco->format('d/m/Y');
        }

        $billsPerType = $this->groupByValue($bills, 'TIPO_CONTA');

        foreach ($billsPerType as $bill) {
            foreach ($identifiers as $identifier) {
                if ($bill->TIPO_CONTA == $identifier->id) {
                    $bill->DESCRICAO =  $identifier->IDENTIF . " - " . $identifier->DESCRICAO;
                }
            }
        }

        $identifiersMap = $identifiers->pluck('DESCRICAO', 'id')->toArray();

        return view('bill.main', compact('bills', 'billsPerType', 'identifiers', 'identifiersMap'));
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
        $bills = $this->objBll->select('*')->where('id_usr', $userId)
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
        $userId = Auth::user()->id;
        $data = $this->objBll->where('id', $id)->where('id_usr', $userId)
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
            $userId = Auth::user()->id;

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

            $this->objBll->where('id_usr', $userId)
                ->where('id', $id)
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
            $bills = $this->objBll->select('*')->where('id_usr', $userId)
                ->where('STATUS', $status)
                ->where('dump', ' ')
                ->get();
        } else {
            $bills = $this->objBll->select('*')->where('id_usr', $userId)
                ->where('dump', ' ')
                ->get();
        }
        if ($bills) {
            foreach ($bills as $bill) {
                $dataDoBanco = Carbon::parse($bill->VENCIMENTO);

                $bill->data_formatada = $dataDoBanco->format('d/m/Y');
            }

            if ($tipo == "Simplificado") {
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
                            $bill->DESCRICAO = $identifier->IDENTIF . " - " . $identifier->DESCRICAO;
                        }
                    }
                }
            }

            $bills = json_encode($bills);
            return response()->json($bills)->header('Content-Type', 'application/json');
        } else
            return response()->json(['error' => true, 'errorMessage' => 'Ocorreu um erro ao processar a solicitação.']);
    }
}
