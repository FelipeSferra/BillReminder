<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required']
            ], [
                'email.required' => 'O campo de e-mail precisa ser preenchido.',
                'email.email' => 'Insira um e-mail válido.',
                'password.required' => 'O campo de senha precisa ser preenchido.'
            ]);

            $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];

            $authenticated = Auth::attempt($credentials, $request->input('remember'));

            if (!$authenticated) {
                flash()->addError('E-mail ou senha incorretos.', 'Erro de autenticação');
                return back();
            }

            return redirect()->route('menu');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            foreach ($errors as $error) {
                flash()->addError($error, 'Erro');
            }
            return back();
        }
    }

    public function destroy()
    {
        Session::flush();

        Auth::logout();

        return redirect()->route('login.index');
    }
}
