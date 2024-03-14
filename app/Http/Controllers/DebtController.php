<?php

namespace App\Http\Controllers;

use App\Models\DebtModel;
use App\Models\IdentifierModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    private $objDbt;
    private $objIdf;

    public function __construct()
    {
        $this->objDbt = new DebtModel();
        $this->objIdf = new IdentifierModel();
    }

    public function index()
    {
        $userId = Auth::user()->id;

        $debts = $this->objDbt->select('*')->where('ID_USR', $userId)
            ->where('STATUS', 'Pagar')
            ->where('dump', ' ')
            ->get();

        $identifiers = $this->objIdf
            ->select('*')
            ->where('id_usr', $userId)
            ->where('dump', ' ')
            ->get();

        foreach ($debts as $debt) {
            $dataDoBanco = Carbon::parse($debt->VENCIMENTO);

            $debt->data_formatada = $dataDoBanco->format('d/m/Y');
        }

        $identifiersMap = $identifiers->pluck('DESCRICAO', 'id')->toArray();

        return view('debt.main', compact('debts','identifiers','identifiersMap'));
    }
}
