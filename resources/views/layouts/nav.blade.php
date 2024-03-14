@extends('layouts.body')

@section('navbar')
    <nav class="navbar bg-light fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar">
                <span><i class="fa-solid fa-bars"></i></span>
            </button>

            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">@include('logo.svg', ['width' => 250])</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('menu') }}">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.index') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bill.index') }}">Contas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('debt.index') }}">Devedores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('identifier.index') }}">Identificadores</a>
                        </li>
                    </ul>
                </div>
            </div>

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
@endsection

@section('script_general')
    @include('script.general.script')
@endsection
