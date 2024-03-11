@extends('layouts.body')

@section('title')
    BillReminder - Menu
@endsection
@section('style')
    <style>
        .gradient-card {
            background: linear-gradient(to bottom, #28124b, #160925);
        }

        .card {
            border-radius: 10px 10px 10px 10px;
        }

        .card-icon {
            color: #fff;
            margin-top: 2rem;
        }

        .card-title {
            color: #fff;
            font-size: 1.5rem;
            margin-top: 5rem;
        }

        .container {
            margin-top: 5rem;
        }

        a {
            text-decoration: none;
        }

        .card:hover {
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }
    </style>
@endsection
@section('content')
    <nav class="navbar bg-light fixed-top">
        <div class="container-fluid justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end position-absolute">
                        <li><a class="dropdown-item" href="{{ route('user.config') }}">Configurações</a></li>
                        <li><a class="dropdown-item" href="{{ route('login.destroy') }}">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="mt-3 col-md-4">
                <div class="text-center card gradient-card">
                    <a href="{{ route('dashboard.index') }}">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <i class="fa-solid fa-chart-line fa-3x card-icon"></i>
                            </div>
                            <p class="card-title">Dashboard</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="mt-3 col-md-4">
                <div class="text-center card gradient-card">
                    <a href="{{ route('bill.index') }}">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <i class="fa-solid fa-money-bills fa-3x card-icon"></i>
                            </div>
                            <p class="card-title">Contas</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="mt-3 col-md-4">
                <div class="text-center card gradient-card">
                    <a href="{{ route('debt.index') }}">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <i class="fa-solid fa-person-to-door fa-3x card-icon"></i>
                            </div>
                            <p class="card-title">Devedores</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="mt-3 col-md-4">
                <div class="text-center card gradient-card">
                    <a href="{{ route('identifier.index') }}">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <i class="fa-solid fa-pen fa-3x card-icon"></i>
                            </div>
                            <p class="card-title">Identificadores</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('script.general.script')
@endsection
