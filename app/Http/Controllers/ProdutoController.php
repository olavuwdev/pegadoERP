<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(){
        $produtos = [
            [
                'id' => 1,
                'nome' => 'Alternador 90A Bosch',
                'referencia' => 'ALT-90A-BOS',
                'estoque' => 12,
                'valor' => 550.00
            ],
            [
                'id' => 2,
                'nome' => 'Motor de Partida Valeo',
                'referencia' => 'MOT-PART-VALE',
                'estoque' => 7,
                'valor' => 680.00
            ],
            [
                'id' => 3,
                'nome' => 'Bateria 60Ah Moura',
                'referencia' => 'BAT-60A-MOUR',
                'estoque' => 20,
                'valor' => 420.00
            ],
            [
                'id' => 4,
                'nome' => 'Relé Automotivo 12V',
                'referencia' => 'REL-12V-AUT',
                'estoque' => 50,
                'valor' => 18.90
            ],
            [
                'id' => 5,
                'nome' => 'Lâmpada H7 Philips',
                'referencia' => 'LAMP-H7-PHIL',
                'estoque' => 35,
                'valor' => 39.90
            ],
        ];

        return view('pages.produto.index', compact('produtos'));
    }

    public function form(){
        return view('pages.produto.form');
    }
}
