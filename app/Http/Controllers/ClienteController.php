<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\ClienteModel;

class ClienteController extends Controller
{
    public function index()
    {
        return view('pages.clientes.index');
    }
    public function create(){
        return view('pages.clientes.form', ['cliente' => null]);
    }

    public function edit($id)
    {
        $cliente = ClienteModel::findOrFail($id);
        return view('pages.clientes.form', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = ClienteModel::findOrFail($id);

        $payload = [
            'tipo_pessoa' => $request->input('tipo_pessoa'),
            'cnpj_cpf' => $request->input('cnpj_cpf'),
            'inscr_estadual' => $request->input('inscricao_estadual'),
            'razao_social' => $request->input('razao_social'),
            'nome_fantasia' => $request->input('nome_fantasia'),
            'email' => $request->input('email'),
            'telefone' => $request->input('telefone'),
            'logradouro' => $request->input('logradouro'),
            'numero' => $request->input('numero'),
            'complemento' => $request->input('complemento'),
            'bairro' => $request->input('bairro'),
            'cidade' => $request->input('cidade'),
            'uf' => $request->input('uf'),
            'cep' => $request->input('cep'),
            'pais' => $request->input('pais'),
            'ativo' => $request->input('ativo', $cliente->ativo),
        ];

        try {
            $cliente->fill($payload);
            $cliente->save();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar o cliente: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Cliente atualizado com sucesso!']);
    }

    public function datatable()
    {
        $clientes = ClienteModel::select([
            'cliente_id',
            'cnpj_cpf',
            'razao_social',
            'telefone',
            'cidade',
            'ativo',
        ])

            ->orderByDesc('cliente_id')
            ->get();

        $data = $clientes->map(function ($cliente) {
            return [
                'cliente_id' => $cliente->cliente_id,
                'cnpj_cpf' => $cliente->cnpj_cpf,
                'razao_social' => $cliente->razao_social,
                'telefone' => $cliente->telefone,
                'cidade' => $cliente->cidade,
                'ativo' => $cliente->ativo ? '<td style="" class=""><span class="badge badge-label badge-soft-success">Sim</span></td>' : '<td style="" class=""><span class="badge badge-label badge-soft-danger">Nao</span></td>',
                'acoes' => '<td class="text-center">
                                                    <div class="dropdown text-muted">
                                                        <a href="#" class="dropdown-toggle drop-arrow-none fs-xxl link-reset p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a href="/clientes/' . $cliente->cliente_id . '/edit" class="dropdown-item"><i class="ti ti-edit me-1"></i> Editar</a>
                                                            <a href="/clientes/' . $cliente->cliente_id . '/delete" class="dropdown-item text-danger"><i class="ti ti-trash me-1"></i> Excluir</a>
                                                        </div>
                                                    </div>
                                                </td>'
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $payload = [
            'tipo_pessoa' => $request->input('tipo_pessoa'),
            'cnpj_cpf' => $request->input('cnpj_cpf'),
            'razao_social' => $request->input('razao_social'),
            'nome_fantasia' => $request->input('nome_fantasia'),
            'email' => $request->input('email'),
            'telefone' => $request->input('telefone'),
            'logradouro' => $request->input('logradouro'),
            'numero' => $request->input('numero'),
            'complemento' => $request->input('complemento'),
            'bairro' => $request->input('bairro'),
            'cidade' => $request->input('cidade'),
            'uf' => $request->input('uf'),
            'cep' => $request->input('cep'),
            'pais' => $request->input('pais'),
            'ativo' => $request->input('ativo', 1),
            'cliente_final' => $request->input('cliente_final'),
            'indicador_ie' => $request->input('indicador_ie'),
            'inscricao_estadual' => $request->input('inscricao_estadual'),
            'inscr_estadual' => $request->input('inscricao_estadual'),
            'ie_subst_trib' => $request->input('ie_subst_trib'),
            'inscricao_municipal' => $request->input('inscricao_municipal'),
            'suframa' => $request->input('suframa'),
            'data_nascimento' => $request->input('data_nascimento'),
            'palavras_chave' => $request->input('palavras_chave'),
            'data_comemorativa' => $request->input('data_comemorativa'),
            'descricao_comemoracao' => $request->input('descricao_comemoracao'),
            'bases_legais_lgpd' => $request->input('bases_legais_lgpd'),
            'observacoes' => $request->input('observacoes'),
        ];
        try{
            ClienteModel::create($payload);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao salvar o cliente: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Cliente salvo com sucesso!']);
    }


    public function verificaCpfCnpj(Request $request)
    {
        $cnpj_cpf = $request->input('cnpj_cpf');
        $tipo = $request->input('tipo');
        $clienteId = $request->input('cliente_id');
        //dd( $cnpj_cpf,$tipo);

        $query = ClienteModel::where('cnpj_cpf', $cnpj_cpf);
        if (!empty($clienteId)) {
            $query->where('cliente_id', '!=', $clienteId);
        }
        $existe = $query->exists();

        if ($existe) {
            return response()->json(['success' => false, 'message' => 'CPF/CNPJ já cadastrado']);
        }

        if($tipo == 'J') {
            //remover caracteres especiais
            $cnpj_cpf = preg_replace('/[^0-9]/', '', $cnpj_cpf);
            //Requisição curl para a API da ReceitaWS
            $curl = curl_init();

            curl_setopt_array($curl, [
              CURLOPT_URL => "https://receitaws.com.br/v1/cnpj/{$cnpj_cpf}/days/1",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Authorization: Bearer " . env('RECEITAWS_API_KEY'),
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

           //Caso retorno seja 200 retornar true
            if ($err) {
                return response()->json(['success' => false, 'message' => 'Erro na requisição: ' . $err], 500);
            }

           if($response){
                $data = json_decode($response, true);
                return response()->json(['success' => true, 'data' => $data]);
            } else {
                return response()->json(['success' => false, 'message' => 'CNPJ não encontrado'], 404);
            }
        }
        //Retornar sucesso
        return response()->json(['message' => 'CPF consultado com sucesso', 'success' => true]);
    }
}
