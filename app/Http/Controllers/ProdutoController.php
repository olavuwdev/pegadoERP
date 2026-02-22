<?php

namespace App\Http\Controllers;

use App\Models\ErpProduto;
use App\Models\ErpProdutoImagem;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProdutoController extends Controller
{
    private const MAX_IMAGENS = 10;

    private const MAX_IMAGEM_KB = 5120;

    public function index()
    {
        return view('pages.produto.index');
    }

    public function dados(Request $request)
    {
        $query = ErpProduto::query()
            ->select([
                'id',
                'tipo',
                'nome',
                'codigo_sku',
                'codigo_barras',
                'ncm',
                'preco_venda',
                'quantidade_estoque',
                'ativo',
            ])
            ->with('imagemPrincipal:id,produto_id,caminho_arquivo');

        $ativo = $request->input('ativo');
        if ($ativo !== null && $ativo !== '') {
            $query->where('ativo', (int) $ativo === 1);
        }

        $tipo = trim((string) $request->input('tipo', ''));
        if ($tipo !== '') {
            $query->where('tipo', $tipo);
        }

        $ncm = preg_replace('/\D/', '', (string) $request->input('ncm', ''));
        if (!empty($ncm)) {
            $query->where('ncm', 'like', $ncm . '%');
        }

        $recordsTotal = (clone $query)->count();

        $search = trim((string) $request->input('search.value', $request->input('search', '')));
        if ($search !== '') {
            $query->where(function ($sub) use ($search) {
                $sub->where('nome', 'like', '%' . $search . '%')
                    ->orWhere('codigo_sku', 'like', '%' . $search . '%')
                    ->orWhere('codigo_barras', 'like', '%' . $search . '%')
                    ->orWhere('ncm', 'like', '%' . $search . '%');
            });
        }

        $recordsFiltered = (clone $query)->count();
        $query->orderByDesc('id');

        $start = max((int) $request->input('start', 0), 0);
        $length = (int) $request->input('length', 25);
        if ($length <= 0 || $length > 250) {
            $length = 25;
        }

        $produtos = $query
            ->skip($start)
            ->take($length)
            ->get();

        $data = $produtos->map(function (ErpProduto $produto) {
            $isAtivo = (bool) $produto->ativo;
            $statusBadge = $isAtivo
                ? '<span class="badge badge-label badge-soft-success">Ativo</span>'
                : '<span class="badge badge-label badge-soft-danger">Inativo</span>';

            $acaoStatus = $isAtivo ? 'Inativar' : 'Reativar';
            $nextStatus = $isAtivo ? 0 : 1;
            $statusClass = $isAtivo ? 'text-danger' : 'text-success';

            return [
                'id' => $produto->id,
                'tipo' => e($produto->tipo),
                'codigo_sku' => e($produto->codigo_sku),
                'nome' => e($produto->nome),
                'codigo_barras' => e($produto->codigo_barras ?? '-'),
                'ncm' => e($produto->ncm ?? '-'),
                'preco_venda' => 'R$ ' . $this->formatMoney($produto->preco_venda),
                'quantidade_estoque' => $this->formatDecimal($produto->quantidade_estoque, 4),
                'status' => $statusBadge,
                'acoes' => '<div class="dropdown text-muted">'
                    . '<a href="#" class="dropdown-toggle drop-arrow-none fs-xxl link-reset p-0" data-bs-toggle="dropdown" aria-expanded="false">'
                    . '<i class="ti ti-dots-vertical"></i>'
                    . '</a>'
                    . '<div class="dropdown-menu dropdown-menu-end">'
                    . '<a href="/produto/' . $produto->id . '/editar" class="dropdown-item"><i class="ti ti-edit me-1"></i> Editar</a>'
                    . '<a href="#" class="dropdown-item ' . $statusClass . ' js-toggle-status" data-id="' . $produto->id . '" data-ativo="' . $nextStatus . '"><i class="ti ti-power me-1"></i> ' . $acaoStatus . '</a>'
                    . '</div>'
                    . '</div>',
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function create()
    {
        return view('pages.produto.create-legacy');
    }

    public function edit(ErpProduto $produto)
    {
        $produto->load([
            'imagens' => function ($query) {
                $query->orderBy('ordem')->orderBy('id');
            },
        ]);

        return view('pages.produto.form', compact('produto'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        try {
            $produto = DB::transaction(function () use ($request, $validated) {
                $payload = $this->produtoPayload($validated);
                $produto = ErpProduto::query()->create($payload);
                $this->syncImages($request, $produto);

                return $produto;
            });

            return response()->json([
                'message' => 'Produto salvo com sucesso!',
                'id' => $produto->id,
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            Log::error('produto.store_failed', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Nao foi possivel salvar o produto.',
            ], 500);
        }
    }

    public function update(Request $request, ErpProduto $produto)
    {
        if ($request->boolean('_status_only')) {
            return $this->updateStatus($request, $produto);
        }

        $validated = $this->validatePayload($request, $produto);

        try {
            DB::transaction(function () use ($request, $produto, $validated) {
                $produto->fill($this->produtoPayload($validated));
                $produto->save();

                $this->syncImages($request, $produto);
            });

            return response()->json([
                'message' => 'Produto atualizado com sucesso!',
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            Log::error('produto.update_failed', [
                'produto_id' => $produto->id,
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Nao foi possivel atualizar o produto.',
            ], 500);
        }
    }

    private function updateStatus(Request $request, ErpProduto $produto)
    {
        $validator = Validator::make($request->all(), [
            'ativo' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            Log::warning('produto.status_validation_failed', [
                'produto_id' => $produto->id,
                'errors' => $validator->errors()->toArray(),
            ]);

            throw new ValidationException($validator);
        }

        $produto->ativo = (bool) $request->input('ativo');
        $produto->save();

        return response()->json([
            'message' => $produto->ativo ? 'Produto reativado com sucesso!' : 'Produto inativado com sucesso!',
        ]);
    }

    private function validatePayload(Request $request, ?ErpProduto $produto = null): array
    {
        $normalized = $request->all();

        foreach ($this->decimalFields() as $field) {
            if (array_key_exists($field, $normalized)) {
                $normalized[$field] = $this->toDecimal($normalized[$field]);
            }
        }

        foreach (['ncm', 'cest', 'codigo_barras'] as $digitsOnlyField) {
            if (array_key_exists($digitsOnlyField, $normalized) && $normalized[$digitsOnlyField] !== null) {
                $normalized[$digitsOnlyField] = preg_replace('/\D/', '', (string) $normalized[$digitsOnlyField]);
            }
        }

        if (!array_key_exists('ativo', $normalized)) {
            $normalized['ativo'] = true;
        }

        $produtoId = $produto?->id;

        $validator = Validator::make($normalized, [
            'tipo' => ['required', Rule::in(['PRODUTO', 'SERVICO'])],
            'nome' => ['required', 'string', 'max:255'],
            'codigo_sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('erp_produto', 'codigo_sku')->ignore($produtoId),
            ],
            'codigo_barras' => ['nullable', 'regex:/^\d{8,14}$/'],
            'descricao' => ['nullable', 'string'],
            'marca' => ['nullable', 'string', 'max:150'],
            'categoria_id' => ['nullable', 'integer', 'min:1'],

            'preco_custo' => ['required', 'numeric', 'min:0'],
            'preco_venda' => ['required', 'numeric', 'min:0'],
            'quantidade_estoque' => ['required', 'numeric', 'min:0'],
            'estoque_minimo' => ['required', 'numeric', 'min:0'],
            'unidade_medida' => ['required', 'string', 'max:10'],
            'peso' => ['nullable', 'numeric', 'min:0'],
            'largura' => ['nullable', 'numeric', 'min:0'],
            'altura' => ['nullable', 'numeric', 'min:0'],
            'comprimento' => ['nullable', 'numeric', 'min:0'],
            'ativo' => ['nullable', 'boolean'],

            'ncm' => ['nullable', 'regex:/^\d{8}$/'],
            'cest' => ['nullable', 'regex:/^\d{7}$/'],
            'codigo_anp' => ['nullable', 'string', 'max:20'],
            'origem_mercadoria' => ['required', 'integer', 'between:0,8'],
            'ex_tipi' => ['nullable', 'string', 'max:5'],
            'codigo_beneficio_fiscal' => ['nullable', 'string', 'max:20'],

            'cst_icms' => ['nullable', 'string', 'max:3'],
            'csosn' => ['nullable', 'string', 'max:3'],
            'modalidade_bc_icms' => ['nullable', 'string', 'max:2'],
            'aliquota_icms' => ['nullable', 'numeric', 'between:0,100'],
            'reducao_bc_icms' => ['nullable', 'numeric', 'between:0,100'],
            'mva_icms' => ['nullable', 'numeric', 'between:0,100'],
            'aliquota_icms_st' => ['nullable', 'numeric', 'between:0,100'],
            'aliquota_fcp' => ['nullable', 'numeric', 'between:0,100'],
            'aliquota_fcp_st' => ['nullable', 'numeric', 'between:0,100'],

            'cst_pis' => ['nullable', 'string', 'max:3'],
            'aliquota_pis' => ['nullable', 'numeric', 'between:0,100'],
            'base_calculo_pis' => ['nullable', 'numeric', 'min:0'],
            'cst_cofins' => ['nullable', 'string', 'max:3'],
            'aliquota_cofins' => ['nullable', 'numeric', 'between:0,100'],
            'base_calculo_cofins' => ['nullable', 'numeric', 'min:0'],
            'cst_ipi' => ['nullable', 'string', 'max:3'],
            'codigo_enquadramento_ipi' => ['nullable', 'string', 'max:5'],
            'aliquota_ipi' => ['nullable', 'numeric', 'between:0,100'],

            'observacoes' => ['nullable', 'string'],

            'imagens' => ['nullable', 'array', 'max:' . self::MAX_IMAGENS],
            'imagens.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:' . self::MAX_IMAGEM_KB],
            'imagens_remover' => ['nullable', 'array'],
            'imagens_remover.*' => ['integer'],
            'ordem_existente' => ['nullable', 'array'],
            'ordem_existente.*' => ['nullable', 'integer', 'min:0'],
            'ordem_nova' => ['nullable', 'array'],
            'ordem_nova.*' => ['nullable', 'integer', 'min:0'],
            'imagem_principal' => ['nullable', 'string', 'regex:/^(existing|new):\d+$/'],
        ]);

        if ($validator->fails()) {
            Log::warning('produto.validation_failed', [
                'produto_id' => $produtoId,
                'errors' => $validator->errors()->toArray(),
            ]);

            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        $existingCount = $produto ? $produto->imagens()->count() : 0;
        $removeCount = count(array_unique(array_map('intval', $validated['imagens_remover'] ?? [])));
        $newUploads = $request->file('imagens', []);
        if ($newUploads instanceof UploadedFile) {
            $newUploads = [$newUploads];
        }
        $newCount = count($newUploads);
        $remainingCount = max($existingCount - $removeCount, 0);

        if (($remainingCount + $newCount) > self::MAX_IMAGENS) {
            throw ValidationException::withMessages([
                'imagens' => ['Limite maximo de ' . self::MAX_IMAGENS . ' imagens por produto.'],
            ]);
        }

        if (!$produto && ($remainingCount + $newCount) > 0 && empty($validated['imagem_principal'])) {
            throw ValidationException::withMessages([
                'imagem_principal' => ['Selecione uma imagem principal.'],
            ]);
        }

        return $validated;
    }

    private function produtoPayload(array $validated): array
    {
        return collect($validated)
            ->only((new ErpProduto())->getFillable())
            ->all();
    }

    private function syncImages(Request $request, ErpProduto $produto): void
    {
        $removeIds = array_unique(array_map('intval', $request->input('imagens_remover', [])));

        if (!empty($removeIds)) {
            $toRemove = $produto->imagens()->whereIn('id', $removeIds)->get();
            foreach ($toRemove as $imagem) {
                $this->removeImageFile($imagem);
                $imagem->delete();
            }
        }

        $ordemExistente = (array) $request->input('ordem_existente', []);
        $existing = $produto->imagens()->get()->keyBy('id');

        foreach ($existing as $imagem) {
            if (in_array($imagem->id, $removeIds, true)) {
                continue;
            }

            $imagem->ordem = (int) ($ordemExistente[$imagem->id] ?? $imagem->ordem ?? 0);
            $imagem->imagem_principal = false;
            $imagem->save();
        }

        $newFiles = $request->file('imagens', []);
        if ($newFiles instanceof UploadedFile) {
            $newFiles = [$newFiles];
        }
        $ordemNova = (array) $request->input('ordem_nova', []);
        $newImageMap = [];

        foreach ($newFiles as $index => $file) {
            $path = $this->storeImageFile($file, $produto->id);

            $imagem = ErpProdutoImagem::query()->create([
                'produto_id' => $produto->id,
                'caminho_arquivo' => $path,
                'nome_original' => $file->getClientOriginalName(),
                'mime_type' => (string) $file->getMimeType(),
                'tamanho_bytes' => (int) $file->getSize(),
                'ordem' => (int) ($ordemNova[$index] ?? (1000 + $index)),
                'imagem_principal' => false,
            ]);

            $newImageMap[(int) $index] = $imagem;
        }

        $principalId = $this->resolvePrincipalImageId(
            (string) $request->input('imagem_principal', ''),
            $produto,
            $newImageMap
        );

        $images = $produto->imagens()->orderBy('ordem')->orderBy('id')->get();
        if ($images->isEmpty()) {
            return;
        }

        $principalId = $principalId ?: $images->first()->id;

        if (!$images->contains('id', $principalId)) {
            $principalId = $images->first()->id;
        }

        $produto->imagens()->update(['imagem_principal' => false]);
        $produto->imagens()->whereKey($principalId)->update(['imagem_principal' => true]);
    }

    private function resolvePrincipalImageId(string $principalKey, ErpProduto $produto, array $newImageMap): ?int
    {
        if ($principalKey === '') {
            return null;
        }

        [$type, $key] = explode(':', $principalKey) + [null, null];
        $target = (int) $key;

        if ($type === 'existing') {
            return $produto->imagens()->whereKey($target)->exists() ? $target : null;
        }

        if ($type === 'new' && isset($newImageMap[$target])) {
            return $newImageMap[$target]->id;
        }

        return null;
    }

    private function storeImageFile(UploadedFile $file, int $produtoId): string
    {
        try {
            $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
            $filename = (string) Str::uuid() . '.' . $extension;
            $directory = 'produtos/imagens/' . $produtoId;
            $storedPath = Storage::disk('public')->putFileAs($directory, $file, $filename);

            if (!$storedPath) {
                throw new \RuntimeException('Falha ao armazenar arquivo de imagem.');
            }

            return $storedPath;
        } catch (Throwable $exception) {
            Log::error('produto.upload_failed', [
                'produto_id' => $produtoId,
                'nome_arquivo' => $file->getClientOriginalName(),
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    private function removeImageFile(ErpProdutoImagem $imagem): void
    {
        if (!$imagem->caminho_arquivo) {
            return;
        }

        if (!Storage::disk('public')->exists($imagem->caminho_arquivo)) {
            return;
        }

        Storage::disk('public')->delete($imagem->caminho_arquivo);
    }

    private function decimalFields(): array
    {
        return [
            'preco_custo',
            'preco_venda',
            'quantidade_estoque',
            'estoque_minimo',
            'peso',
            'largura',
            'altura',
            'comprimento',
            'aliquota_icms',
            'reducao_bc_icms',
            'mva_icms',
            'aliquota_icms_st',
            'aliquota_fcp',
            'aliquota_fcp_st',
            'aliquota_pis',
            'base_calculo_pis',
            'aliquota_cofins',
            'base_calculo_cofins',
            'aliquota_ipi',
        ];
    }

    private function toDecimal(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value) || is_float($value)) {
            return $value;
        }

        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $hasComma = str_contains($value, ',');
        $hasDot = str_contains($value, '.');

        if ($hasComma && $hasDot) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } elseif ($hasComma) {
            $value = str_replace(',', '.', $value);
        }

        return preg_replace('/[^0-9.\-]/', '', $value);
    }

    private function formatMoney(mixed $value): string
    {
        return number_format((float) $value, 2, ',', '.');
    }

    private function formatDecimal(mixed $value, int $scale = 4): string
    {
        return number_format((float) $value, $scale, ',', '.');
    }
}
