<?php

namespace App\Http\Controllers;

use App\Models\ErpCategoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function listar(Request $request)
    {
        $q = trim($request->input('q', ''));
        $categorias = ErpCategoria::ativo()
            ->when($q, fn($query) => $query->where('nome', 'like', "%{$q}%"))
            ->orderBy('nome')
            ->limit(50)
            ->get(['id', 'nome as text']);

        return response()->json($categorias);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:150', 'unique:erp_categorias,nome'],
            'descricao' => ['nullable', 'string'],
        ]);

        $categoria = ErpCategoria::create($validated);

        return response()->json([
            'id' => $categoria->id,
            'nome' => $categoria->nome,
            'message' => 'Categoria criada com sucesso!',
        ], 201);
    }
}
