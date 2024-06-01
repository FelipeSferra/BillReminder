@extends('layouts.body')

@section('title')
    BillReminder
@endsection

@section('content')
    <div class="container-md">
        <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
            <div class="mx-auto text-center form-box col-md-4">
                <div class="tab-pane fade show active" id="sign-up-tab">
                    <div class="pt-3 px-3 text-center mb-2">
                        <p class="h6">Insira seus dados para a modificação da senha.</p>
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    </div>
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="p-3 form-group">
                            <div class="mt-3 row">
                                <div class="mt-3 input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-envelope fa-sm"></i></span>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="E-mail" value="{{ app('request')->input('email') }}" readonly>
                                </div>
                                <div class="mt-3 input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-key fa-sm"></i></span>
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Senha">
                                </div>
                                <div class="mt-3 input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-key fa-sm"></i></span>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" placeholder="Confirme a senha" oninput="checkPasswordMatch()">
                                </div>
                            </div>
                            <div class="mt-3 row text-start">
                                <div id="password-match"></div>
                            </div>
                            <div class="mt-3 row">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-custom btn-primary">Modificar Senha</button>
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
    <script src="{{ url('assets/js/auth/reset-password/functions.js') }}"></script>
@endsection
