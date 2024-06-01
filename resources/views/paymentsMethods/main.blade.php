@extends('layouts.nav')

@section('title')
    BillReminder - Formas de pagamento
@endsection

@section('style')
    <style>
        .container {
            margin-top: 5rem;
        }

        .form-floating>.form-control-plaintext~label::after,
        .form-floating>.form-control:focus~label::after,
        .form-floating>.form-control:not(:placeholder-shown)~label::after,
        .form-floating>.form-select~label::after {
            position: relative;
        }
    </style>
@endsection

@section('content')
    @include('layouts.loading')
    <div class="container">
        <div class="row">
            <div class="d-flex align-items-center justify-content-between">
                <div class="p-2">
                    <p class="fs-5">Formas de pagamento</p>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-dark mt-2" data-bs-toggle="modal"
                        data-bs-target="#ModalCreate">
                        <i class="fa-solid fa-plus"></i> Novo
                    </button>
                    <button type="button" id="btnEdit" class="btn btn-outline-primary mt-2" data-bs-toggle="modal"
                        data-bs-target="#ModalEdit" disabled>
                        <i class="fa-solid fa-pen"></i> Editar
                    </button>
                    <button type="button" id="btnDelete" class="btn btn-outline-danger mt-2" disabled>
                        <i class="fa-solid fa-trash"></i> Excluir
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mt-3 table-responsive-sm">
                <div class="card">
                    <div class="card-body">
                        <table class="table" id="table-identifiers">
                            <thead>
                                <tr>
                                    <th>Identif.</th>
                                    <th>Descrição</th>
                                    <th>Ativo</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modal.identifier.create')
    @include('modal.identifier.edit')
@endsection

@section('script')
    <script src="{{ url('assets/js/identifier/actions.js') }}?v={{ rand(1, 1000) }}"></script>
    <script src="{{ url('assets/js/identifier/table-identifiers.js') }}?v={{ rand(1, 1000) }}"></script>
@endsection
