<?php

use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\FornecedorController;
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
Route::get('/', function () {
    return 'Laravel funcionando!';
});

Route::middleware('auth')->group(function () {

    // ── Produto ──
    Route::get('/produto', [ProdutoController::class, 'index'])->name('produto.index');
    Route::get('/produto/dados', [ProdutoController::class, 'dados'])->name('produto.dados');
    Route::get('/produto/novo', [ProdutoController::class, 'create'])->name('produto.create');
    Route::get('/produto/{produto}/editar', [ProdutoController::class, 'edit'])->name('produto.edit');
    Route::post('/produto', [ProdutoController::class, 'store'])->name('produto.store');
    Route::put('/produto/{produto}', [ProdutoController::class, 'update'])->name('produto.update');

    //Rotas de clientes, fornecedor, transportador
    Route::get('clientes/dados', [ClienteController::class, 'datatable'])->name('clientes.datatable');
    Route::resource('clientes', 'App\Http\Controllers\ClienteController');
    Route::resource('transportadores', 'App\Http\Controllers\TransportadorController');

    // ── API Auxiliares (AJAX inline) ──
    Route::prefix('api')->group(function () {
        Route::get('/cep/{cep}', 'App\Http\Controllers\Api\CepController@buscarCep');
        Route::post('/cpf-cnpj/', 'App\Http\Controllers\ClienteController@verificaCpfCnpj');

        // Categorias
        Route::get('/categorias', [CategoriaController::class, 'listar'])->name('api.categorias.listar');
        Route::post('/categorias', [CategoriaController::class, 'store'])->name('api.categorias.store');

        // Marcas
        Route::get('/marcas', [MarcaController::class, 'listar'])->name('api.marcas.listar');
        Route::post('/marcas', [MarcaController::class, 'store'])->name('api.marcas.store');

        // Fornecedores (busca para Select2)
        Route::get('/fornecedores/buscar', [FornecedorController::class, 'buscar'])->name('api.fornecedores.buscar');
        Route::post('/fornecedores', [FornecedorController::class, 'storeInline'])->name('api.fornecedores.store');
    });
});
