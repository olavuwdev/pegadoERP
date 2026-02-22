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

    Route::get('/produto', [ProdutoController::class, 'index'])->name('produto.index');
    Route::get('/produto/dados', [ProdutoController::class, 'dados'])->name('produto.dados');
    Route::get('/produto/novo', [ProdutoController::class, 'create'])->name('produto.create');
    Route::get('/produto/{produto}/editar', [ProdutoController::class, 'edit'])->name('produto.edit');
    Route::post('/produto', [ProdutoController::class, 'store'])->name('produto.store');
    Route::put('/produto/{produto}', [ProdutoController::class, 'update'])->name('produto.update');

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
