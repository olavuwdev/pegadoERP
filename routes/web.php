<?php

use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home.index');
});
Route::get('/produto', [ProdutoController::class, 'index']);
Route::get('/produto/form', [ProdutoController::class, 'form']);

