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
        $userId = Auth::user()->id;
        $identifiers = $this->objIdf->select('*')->where('id_usr', $userId)
            ->where('dump', ' ')
            ->get();
        return view('identifier.main', compact('identifiers'));
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
        $identifier = $this->objIdf->select('*')->where('id_usr', $userId)
            ->where('id', $id)
            ->where('dump', ' ')
            ->first();

        if (!$identifier) {
            return response()->json(['notFound' => true]);
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
            return response()->json(['exists' => true]);
        } else {
            $this->objIdf->where('id', $id)
                ->where('dump', ' ')
                ->update(['dump' => '*']);
            return response()->json(['message' => 'O identificador foi excluído com sucesso!']);
        }
    }

    public function getList()
    {
        $userId = Auth::user()->id;
        $identifiers = $this->objIdf->select('*')->where('id_usr', $userId)
            ->where('dump', ' ')
            ->get();

        $identifiers = json_encode($identifiers);
        return response()->json($identifiers)->header('Content-Type', 'application/json');
    }
}
