@extends('layouts.nav')

@section('title')
    BillReminder - Devedores
@endsection

@section('style')
    <style>
        .container {
            margin-top: 5rem;
        }

        .div-inner {
            box-sizing: border-box;
            padding: 4px 8px 5px 8px;
            overflow: hidden;
            text-overflow: ellipsis;
            border-radius: 16px;
            height: 24px;
            align-items: center;
            white-space: nowrap;
            margin: 4px 4px 4px 4px;
        }
    </style>
@endsection

@section('content')
    @include('layouts.loading')
    <div class="container">

        <div class="row">
            <div class="d-flex align-items-center justify-content-between">
                <div class="p-2">
                    <p class="fs-5">Devedores</p>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-dark mt-2" data-bs-toggle="modal"
                        data-bs-target="#ModalCreate"><i class="fa-solid fa-plus"></i>
                        Novo
                    </button>
                    <button type="button" id="btnEdit" class="btn btn-outline-primary mt-2" data-bs-toggle="modal"
                        data-bs-target="#ModalEdit" disabled><i class="fa-solid fa-pen"></i>
                        Editar
                    </button>
                    <button type="button" id="btnDelete" class="btn btn-outline-danger mt-2" disabled><i
                            class="fa-solid fa-trash"></i>
                        Excluir
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mt-3 table-responsive-sm">
                <div class="card">
                    <div class="card-body">
                        <table class="table" id="table-debt">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Ativo</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('modal.debt.create')
        @include('modal.debt.edit')
    </div>
@endsection

@section('script')
    <script src="{{ url('assets/js/debt/actions.js') }}?v={{rand(1,10000)}}"></script>
    <script src="{{ url('assets/js/debt/tableDebt.js') }}?v={{rand(1,10000)}}"></script>
@endsection
