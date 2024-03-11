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

/*         $hashed_password = User::where('email', $request->input('email'))->first()->password;
        $authHash = Hash::check($request->input('password'),$hashed_password);
        dd($request, Hash::make($request->input('password')), $hashed_password, Hash::make($request->input('password')),$authHash);
 */
        $authenticated = Auth::attempt($credentials, $request->input('remember'));

        if (!$authenticated) {
            return back()->withErrors(['message' => 'E-mail/senha incorretos.']);
        }

        return redirect()->route('menu');
    }

    public function destroy()
    {
        Session::flush();

        Auth::logout();

        return redirect()->route('login.index');
    }
}
