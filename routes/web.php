<?php

use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.attempt')
    ->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('pages.home.index');
    });
    Route::get('/produto', [ProdutoController::class, 'index']);
    Route::get('/produto/form', [ProdutoController::class, 'form']);
    Route::get('/teste-conexao', [ProdutoController::class, 'conexao']);

    //Rotas de clientes, fornecedor, transportador
    Route::get('clientes/dados', [ClienteController::class, 'datatable'])->name('clientes.datatable');
    Route::resource('clientes', 'App\Http\Controllers\ClienteController');
    Route::resource('fornecedores', 'App\Http\Controllers\FornecedorController');
    Route::resource('transportadores', 'App\Http\Controllers\TransportadorController');

    /* APIs */
    Route::prefix('api')->group(function () {
        Route::get('/cep/{cep}', 'App\Http\Controllers\Api\CepController@buscarCep');
        Route::post('/cpf-cnpj/', 'App\Http\Controllers\ClienteController@verificaCpfCnpj');
    });
});
