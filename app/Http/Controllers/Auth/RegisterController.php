<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as RulesPassword;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:' . User::class],
            'password' => ['required', 'confirmed', RulesPassword::defaults()]
        ], [
            'email.unique' => 'Este email já está cadastrado.',
            'password.confirmed' => 'As duas senhas precisam estar iguais.'
        ]);

        $user = User::create([

            'name'     => $request->name,

            'email'    => $request->email,

            'password' => Hash::make($request->password),

        ]);

        /* $user->sendEmailVerificationNotification(); */

        $user->createToken('token')->accessToken;

        if (!$user) {
            return redirect()->back()->withErrors('messages');
        }
        Auth::login($user);

        return redirect()->route('menu')->with('success', 'A sua conta foi registrada com sucesso!');
    }
}
