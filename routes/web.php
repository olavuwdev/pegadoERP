<?php

use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home.index');
});
Route::get('/produto', [ProdutoController::class, 'index']);
Route::get('/produto/form', [ProdutoController::class, 'form']);

//Rotas de clientes, fornecedor, transportador
Route::resource('clientes', 'App\Http\Controllers\ClienteController');
Route::resource('clientes', 'App\Http\Controllers\ClienteController');
Route::resource('fornecedores', 'App\Http\Controllers\FornecedorController');
Route::resource('transportadores', 'App\Http\Controllers\TransportadorController');

