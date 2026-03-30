<?php

namespace App\Http\Controllers;

use App\Models\ErpMarca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function listar(Request $request)
    {
        $q = trim($request->input('q', ''));
        $marcas = ErpMarca::ativo()
            ->when($q, fn($query) => $query->where('nome', 'like', "%{$q}%"))
            ->orderBy('nome')
            ->limit(50)
            ->get(['id', 'nome as text']);

        return response()->json($marcas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:150', 'unique:erp_marcas,nome'],
        ]);

        $marca = ErpMarca::create($validated);

        return response()->json([
            'id' => $marca->id,
            'nome' => $marca->nome,
            'message' => 'Marca criada com sucesso!',
        ], 201);
    }
}
