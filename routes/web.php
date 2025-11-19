<?php

use App\Events\NovoProdutoAdicionado;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home.index');
});

Route::get('/evento', function () {
    return view('pages.teste-evento.index');
});


Route::get('/testar-evento', function () {
    event(new NovoProdutoAdicionado([
        'nome' => 'Bateria 60Ah Moura',
        'valor' => 420,
    ]));

    return 'Evento enviado!';
});

