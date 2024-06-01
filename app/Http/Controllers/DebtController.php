<?php

namespace App\Http\Controllers;

use App\Models\DebtModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    private $objDbt;

    public function __construct()
    {
        $this->objDbt = new DebtModel();
    }

    public function index()
    {
        return view('debt.main');
    }

    // cadastra no banco com os dados
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $data = $this->objDbt->create([
            'ID_USR' => $userId,
            'NOME' => $request->nome,
            'EMAIL' => $request->email,
            'ATIVO' => $request->ativo,
        ]);

        if ($data)
            return response()->json(['message' => 'O devedor foi cadastrado com sucesso!']);
        else
            return response()->json(['error' => true, 'errorMessage' => 'Erro ao cadastrar o devedor, tente novamente!']);
    }

    // envia os dados para preencher o formulario de edicao
    public function edit(string $id)
    {
        $userId = Auth::user()->id;
        $data = $this->objDbt->select('id', 'NOME', 'EMAIL', 'ATIVO')->where('id_usr', $userId)
            ->where('id', $id)
            ->where('dump', ' ')
            ->first();

        if (!$data) {
            return response()->json(['error' => true, 'errorMessage' => 'O devedor não foi encontrado, tente novamente.']);
        }

        return response()->json($data);
    }

    // faz o update na tabela
    public function update(Request $request, string $id)
    {
        $userId = Auth::user()->id;
        $data = $this->objDbt->where('id', $id)
            ->where('id_usr', $userId)
            ->where('dump', ' ')->update([
                'NOME' => $request->nomeEdt,
                'EMAIL' => $request->emailEdt,
                'ATIVO' => $request->ativoEdt
            ]);

        if ($data)
            return response()->json(['message' => 'O devedor foi editado com sucesso!']);
        else
            return response()->json(['error' => true, 'errorMessage' => 'Erro ao editar o devedor, tente novamente!']);
    }

    // excluir da tabela (logicamente)
    public function destroy(string $id)
    {
        $userId = Auth::user()->id;
        /*  $exists = $this->objBll->select('*')->where('id_usr', $userId)->where('dump', ' ')->where('tipo_conta', $id)->count();
        if ($exists > 0) {
            return response()->json(['error' => true, 'errorMessage' => 'Não é possível excluir o item, pois ele está sendo utilizado.Neste caso você deve desabilitar o item.']);
        } else { */
        $this->objDbt->where('id', $id)
            ->where('ID_USR', $userId)
            ->where('dump', ' ')
            ->update(['dump' => '*']);
        return response()->json(['message' => 'O devedor foi excluído com sucesso!']);
        //}
    }

    public function getDebtData()
    {
        $userId = Auth::user()->id;
        $debt = $this->objDbt
            ->select('id', 'NOME', 'EMAIL', 'ATIVO')
            ->where('id_usr', $userId)
            ->where('dump', ' ')
            ->get();

        $debt = json_encode($debt);

        if ($debt)
            return response()->json(['debt' => $debt])->header('Content-Type', 'application/json');
        else
            return response()->json(['error' => true, 'errorMessage' => 'Ocorreu um erro ao recuperar os dados']);
    }
}
