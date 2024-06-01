@extends('layouts.body')

@section('title')
    BillReminder
@endsection

@section('content')
    <div class="container-md">
        <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
            <div class="mx-auto text-center form-box col-md-4">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="sign-in-tab">
                        <div class="pt-3 px-3 text-start mb-2">
                            <p>Esqueceu sua senha? Sem problemas. Apenas informe seu endereço de e-mail que enviaremos um
                                link que permitirá definir uma nova senha.</p>
                        </div>

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="col-md-12 p-3 form-group">
                                <div class="mt-2 row">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-regular fa-envelope fa-sm"></i></span>
                                        <input type="email" id="email" name="email" class="form-control"
                                            placeholder="E-mail">
                                    </div>
                                </div>
                                <div class="mt-3 row text-start">
                                    @error('email')
                                        <p class="text-danger">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                    <div class="col-md-12 text-end">
                                        <button type="submit" class="btn btn-custom btn-primary">Enviar</button>
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
