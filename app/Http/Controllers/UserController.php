<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        $userInfo = Auth::user();
        return view('user.configuration', compact('userInfo'));
    }

    public function turnNotification(Request $request, string $id)
    {
        $userInfo = Auth::user();

        //notificacao de gasto
        if ($request->notifGasto == "S") {
            $userUpdateGasto = $this->user->where('id', $id)
                ->where('dump', '')->update([
                    'NOTIFICAR_GASTO' => $request->notifGasto,
                    'TIPO_NOTIF_GASTO' => $request->emailGastos,
                    'EMAIL_GASTO' => Carbon::now()->toDateString()
                ]);
            if (!$userUpdateGasto)
                return redirect()->back()->withErrors("Erro ao ativar as notificações de gasto, tente novamente!");
        } else {
            if ($userInfo->NOTIFICAR_GASTO != "N") {
                $userUpdateGasto = $this->user->where('id', $id)
                    ->where('dump', '')->update([
                        'NOTIFICAR_GASTO' => 'N',
                        'TIPO_NOTIF_GASTO' => NULL,
                        'EMAIL_GASTO' => NULL
                    ]);
                if (!$userUpdateGasto)
                    return redirect()->back()->withErrors('Erro ao desativar as notificações de gasto, tente novamente!');
            }
        }

        //noticacao vencimento
        if ($request->notifVenc == "S") {
            if ($userInfo->NOTIFICAR_VENC != "S" || $userInfo->VENC_DIAS != $request->emailVenc) {
                $userUpdateVenc = $this->user->where('id', $id)
                    ->where('dump', '')->update([
                        'NOTIFICAR_VENC' => $request->notifVenc,
                        'VENC_DIAS' => $request->emailVenc,
                    ]);

                if (!$userUpdateVenc)
                    return redirect()->back()->withErrors("Erro ao ativar as notificações de vencimento, tente novamente!");
            }
        } else {
            if ($userInfo->NOTIFICAR_VENC != "N") {
                $userUpdateVenc = $this->user->where('id', $id)
                    ->where('dump', '')->update([
                        'NOTIFICAR_VENC' => 'N',
                        'VENC_DIAS' => NULL
                    ]);
                if (!$userUpdateVenc)
                    return redirect()->back()->withErrors('Erro ao desativar as notificações de vencimento, tente novamente!');
            }
        }



        return redirect()->back()->withSuccess('As notificações foram ativadas com sucesso!');
    }

    public function changeUserInfo(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|max:20',
            'email' => 'required|email',
            'emailSec' => 'nullable|email'
        ], [
            'name.required' => 'Preencha o campo de nome corretamente!',
            'email.required' => 'Preencha o campo de E-mail corretamente!',
            'email.email' => 'Digite um email válido!',
            'emailSec.email' => 'Digite um email válido!'
        ]);

        if ($request->email == $request->emailSec)
            return response()->json(['error' => true, 'errorMessage' => 'O e-mail principal e o e-mail secundário não podem ser iguais!']);

        if (empty($request->emailSec))
            $data = ['name' => $request->name, 'email' => $request->email, 'EMAIL_SECUNDARIO' => ''];
        else
            $data = ['name' => $request->name, 'email' => $request->email, 'EMAIL_SECUNDARIO' => $request->emailSec];


        $user = $this->user->where('id', $id)
            ->where('dump', '')->update($data);
        if (!$user) {
            return response()->json(['error' => true]);
        }

        return response()->json(['message' => 'Dados do usuário alterados com sucesso!']);
    }

    public function changePassword(Request $request)
    {

        $this->validate($request, [
            'password' => 'required',
            'new_password' => 'required|confirmed'
        ], [
            'password.required' => 'O campo de senha atual é obrigatório',
            'new_password.required' => 'O campo de nova senha é obrigatório',
            'new_password.confirmed' => 'A nova senha precisa ser confirmada no campo: Confirme a nova senha',
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return redirect()->back()->withErrors('A senha atual está incorreta!');
        }



        if (strcmp($request->password, $request->new_password) === 0) {
            return redirect()->back()->withErrors('A nova senha não pode ser a mesma que a atual!');
        }

        User::findOrFail(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);


        return redirect()->back()->withSuccess('Senha alterada com sucesso!');
    }
}
