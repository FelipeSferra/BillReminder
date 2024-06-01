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
        try {
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
            Auth::login($user);
            flash()->addSuccess('A sua conta foi registrada com sucesso!', 'Sucesso');
            return redirect()->route('menu');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            foreach ($errors as $error) {
                flash()->addError($error, 'Erro');
            }
            return back();
        }
    }
}
