<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers as Ctr;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    //return view('/auth/login');
    return redirect('/login');
});

Route::get('logout', [Ctr\Auth\LoginController::class, 'logout']);

Route::get('registrar', [Ctr\Auth\LoginController::class, 'registrar'])->name('registrar');

Route::get('inicio', [Ctr\HomeController::class, 'inicio'])->name('inicio')->middleware('auth'); //layouts/without-menu

Route::get('operadores', [Ctr\OperadoresController::class, 'operadores'])->name('operadores')->middleware('auth'); //layouts/without-menu

Route::any('encuentra-operadores', [Ctr\OperadoresController::class, 'getRowOperadores'])->name('encuentra-operadores')->middleware('auth');

Route::any('detalle-operadores/{action?}/{id?}', [Ctr\OperadoresController::class, 'actions'])->name('detalle-operadores')->middleware('auth');

Route::any('nuevo-operador/{action?}/{id?}', [Ctr\OperadoresController::class, 'new'])->name('nuevo-operador')->middleware('auth');

Route::any('save-operador', [Ctr\OperadoresController::class, 'saveOperador'])->name('save-operador')->middleware('auth');
