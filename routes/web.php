<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\IdentifierController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentsMethodsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/logout', [LoginController::class, 'destroy'])->middleware(['auth'])->name('login.destroy');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login.index');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    //Rota para menu
    Route::get('/', function () {
        return view('menu.main');
    })->name('menu');

    //Rotas para identificadores
    Route::get('/identifier', [IdentifierController::class, 'index'])->name('identifier.index');
    Route::get('/identifier/{id}/edit', [IdentifierController::class, 'edit'])->name('identifier.edit');
    Route::get('/identifier/data', [IdentifierController::class, 'getIdentifiersData'])->name('identifier.data');
    Route::post('/identifier/create', [IdentifierController::class, 'store'])->name('identifier.create');
    Route::put('/identifier/{id}', [IdentifierController::class, 'update'])->name('identifier.update');
    Route::delete('/identifier/{id}', [IdentifierController::class, 'destroy'])->name('identifier.destroy');

    //Rotas para devedores
    Route::get('/debt', [DebtController::class, 'index'])->name('debt.index');
    Route::get('debt/{id}/edit', [DebtController::class, 'edit'])->name('debt.edit');
    Route::get('/debt/data', [DebtController::class, 'getDebtData'])->name('debt.data');
    Route::post('/debt/create', [DebtController::class, 'store'])->name('debt.create');
    Route::put('/debt/{id}', [DebtController::class, 'update'])->name('debt.update');
    Route::delete('/debt/{id}', [DebtController::class, 'destroy'])->name('debt.destroy');

    //Rotas para contas
    Route::get('/bills', [BillController::class, 'index'])->name('bill.index');
    Route::get('/bills/{id}/edit', [BillController::class, 'edit'])->name('bill.edit');
    Route::get('/bills/data', [BillController::class, 'getBillsData'])->name('bill.data');
    Route::post('/bills/create', [BillController::class, 'store'])->name('bill.create');
    Route::post('/bills/{id}', [BillController::class, 'concluded'])->name('bill.concluded');
    Route::post('/filter/{status}/{tipo}', [BillController::class, 'filter'])->name('bill.filter');
    Route::put('/bills/{id}', [BillController::class, 'update'])->name('bill.update');
    Route::delete('/bills/{id}', [BillController::class, 'destroy'])->name('bill.destroy');

    //Rotas para usuário
    Route::get('/user', [UserController::class, 'index'])->name('user.config');
    Route::get('/user/data', [UserController::class, 'getUserData'])->name('user.data');
    Route::post('/changePassword/{id}', [UserController::class, 'changePassword'])->name('user.change-pass');
    Route::put('/changeInfo/{id}', [UserController::class, 'changeUserInfo'])->name('user.change-info');
    Route::put('/notifyUser/{id}', [UserController::class, 'turnNotification'])->name('user.notify');

    //Rotas para dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');

    //Rotas para formas de pagamento
    Route::get('/paymentsMethods', [PaymentsMethodsController::class, 'index'])->name('paymentsMethods.index');
});
