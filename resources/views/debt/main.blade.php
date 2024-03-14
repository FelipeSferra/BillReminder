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
    <div class="container">
        <div class="row">
            <div class="d-flex justify-content-end">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <label>
                                Status:
                            </label>
                            <select class="form-control mx-2" id="filtroStatus" name="filtroStatus">
                                <option value="Todos">Todos</option>
                                <option value="Pagar"selected>Pagar</option>
                                <option value="Pago">Pago</option>
                            </select>
                        </div>

                        <div class="col-md-6 text-end" id="btn-group">
                            <button type="button" id="btnNew" class="btn btn-outline-dark mt-4" data-bs-toggle="modal"
                                data-bs-target="#ModalCreate"><i class="fa-solid fa-plus"></i>
                                Novo
                            </button>
                            <button type="button" id="btnEdit" class="btn btn-outline-primary mt-4"
                                data-bs-toggle="modal" data-bs-target="#ModalEdit" disabled><i class="fa-solid fa-pen"></i>
                                Editar
                            </button>
                            <button type="button" id="btnConcluded" class="btn btn-outline-success mt-4" disabled><i
                                    class="fa-solid fa-check"></i>
                                Pagar
                            </button>
                            <button type="button" id="btnDelete" class="btn btn-outline-danger mt-4" disabled><i
                                    class="fa-solid fa-trash"></i>
                                Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="complete">
            <div class="mt-3 table-responsive-sm">
                <div class="card">
                    <div class="card-body">
                        <table class="table text-center" id="table-debts">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Descrição</th>
                                    <th>Nome</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Venc.</th>
                                    <th>Parcelas</th>
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
    @include('script.debt.actions')
    @include('script.debt.tableDebt')
@endsection
