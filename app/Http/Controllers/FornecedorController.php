<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index()
    {
        return view('pages.fornecedor.index');
    }

    public function buscar(Request $request)
    {
        $q = trim($request->input('q', ''));

        // tipo_pessoa: 'F' para Fornecedor (ou campo 'tipo' dependendo da estrutura)
        $fornecedores = Cliente::where(function ($query) {
            $query->where('tipo_pessoa', 'F')
                ->orWhere('tipo_pessoa', 'FORNECEDOR')
                ->orWhere('tipo', 'FORNECEDOR')
                ->orWhere('tipo', 'AMBOS');
        })
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('razao_social', 'like', "%{$q}%")
                    ->orWhere('nome_fantasia', 'like', "%{$q}%")
                    ->orWhere('cnpj_cpf', 'like', "%{$q}%");
            }))
            ->orderBy('razao_social')
            ->limit(30)
            ->get()
            ->map(fn($f) => [
                'id' => $f->id,
                'text' => $f->razao_social ?: $f->nome_fantasia,
                'cpf_cnpj' => $f->cnpj_cpf,
            ]);

        return response()->json(['results' => $fornecedores]);
    }

    public function storeInline(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cpf_cnpj' => ['required', 'string', 'max:18', 'unique:erp_clientes,cnpj_cpf'],
        ]);

        $fornecedor = Cliente::create([
            'razao_social' => $validated['nome'],
            'cnpj_cpf' => $validated['cpf_cnpj'],
            'tipo_pessoa' => 'F', // F = Fornecedor
            'tipo' => 'FORNECEDOR',
            'email' => '',
            'telefone' => '',
        ]);

        return response()->json([
            'id' => $fornecedor->id,
            'nome' => $fornecedor->razao_social,
            'message' => 'Fornecedor criado com sucesso!',
        ], 201);
    }
}
