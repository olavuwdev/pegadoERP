<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return view('pages.clientes.index');
    }
    public function create(){
        return view('pages.clientes.form');
    }
}
