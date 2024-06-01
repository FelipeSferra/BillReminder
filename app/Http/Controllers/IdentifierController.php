<?php

namespace App\Http\Controllers;

use App\Models\BillModel;
use App\Models\IdentifierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdentifierController extends Controller
{
    private $objIdf;
    private $objBll;

    public function __construct()
    {
        $this->objIdf = new IdentifierModel();
        $this->objBll = new BillModel();
    }

    // Retorna a view principal
    public function index()
    {
        return view('identifier.main');
    }

    // cadastra no banco com os dados
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $data = $this->objIdf->create([
            'ID_USR' => $userId,
            'IDENTIF' => $request->identif,
            'DESCRICAO' => $request->descricao,
            'ID_HEX' => $request->id_hex,
            'ATIVO' => $request->ativo,
        ]);

        if ($data)
            return response()->json(['message' => 'O identificador foi criado com sucesso!']);
        else
            return response()->json(['error' => true, 'errorMessage' => 'Erro ao criar o identificador, tente novamente!']);
    }

    public function edit(string $id)
    {
        $userId = Auth::user()->id;
        $identifier = $this->objIdf->select('id', 'IDENTIF', 'DESCRICAO', 'ATIVO', 'ID_HEX')->where('id_usr', $userId)
            ->where('id', $id)
            ->where('dump', ' ')
            ->first();

        if (!$identifier) {
            return response()->json(['error' => true, 'errorMessage' => 'O identificador não foi encontrado, tente novamente.']);
        }

        return response()->json($identifier);
    }

    // faz o update na tabela
    public function update(Request $request, string $id)
    {
        $userId = Auth::user()->id;
        $data = $this->objIdf->where('id', $id)
            ->where('id_usr', $userId)
            ->where('dump', ' ')->update([
                'IDENTIF' => $request->identifEdt,
                'DESCRICAO' => $request->descricaoEdt,
                'ID_HEX' => $request->id_hexEdt,
                'ATIVO' => $request->ativoEdt
            ]);

        if ($data)
            return response()->json(['message' => 'O identificador foi editado com sucesso!']);
        else
            return response()->json(['error' => true, 'errorMessage' => 'Erro ao editar o identificador, tente novamente!']);
    }

    // excluir da tabela (logicamente)
    public function destroy(string $id)
    {
        $userId = Auth::user()->id;
        $exists = $this->objBll->select('*')->where('id_usr', $userId)->where('dump', ' ')->where('tipo_conta', $id)->count();
        if ($exists > 0) {
            return response()->json(['error' => true, 'errorMessage' => 'Não é possível excluir o item, pois ele está sendo utilizado.Neste caso você deve desabilitar o item.']);
        } else {
            $this->objIdf->where('id', $id)
                ->where('ID_USR', $userId)
                ->where('dump', ' ')
                ->update(['dump' => '*']);
            return response()->json(['message' => 'O identificador foi excluído com sucesso!']);
        }
    }

    public function getIdentifiersData()
    {
        $userId = Auth::user()->id;
        $identifiers = $this->objIdf
            ->select('id', 'IDENTIF', 'DESCRICAO', 'ATIVO', 'ID_HEX')
            ->where('id_usr', $userId)
            ->where('dump', ' ')
            ->get();

        $identifiers = json_encode($identifiers);

        if ($identifiers)
            return response()->json(['identifiers' => $identifiers])->header('Content-Type', 'application/json');
        else
            return response()->json(['error' => true, 'errorMessage' => 'Ocorreu um erro ao recuperar os dados']);
    }
}
