@extends('layouts.body')

@section('title')
    BillReminder
@endsection

@section('style')
    <style>
        .lb-pass {
            user-select: none;
        }

        .nav-link {
            color: #000;
            border: none;
            border-bottom: 1px solid transparent;
            transition: border-bottom 0.3s ease;
            text-decoration: none;
        }

        .nav-link.active {
            font-weight: bold;
            border-bottom: 1px solid #160925 !important;
            background-color: #e2e4e7 !important;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: #000;
            border: none;
            background-color: #e2e4e7 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-md">
        <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
            <div class="mx-auto text-center form-box col-md-4">
                <div class="py-3 mb-3 text-center col-md-12">
                    <div class="logo-container">
                        @include('logo.svg', ['width' => 300])
                    </div>
                    <ul class="mt-5 nav nav-tabs justify-content-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="sign-in-tab-btn" data-bs-toggle="tab" href="#sign-in-tab"
                                role="tab" aria-controls="sign-in-tab" aria-selected="true">Entrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sign-up-tab-btn" data-bs-toggle="tab" href="#sign-up-tab" role="tab"
                                aria-controls="sign-up-tab" aria-selected="false">Criar minha conta</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="sign-in-tab">
                        @if (session('status'))
                            <p class="mt-3 text-success">
                                {{ session('status') }}
                            </p>
                        @endif
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                        <form method="POST" action="{{ route('login.store') }}">
                            @csrf
                            <div class="p-3 form-group">
                                <div class="mt-3 row">
                                    <div class="mt-3 input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-envelope fa-sm"></i></span>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="E-mail">
                                    </div>
                                    <div class="mt-3 input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-key fa-sm"></i></span>
                                        <input type="password" id="password" name="password" class="form-control"
                                            placeholder="Senha">
                                    </div>
                                </div>
                                <div class="mt-3 row">
                                    <div class="col-md-12 mt-2 text-start">
                                        <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember" value="1">
                                        <label for="remember">Mantenha-me conectado</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 row">
                                    <div class="col-md-6 mt-2 text-start">
                                        <a href="{{ route('password.request') }}" class="link-underline-dark"
                                            style="color:#000;">Esqueceu sua senha?</a>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="submit" class="btn btn-custom btn-primary">Acessar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="sign-up-tab">
                        <form method="POST" action="{{ route('register.store') }}">
                            @csrf
                            <div class="p-3 form-group">
                                <div class="mt-3 row">
                                    <div class="mt-3 input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-user fa-sm"></i></span>
                                        <input type="text" id="name" name="name" class="form-control"
                                            placeholder="Nome">
                                    </div>
                                    <div class="mt-3 input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-envelope fa-sm"></i></span>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="E-mail">
                                    </div>
                                    <div class="mt-3 input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-key fa-sm"></i></span>
                                        <input type="password" id="password-signup" name="password" class="form-control"
                                            placeholder="Senha" oninput="checkPasswordMatch()">
                                    </div>
                                    <div class="mt-3 input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-key fa-sm"></i></span>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" placeholder="Confirme a senha"
                                            oninput="checkPasswordMatch()">
                                    </div>
                                </div>
                                <div class="mt-3 row text-start">
                                    <div id="password-match"></div>
                                </div>
                                <div class="mt-3 row">
                                    <div class="col-md-12 text-end">
                                        <button type="submit" class="btn btn-custom btn-primary">Criar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('script.auth.login.script')
@endsection
