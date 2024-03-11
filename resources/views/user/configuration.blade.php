@extends('layouts.nav')

@section('title')
    BillReminder - Informações do Usuário
@endsection

@section('style')
    <style>
        .container {
            margin-top: 5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row gutters">
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-md-12 text-center">
                                <h5 class="text-primary">Informações do usuário</h5>
                            </div>
                            @include('forms.change-info')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-md-12 text-center">
                                <h5 class="text-primary mb-3">Alteração de senha</h5>
                            </div>
                            @include('forms.change-password')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gutters">
            <div class="d-flex justify-content-center">
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row gutters">
                                <div class="col-md-12 text-center">
                                    <h5 class="text-primary">Configurações de notificação</h5>
                                </div>
                                @include('forms.send-email')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('script.user.notifications')
    @include('script.user.userInfo')
@endsection
