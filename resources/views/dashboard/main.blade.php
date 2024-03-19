@extends('layouts.nav')

@section('title')
    BillReminder - Dashboard
@endsection

@section('style')
    <style>
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

        .tableBills {
            -webkit-border-radius: 6px 6px 6px 6px;
            border-radius: 6px 6px 6px 6px;
            background-color: #e2e4e7;
            -webkit-box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
        }
    </style>
@endsection

@section('content')
    <div class="header-dashboard container-fluid">
        <div class="row h-100 p-5">
            <div class="col-md-4 align-self-center mt-2">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8 mt-3">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Contas à vencer nesse mês</p>
                                <h5 class="font-weight-bolder" id="billsDue"></h5>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="fa-thin fa-book-skull fa-2xl"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 align-self-center mt-2">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8 mt-3">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Valor pago</p>
                                <h5 class="font-weight-bolder" id="monthlyExpenses"></h5>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="fa-thin fa-sack-dollar fa-2xl"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 align-self-center mt-2">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8 mt-3">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Contas em aberto</p>
                                <h5 class="font-weight-bolder" id="openBills"></h5>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="fa-thin fa-file-invoice-dollar fa-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="mt-3 col-md-6 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold text-primary">Histórico de gastos</h6>
                        <div id="hasNothing" style="display: none;"></div>
                        <canvas id="allBills-dashboard" style="max-width: 100%;height: 300px; max-height:100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="mt-3 col-md-6 mb-3 text-center ">
                <div class="card">
                    <div class="card-body ">
                        <h6 class="card-title font-weight-bold text-primary">Contas pagas</h6>
                        <canvas id="monthBills-dashboard" style="max-width: 100%;height: 300px; max-height:100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="mt-4 col-md-6 text-center ">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold text-primary">Contas mais caras do mês</h6>
                        <table class="table " id="topBillsTable">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4 col-md-6 text-center ">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title font-weight-bold text-primary">Contas que vão vencer</h6>
                        <table class="table " id="dueDateTable">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Vencimento</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{url('assets/js/dashboard/general.js')}}"></script>
<script src="{{url('assets/js/dashboard/charts/allChart.js')}}"></script>
<script src="{{url('assets/js/dashboard/charts/monthlyChart.js')}}"></script>
<script src="{{url('assets/js/dashboard/tables/dueDateTable.js')}}"></script>
<script src="{{url('assets/js/dashboard/tables/topBillsTable.js')}}"></script>
    {{-- @include('script.dashboard.general')
    @include('script.dashboard.charts.allChart')
    @include('script.dashboard.charts.monthlyChart')
    @include('script.dashboard.tables.dueDateTable')
    @include('script.dashboard.tables.topBillsTable') --}}
@endsection
