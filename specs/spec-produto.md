# Spec — Módulo de Produto V3 (PegadoERP)

> **Data:** 21/02/2026 · **Stack:** Laravel 11 + Blade + jQuery + Bootstrap 5 (Vona template)
> **Padrão de formulário:** Basic Wizard (`form-wizard.html#addrInfo`) com `data-wizard`, `data-wizard-nav`, `data-wizard-next`, `data-wizard-prev`

---

## 1. Resumo e Objetivo

Reescrever o módulo de Produto de ponta a ponta, adotando:

- Wizard de 4 etapas: **Informações Básicas**, **Fornecedores**, **Tributação**, **Imagens**
- Todas as operações CRUD via **AJAX** com `$.ajax` — sem page reload
- Após salvar/atualizar, o sistema retorna JSON e recarrega a listagem via `DataTables.ajax.reload()`
- Cadastro inline (modal AJAX) para **Categoria**, **Marca** e **Fornecedor** sem sair da tela
- **Select2** em todos os `<select>` do formulário
- Máscaras de entrada em todos os campos pertinentes
- Preparação para a **Reforma Tributária** (IBS/CBS) — transição 2026–2033

---

## 2. Decisões de Arquitetura

| Decisão     | Definição                                                                      |
| ----------- | ------------------------------------------------------------------------------ |
| Frontend    | jQuery puro + Select2 + jQuery Mask + DataTables + Bootstrap 5 (Vona)          |
| Backend     | Laravel 11, Controllers resource-like, JSON responses                          |
| Exclusão    | **Não existe** rota `DELETE` — usa campo `ativo` para inativação               |
| Soft-delete | **Não** usa `SoftDeletes` — estado controlado exclusivamente por `ativo`       |
| Upload      | Armazenamento em `storage/app/public/produtos/imagens/{produto_id}`, nome UUID |
| Transações  | `DB::transaction()` para produto + imagens + pivot fornecedores                |
| Auth        | Middleware `auth` em todas as rotas; sem ACL nesta fase                        |
| NF-e        | Apenas campos preparatórios — sem integração com SEFAZ nesta fase              |

---

## 3. Estrutura de Banco de Dados

### 3.1 Nova tabela: `erp_categorias`

```sql
CREATE TABLE erp_categorias (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(150) NOT NULL,
    descricao   TEXT NULL,
    ativo       TINYINT(1) NOT NULL DEFAULT 1,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_categoria_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Migration Laravel:** `create_erp_categorias_table.php`

```php
Schema::create('erp_categorias', function (Blueprint $table) {
    $table->id();
    $table->string('nome', 150)->unique();
    $table->text('descricao')->nullable();
    $table->boolean('ativo')->default(true);
    $table->timestamps();
});
```

---

### 3.2 Nova tabela: `erp_marcas`

```sql
CREATE TABLE erp_marcas (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(150) NOT NULL,
    ativo       TINYINT(1) NOT NULL DEFAULT 1,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_marca_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Migration Laravel:** `create_erp_marcas_table.php`

```php
Schema::create('erp_marcas', function (Blueprint $table) {
    $table->id();
    $table->string('nome', 150)->unique();
    $table->boolean('ativo')->default(true);
    $table->timestamps();
});
```

---

### 3.3 Nova tabela: `erp_produto_fornecedor` (pivot N:N)

```sql
CREATE TABLE erp_produto_fornecedor (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    produto_id      BIGINT UNSIGNED NOT NULL,
    fornecedor_id   BIGINT UNSIGNED NOT NULL,
    codigo_fornecedor VARCHAR(100) NULL COMMENT 'Código do produto no fornecedor',
    preco_fornecedor  DECIMAL(15,4) NULL DEFAULT 0.0000,
    prazo_entrega_dias INT UNSIGNED NULL,
    principal       TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Fornecedor principal do produto',
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES erp_produto(id) ON DELETE CASCADE,
    FOREIGN KEY (fornecedor_id) REFERENCES clientes(id) ON DELETE CASCADE,
    UNIQUE KEY uk_produto_fornecedor (produto_id, fornecedor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Migration Laravel:** `create_erp_produto_fornecedor_table.php`

```php
Schema::create('erp_produto_fornecedor', function (Blueprint $table) {
    $table->id();
    $table->foreignId('produto_id')->constrained('erp_produto')->cascadeOnDelete();
    $table->foreignId('fornecedor_id')->constrained('clientes')->cascadeOnDelete();
    $table->string('codigo_fornecedor', 100)->nullable();
    $table->decimal('preco_fornecedor', 15, 4)->nullable()->default(0);
    $table->unsignedInteger('prazo_entrega_dias')->nullable();
    $table->boolean('principal')->default(false);
    $table->timestamps();
    $table->unique(['produto_id', 'fornecedor_id']);
});
```

> **Nota:** A tabela `clientes` já possui um campo `tipo` que distingue CLIENTE / FORNECEDOR / TRANSPORTADOR. A FK referencia `clientes.id` filtrando pelo tipo FORNECEDOR na camada de aplicação.

---

### 3.4 Tabela `erp_produto` — Alterações (nova migration)

Adicionar colunas para a **Reforma Tributária (IBS/CBS)** e ajustar FK's:

**Migration:** `alter_erp_produto_add_reforma_tributaria.php`

```php
Schema::table('erp_produto', function (Blueprint $table) {
    // FK para categoria e marca (substituem varchar livre)
    $table->unsignedBigInteger('marca_id')->nullable()->after('marca');
    $table->foreign('marca_id')->references('id')->on('erp_marcas')->nullOnDelete();

    // === REFORMA TRIBUTÁRIA — Novos campos IBS/CBS (transição 2026–2033) ===
    // IBS = Imposto sobre Bens e Serviços (Estados + Municípios) — substitui ICMS + ISS
    $table->decimal('aliquota_ibs', 5, 2)->nullable()->after('aliquota_ipi')
          ->comment('Alíquota IBS (substitui ICMS+ISS na transição)');
    $table->decimal('reducao_bc_ibs', 5, 2)->nullable()->after('aliquota_ibs')
          ->comment('Redução de base de cálculo IBS (%)');

    // CBS = Contribuição sobre Bens e Serviços (União) — substitui PIS + COFINS
    $table->decimal('aliquota_cbs', 5, 2)->nullable()->after('reducao_bc_ibs')
          ->comment('Alíquota CBS (substitui PIS+COFINS na transição)');
    $table->decimal('reducao_bc_cbs', 5, 2)->nullable()->after('aliquota_cbs')
          ->comment('Redução de base de cálculo CBS (%)');

    // IS = Imposto Seletivo (Federal) — incide sobre produtos prejudiciais à saúde/meio ambiente
    $table->boolean('sujeito_imposto_seletivo')->default(false)->after('reducao_bc_cbs')
          ->comment('Produto sujeito ao Imposto Seletivo?');
    $table->decimal('aliquota_imposto_seletivo', 5, 2)->nullable()->after('sujeito_imposto_seletivo')
          ->comment('Alíquota do Imposto Seletivo (%)');

    // Regime tributário de referência para transição
    $table->enum('regime_tributario', ['SIMPLES', 'LUCRO_PRESUMIDO', 'LUCRO_REAL'])
          ->nullable()->after('aliquota_imposto_seletivo')
          ->comment('Regime tributário aplicável ao produto');
});
```

**Resumo das novas colunas na `erp_produto`:**

| Campo                     | Tipo            | Nulo | Default | Comentário                             |
| ------------------------- | --------------- | ---- | ------- | -------------------------------------- |
| marca_id                  | bigint unsigned | Sim  | null    | FK → erp_marcas                        |
| aliquota_ibs              | decimal(5,2)    | Sim  | null    | Alíquota IBS (transição ICMS+ISS)      |
| reducao_bc_ibs            | decimal(5,2)    | Sim  | null    | Redução BC do IBS (%)                  |
| aliquota_cbs              | decimal(5,2)    | Sim  | null    | Alíquota CBS (transição PIS+COFINS)    |
| reducao_bc_cbs            | decimal(5,2)    | Sim  | null    | Redução BC do CBS (%)                  |
| sujeito_imposto_seletivo  | tinyint(1)      | Não  | 0       | Incide Imposto Seletivo?               |
| aliquota_imposto_seletivo | decimal(5,2)    | Sim  | null    | Alíquota Imposto Seletivo (%)          |
| regime_tributario         | enum            | Sim  | null    | SIMPLES / LUCRO_PRESUMIDO / LUCRO_REAL |

---

### 3.5 Tabela `erp_produto_imagem` — Sem alterações

Mantém a mesma estrutura já existente (ver spec anterior 001).

---

### 3.6 Diagrama de Relacionamento (ER simplificado)

```
erp_categorias (1) ──────< (N) erp_produto
erp_marcas     (1) ──────< (N) erp_produto
erp_produto    (1) ──────< (N) erp_produto_imagem
erp_produto    (N) >─────< (N) clientes (tipo=FORNECEDOR) [pivot: erp_produto_fornecedor]
```

---

## 4. Models (Eloquent)

### 4.1 `ErpCategoria`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErpCategoria extends Model
{
    protected $table = 'erp_categorias';

    protected $fillable = ['nome', 'descricao', 'ativo'];

    protected function casts(): array
    {
        return ['ativo' => 'boolean'];
    }

    public function produtos()
    {
        return $this->hasMany(ErpProduto::class, 'categoria_id');
    }

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }
}
```

### 4.2 `ErpMarca`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErpMarca extends Model
{
    protected $table = 'erp_marcas';

    protected $fillable = ['nome', 'ativo'];

    protected function casts(): array
    {
        return ['ativo' => 'boolean'];
    }

    public function produtos()
    {
        return $this->hasMany(ErpProduto::class, 'marca_id');
    }

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }
}
```

### 4.3 `ErpProduto` — Alterações no Model existente

Adicionar ao `$fillable`:

```php
'marca_id',
'aliquota_ibs',
'reducao_bc_ibs',
'aliquota_cbs',
'reducao_bc_cbs',
'sujeito_imposto_seletivo',
'aliquota_imposto_seletivo',
'regime_tributario',
```

Adicionar ao `casts()`:

```php
'aliquota_ibs'              => 'decimal:2',
'reducao_bc_ibs'            => 'decimal:2',
'aliquota_cbs'              => 'decimal:2',
'reducao_bc_cbs'            => 'decimal:2',
'sujeito_imposto_seletivo'  => 'boolean',
'aliquota_imposto_seletivo' => 'decimal:2',
```

Adicionar relationships:

```php
public function categoria()
{
    return $this->belongsTo(ErpCategoria::class, 'categoria_id');
}

public function marcaRelation()
{
    return $this->belongsTo(ErpMarca::class, 'marca_id');
}

public function fornecedores()
{
    return $this->belongsToMany(Cliente::class, 'erp_produto_fornecedor', 'produto_id', 'fornecedor_id')
        ->withPivot(['codigo_fornecedor', 'preco_fornecedor', 'prazo_entrega_dias', 'principal'])
        ->withTimestamps();
}
```

### 4.4 `ErpProdutoFornecedor` (Pivot Model — opcional)

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ErpProdutoFornecedor extends Pivot
{
    protected $table = 'erp_produto_fornecedor';
    public $incrementing = true;

    protected $fillable = [
        'produto_id', 'fornecedor_id',
        'codigo_fornecedor', 'preco_fornecedor',
        'prazo_entrega_dias', 'principal',
    ];

    protected function casts(): array
    {
        return [
            'preco_fornecedor'    => 'decimal:4',
            'prazo_entrega_dias'  => 'integer',
            'principal'           => 'boolean',
        ];
    }
}
```

---

## 5. Rotas (Routes)

### 5.1 Rotas Web — `routes/web.php`

```php
Route::middleware('auth')->group(function () {
    // ── Produto ──
    Route::get('/produto', [ProdutoController::class, 'index'])->name('produto.index');
    Route::get('/produto/dados', [ProdutoController::class, 'dados'])->name('produto.dados');
    Route::get('/produto/novo', [ProdutoController::class, 'create'])->name('produto.create');
    Route::get('/produto/{produto}/editar', [ProdutoController::class, 'edit'])->name('produto.edit');
    Route::post('/produto', [ProdutoController::class, 'store'])->name('produto.store');
    Route::put('/produto/{produto}', [ProdutoController::class, 'update'])->name('produto.update');
    // Sem DELETE — inativação via PUT com _status_only

    // ── API Auxiliares (AJAX inline) ──
    Route::prefix('api')->group(function () {
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
```

### 5.2 Contratos HTTP

| Método | URI                                    | Ação                                        | Retorno                                                |
| ------ | -------------------------------------- | ------------------------------------------- | ------------------------------------------------------ |
| `GET`  | `/produto`                             | Listagem (Blade + DataTables)               | HTML                                                   |
| `GET`  | `/produto/dados`                       | DataTables AJAX (server-side)               | `{ draw, recordsTotal, recordsFiltered, data: [...] }` |
| `GET`  | `/produto/novo`                        | Form de criação (Blade wizard)              | HTML                                                   |
| `GET`  | `/produto/{id}/editar`                 | Form de edição (Blade wizard)               | HTML                                                   |
| `POST` | `/produto`                             | Salvar novo produto                         | `{ message, id }` ou `422 { message, errors }`         |
| `PUT`  | `/produto/{id}`                        | Atualizar produto existente                 | `{ message }` ou `422 { message, errors }`             |
| `PUT`  | `/produto/{id}` (com `_status_only=1`) | Inativar/Reativar                           | `{ message }`                                          |
| `GET`  | `/api/categorias`                      | Lista categorias ativas (JSON para Select2) | `[{ id, text }]`                                       |
| `POST` | `/api/categorias`                      | Criar categoria via modal AJAX              | `{ id, nome, message }`                                |
| `GET`  | `/api/marcas`                          | Lista marcas ativas (JSON para Select2)     | `[{ id, text }]`                                       |
| `POST` | `/api/marcas`                          | Criar marca via modal AJAX                  | `{ id, nome, message }`                                |
| `GET`  | `/api/fornecedores/buscar`             | Busca fornecedores (Select2 AJAX)           | `{ results: [{ id, text }] }`                          |
| `POST` | `/api/fornecedores`                    | Criar fornecedor inline                     | `{ id, nome, message }`                                |

---

## 6. Controllers

### 6.1 `ProdutoController` — Refatoração

Manter a mesma estrutura existente com os seguintes ajustes:

**`create()`** — Agora retorna view `pages.produto.form` (unificada create/edit) com dados auxiliares:

```php
public function create()
{
    $categorias = ErpCategoria::ativo()->orderBy('nome')->get();
    $marcas     = ErpMarca::ativo()->orderBy('nome')->get();
    $produto    = null;

    return view('pages.produto.form', compact('produto', 'categorias', 'marcas'));
}
```

**`edit()`** — Carrega relações:

```php
public function edit(ErpProduto $produto)
{
    $produto->load([
        'imagens' => fn($q) => $q->orderBy('ordem')->orderBy('id'),
        'fornecedores',
        'categoria',
        'marcaRelation',
    ]);

    $categorias = ErpCategoria::ativo()->orderBy('nome')->get();
    $marcas     = ErpMarca::ativo()->orderBy('nome')->get();

    return view('pages.produto.form', compact('produto', 'categorias', 'marcas'));
}
```

**`store()` / `update()`** — Adicionar sincronização de fornecedores:

```php
// Dentro do DB::transaction(), após salvar produto e imagens:
$this->syncFornecedores($request, $produto);
```

**Método `syncFornecedores()`:**

```php
private function syncFornecedores(Request $request, ErpProduto $produto): void
{
    $fornecedores = $request->input('fornecedores', []);
    $syncData = [];

    foreach ($fornecedores as $item) {
        $fornecedorId = (int) ($item['fornecedor_id'] ?? 0);
        if ($fornecedorId <= 0) continue;

        $syncData[$fornecedorId] = [
            'codigo_fornecedor'  => $item['codigo_fornecedor'] ?? null,
            'preco_fornecedor'   => $this->toDecimal($item['preco_fornecedor'] ?? null),
            'prazo_entrega_dias' => !empty($item['prazo_entrega_dias']) ? (int) $item['prazo_entrega_dias'] : null,
            'principal'          => (bool) ($item['principal'] ?? false),
        ];
    }

    $produto->fornecedores()->sync($syncData);
}
```

**Validação — novos campos:**

```php
// Adicionar às regras de validação existentes:
'marca_id'                   => ['nullable', 'integer', 'exists:erp_marcas,id'],
'aliquota_ibs'               => ['nullable', 'numeric', 'between:0,100'],
'reducao_bc_ibs'             => ['nullable', 'numeric', 'between:0,100'],
'aliquota_cbs'               => ['nullable', 'numeric', 'between:0,100'],
'reducao_bc_cbs'             => ['nullable', 'numeric', 'between:0,100'],
'sujeito_imposto_seletivo'   => ['nullable', 'boolean'],
'aliquota_imposto_seletivo'  => ['nullable', 'numeric', 'between:0,100'],
'regime_tributario'          => ['nullable', Rule::in(['SIMPLES', 'LUCRO_PRESUMIDO', 'LUCRO_REAL'])],

// Fornecedores (array de objetos)
'fornecedores'                      => ['nullable', 'array'],
'fornecedores.*.fornecedor_id'      => ['required', 'integer', 'exists:clientes,id'],
'fornecedores.*.codigo_fornecedor'  => ['nullable', 'string', 'max:100'],
'fornecedores.*.preco_fornecedor'   => ['nullable', 'numeric', 'min:0'],
'fornecedores.*.prazo_entrega_dias' => ['nullable', 'integer', 'min:0'],
'fornecedores.*.principal'          => ['nullable', 'boolean'],
```

### 6.2 `CategoriaController` (Novo)

```php
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
            'nome'      => ['required', 'string', 'max:150', 'unique:erp_categorias,nome'],
            'descricao' => ['nullable', 'string'],
        ]);

        $categoria = ErpCategoria::create($validated);

        return response()->json([
            'id'      => $categoria->id,
            'nome'    => $categoria->nome,
            'message' => 'Categoria criada com sucesso!',
        ], 201);
    }
}
```

### 6.3 `MarcaController` (Novo)

```php
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
            'id'      => $marca->id,
            'nome'    => $marca->nome,
            'message' => 'Marca criada com sucesso!',
        ], 201);
    }
}
```

### 6.4 `FornecedorController` — Método `buscar()` e `storeInline()`

```php
// Adicionar ao FornecedorController existente:

public function buscar(Request $request)
{
    $q = trim($request->input('q', ''));

    $fornecedores = Cliente::where('tipo', 'FORNECEDOR')
        ->when($q, fn($query) => $query->where('nome', 'like', "%{$q}%"))
        ->orderBy('nome')
        ->limit(30)
        ->get(['id', 'nome as text', 'cpf_cnpj']);

    return response()->json(['results' => $fornecedores]);
}

public function storeInline(Request $request)
{
    $validated = $request->validate([
        'nome'     => ['required', 'string', 'max:255'],
        'cpf_cnpj' => ['required', 'string', 'max:18', 'unique:clientes,cpf_cnpj'],
    ]);

    $fornecedor = Cliente::create(array_merge($validated, ['tipo' => 'FORNECEDOR']));

    return response()->json([
        'id'      => $fornecedor->id,
        'nome'    => $fornecedor->nome,
        'message' => 'Fornecedor criado com sucesso!',
    ], 201);
}
```

---

## 7. View — Wizard de 4 Etapas

**Arquivo:** `resources/views/pages/produto/form.blade.php`

**Layout base:** `@extends('layouts.form')` (já existente)

### 7.1 Estrutura do Wizard (baseado no Basic Wizard do template Vona)

```blade
<div class="card">
    <div class="card-header justify-content-between">
        <h5 class="card-title">{{ $produto ? 'Editar Produto' : 'Cadastrar Produto' }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ url('/produto') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Voltar
            </a>
            <button type="button" id="btn-salvar" class="btn btn-primary">
                <i class="ti ti-device-floppy me-1"></i>Salvar
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="ins-wizard" data-wizard>
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs wizard-tabs" data-wizard-nav role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#stepBasico">
                        <span class="d-flex align-items-center">
                            <i class="ti ti-package fs-32"></i>
                            <span class="flex-grow-1 ms-2 text-truncate">
                                <span class="d-block fw-semibold text-body fs-base">Informações Básicas</span>
                                <span class="fw-normal">Dados do produto</span>
                            </span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#stepFornecedores">
                        <span class="d-flex align-items-center">
                            <i class="ti ti-truck fs-32"></i>
                            <span class="flex-grow-1 ms-2 text-truncate">
                                <span class="d-block fw-semibold text-body fs-base">Fornecedores</span>
                                <span class="fw-normal">Quem fornece</span>
                            </span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#stepTributacao">
                        <span class="d-flex align-items-center">
                            <i class="ti ti-receipt-tax fs-32"></i>
                            <span class="flex-grow-1 ms-2 text-truncate">
                                <span class="d-block fw-semibold text-body fs-base">Tributação</span>
                                <span class="fw-normal">Fiscal e impostos</span>
                            </span>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#stepImagens">
                        <span class="d-flex align-items-center">
                            <i class="ti ti-photo fs-32"></i>
                            <span class="flex-grow-1 ms-2 text-truncate">
                                <span class="d-block fw-semibold text-body fs-base">Imagens</span>
                                <span class="fw-normal">Galeria do produto</span>
                            </span>
                        </span>
                    </a>
                </li>
            </ul>

            <div class="tab-content pt-3" data-wizard-content>
                <!-- Step 1: Informações Básicas -->
                <!-- Step 2: Fornecedores -->
                <!-- Step 3: Tributação -->
                <!-- Step 4: Imagens -->
            </div>
        </div>
    </div>
</div>
```

---

### 7.2 Step 1 — Informações Básicas

Campos organizados em `row > col`:

| Campo            | Tipo                     | Col      | Máscara                              | Select2               | Obrigatório |
| ---------------- | ------------------------ | -------- | ------------------------------------ | --------------------- | ----------- |
| Tipo             | select (PRODUTO/SERVICO) | col-md-2 | —                                    | ✅                    | ✅          |
| Nome             | text                     | col-md-5 | —                                    | —                     | ✅          |
| Código SKU       | text                     | col-md-3 | —                                    | —                     | ✅          |
| GTIN/Cód. Barras | text                     | col-md-2 | `00000000000000` (até 14 dígitos)    | —                     | —           |
| Categoria        | select (AJAX)            | col-md-4 | —                                    | ✅ (AJAX + botão "+") | —           |
| Marca            | select (AJAX)            | col-md-4 | —                                    | ✅ (AJAX + botão "+") | —           |
| Unidade Medida   | select                   | col-md-2 | —                                    | ✅                    | ✅          |
| Status (ativo)   | switch                   | col-md-2 | —                                    | —                     | —           |
| Descrição        | textarea                 | col-12   | —                                    | —                     | —           |
| Preço Custo      | text                     | col-md-3 | `000.000.000,0000` (decimal 4 casas) | —                     | ✅          |
| Preço Venda      | text                     | col-md-3 | `000.000.000,0000`                   | —                     | ✅          |
| Qtd. Estoque     | text                     | col-md-3 | `000.000.000,0000`                   | —                     | ✅          |
| Estoque Mínimo   | text                     | col-md-3 | `000.000.000,0000`                   | —                     | ✅          |
| Peso (kg)        | text                     | col-md-3 | `000.000,0000`                       | —                     | —           |
| Largura (cm)     | text                     | col-md-3 | `000.000,0000`                       | —                     | —           |
| Altura (cm)      | text                     | col-md-3 | `000.000,0000`                       | —                     | —           |
| Comprimento (cm) | text                     | col-md-3 | `000.000,0000`                       | —                     | —           |
| Observações      | textarea                 | col-12   | —                                    | —                     | —           |

**Botão "+" para Categoria e Marca:**

Ao lado de cada Select2, um `<button>` com ícone `ti-plus` abre um **Modal Bootstrap** com formulário mínimo (nome + descrição para categoria, nome para marca). O submit do modal faz `$.ajax POST` para `/api/categorias` ou `/api/marcas`, recebe `{ id, nome }` e adiciona a nova opção no Select2 já selecionada.

```html
<!-- Exemplo de campo Categoria com botão + -->
<div class="col-md-4">
    <label class="form-label">Categoria</label>
    <div class="input-group">
        <select
            name="categoria_id"
            id="categoria_id"
            class="form-select select2-ajax"
            data-url="{{ route('api.categorias.listar') }}"
            data-placeholder="Selecione uma categoria"
        >
            @if($produto?->categoria)
            <option value="{{ $produto->categoria_id }}" selected>
                {{ $produto->categoria->nome }}
            </option>
            @endif
        </select>
        <button
            type="button"
            class="btn btn-outline-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalNovaCategoria"
            title="Nova categoria"
        >
            <i class="ti ti-plus"></i>
        </button>
    </div>
</div>
```

---

### 7.3 Step 2 — Fornecedores

Tabela dinâmica para adicionar N fornecedores ao produto:

```html
<div class="tab-pane fade" id="stepFornecedores">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6>Fornecedores vinculados</h6>
        <div class="d-flex gap-2">
            <button
                type="button"
                class="btn btn-sm btn-outline-primary"
                id="btn-add-fornecedor"
            >
                <i class="ti ti-plus me-1"></i>Adicionar
            </button>
            <button
                type="button"
                class="btn btn-sm btn-outline-success"
                data-bs-toggle="modal"
                data-bs-target="#modalNovoFornecedor"
            >
                <i class="ti ti-user-plus me-1"></i>Novo Fornecedor
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table
            class="table table-bordered align-middle"
            id="tabela-fornecedores"
        >
            <thead class="thead-sm">
                <tr>
                    <th style="width:35%">Fornecedor</th>
                    <th>Cód. no Fornecedor</th>
                    <th>Preço Fornecedor</th>
                    <th>Prazo Entrega (dias)</th>
                    <th style="width:80px">Principal</th>
                    <th style="width:60px">Ações</th>
                </tr>
            </thead>
            <tbody id="fornecedores-body">
                {{-- Linhas adicionadas via JS --}}
            </tbody>
        </table>
    </div>
</div>
```

| Campo da linha       | Tipo          | Máscara            | Select2                                  |
| -------------------- | ------------- | ------------------ | ---------------------------------------- |
| Fornecedor           | select (AJAX) | —                  | ✅ (endpoint `/api/fornecedores/buscar`) |
| Código no Fornecedor | text          | —                  | —                                        |
| Preço Fornecedor     | text          | `000.000.000,0000` | —                                        |
| Prazo Entrega (dias) | number        | —                  | —                                        |
| Principal            | radio         | —                  | —                                        |
| Remover              | button        | —                  | —                                        |

**JS para adicionar linha:**

```javascript
$("#btn-add-fornecedor").on("click", function () {
    const idx = $("#fornecedores-body tr").length;
    const row = `<tr data-index="${idx}">
        <td><select name="fornecedores[${idx}][fornecedor_id]" class="form-select select2-fornecedor"
                    data-placeholder="Buscar fornecedor..."></select></td>
        <td><input type="text" class="form-control" name="fornecedores[${idx}][codigo_fornecedor]" maxlength="100"></td>
        <td><input type="text" class="form-control js-decimal-4" name="fornecedores[${idx}][preco_fornecedor]"></td>
        <td><input type="number" min="0" class="form-control" name="fornecedores[${idx}][prazo_entrega_dias]"></td>
        <td class="text-center"><input type="radio" name="fornecedor_principal" value="${idx}" class="form-check-input"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger js-remove-fornecedor">
            <i class="ti ti-trash"></i></button></td>
    </tr>`;
    $("#fornecedores-body").append(row);
    initSelect2Fornecedor($(`tr[data-index="${idx}"] .select2-fornecedor`));
    applyMasks();
});
```

---

### 7.4 Step 3 — Tributação

Dividido em **3 seções** com separador visual (`<hr>` ou card interno):

#### Seção A — Dados Fiscais Gerais

| Campo                 | Tipo   | Col      | Máscara                             | Select2 | Obrigatório |
| --------------------- | ------ | -------- | ----------------------------------- | ------- | ----------- |
| NCM                   | text   | col-md-2 | `0000.00.00` (8 dígitos formatados) | —       | —           |
| CEST                  | text   | col-md-2 | `00.000.00` (7 dígitos formatados)  | —       | —           |
| Código ANP            | text   | col-md-2 | —                                   | —       | —           |
| Origem Mercadoria     | select | col-md-3 | —                                   | ✅      | ✅          |
| EX TIPI               | text   | col-md-1 | —                                   | —       | —           |
| Cód. Benefício Fiscal | text   | col-md-2 | —                                   | —       | —           |
| Regime Tributário     | select | col-md-3 | —                                   | ✅      | —           |

#### Seção B — Tributos Vigentes (ICMS/PIS/COFINS/IPI)

> **Nota:** Esses campos continuam obrigatórios durante o período de transição 2026–2033. Após 2033 serão deprecados.

| Campo               | Tipo | Col      | Máscara            |
| ------------------- | ---- | -------- | ------------------ |
| CST ICMS            | text | col-md-2 | `000`              |
| CSOSN               | text | col-md-2 | `000`              |
| Mod. BC ICMS        | text | col-md-2 | `00`               |
| Alíq. ICMS (%)      | text | col-md-2 | `##0,00` (reverse) |
| Redução BC ICMS (%) | text | col-md-2 | `##0,00`           |
| MVA ICMS (%)        | text | col-md-2 | `##0,00`           |
| Alíq. ICMS ST (%)   | text | col-md-2 | `##0,00`           |
| Alíq. FCP (%)       | text | col-md-2 | `##0,00`           |
| Alíq. FCP ST (%)    | text | col-md-2 | `##0,00`           |
| CST PIS             | text | col-md-2 | `000`              |
| Alíq. PIS (%)       | text | col-md-2 | `##0,00`           |
| Base PIS            | text | col-md-2 | `000.000.000,0000` |
| CST COFINS          | text | col-md-2 | `000`              |
| Alíq. COFINS (%)    | text | col-md-2 | `##0,00`           |
| Base COFINS         | text | col-md-2 | `000.000.000,0000` |
| CST IPI             | text | col-md-2 | `000`              |
| Cód. Enquad. IPI    | text | col-md-2 | `00000`            |
| Alíq. IPI (%)       | text | col-md-2 | `##0,00`           |

#### Seção C — Reforma Tributária (IBS / CBS / IS) — Transição 2026–2033

> **Alerta visual (info):** "Campos da Reforma Tributária (EC 132/2023). Durante o período de transição (2026–2033), é necessário manter tanto os tributos vigentes quanto os novos."

| Campo                       | Tipo   | Col      | Máscara  | Observação                             |
| --------------------------- | ------ | -------- | -------- | -------------------------------------- |
| Alíquota IBS (%)            | text   | col-md-2 | `##0,00` | Substitui ICMS + ISS                   |
| Redução BC IBS (%)          | text   | col-md-2 | `##0,00` | —                                      |
| Alíquota CBS (%)            | text   | col-md-2 | `##0,00` | Substitui PIS + COFINS                 |
| Redução BC CBS (%)          | text   | col-md-2 | `##0,00` | —                                      |
| Sujeito a Imposto Seletivo? | switch | col-md-3 | —        | Produtos nocivos à saúde/meio ambiente |
| Alíq. Imposto Seletivo (%)  | text   | col-md-2 | `##0,00` | Exibir apenas se switch ativo          |
| **Tooltip explicativo**     | —      | —        | —        | Cada campo tem `title` com descrição   |

**Contexto da Reforma Tributária:**

A Reforma Tributária brasileira (EC 132/2023 + LC 214/2025) institui o **IVA Dual**:

- **CBS** (Contribuição sobre Bens e Serviços) — Federal, substitui PIS e COFINS
- **IBS** (Imposto sobre Bens e Serviços) — Estadual/Municipal, substitui ICMS e ISS
- **IS** (Imposto Seletivo) — Federal, incide sobre produtos prejudiciais (tabaco, álcool, açúcar, minerais, etc.)

**Cronograma da transição:**

- **2026:** Início de teste — CBS a 0,9% e IBS a 0,1% (fase experimental)
- **2027:** CBS a 100% entra em vigor; PIS e COFINS extintos
- **2029–2032:** IBS implementado gradualmente; ICMS e ISS reduzidos proporcionalmente
- **2033:** ICMS e ISS extintos; IBS a 100%

**Alíquota de referência estimada:** ~26,5% (IBS + CBS combinados)

> Os campos da **Seção B** devem ser mantidos no banco e no formulário até 2033 para compatibilidade com NF-e e SPED.

---

### 7.5 Step 4 — Imagens

Mesmo comportamento do wizard atual (upload múltiplo, galeria com drag-order, definição de principal), porém integrado no novo wizard de 4 steps.

| Funcionalidade   | Descrição                                                       |
| ---------------- | --------------------------------------------------------------- |
| Upload           | `<input type="file" multiple>` — aceita `jpg, jpeg, png, webp`  |
| Limite           | Máximo 10 imagens por produto, 5 MB cada                        |
| Preview          | Thumbnails com `URL.createObjectURL()`                          |
| Imagem principal | Radio button por imagem                                         |
| Ordem            | Campo numérico por imagem                                       |
| Remoção          | Botão "Remover" marca imagem para exclusão no submit            |
| Armazenamento    | `storage/app/public/produtos/imagens/{produto_id}/{uuid}.{ext}` |

---

## 8. Máscaras — Resumo Completo

Biblioteca: **jQuery Mask Plugin** (já incluída no layout master)

```javascript
function applyMasks() {
    if (!$.fn.mask) return;

    // Documentos e códigos
    $("#codigo_barras").mask("00000000000000"); // GTIN até 14 dígitos
    $("#ncm").mask("0000.00.00"); // NCM formatado
    $("#cest").mask("00.000.00"); // CEST formatado
    $('input[name="cst_icms"]').mask("000");
    $('input[name="csosn"]').mask("000");
    $('input[name="modalidade_bc_icms"]').mask("00");
    $('input[name="cst_pis"]').mask("000");
    $('input[name="cst_cofins"]').mask("000");
    $('input[name="cst_ipi"]').mask("000");
    $('input[name="codigo_enquadramento_ipi"]').mask("00000");

    // Percentuais (0,00 a 100,00)
    $(".js-percent").mask("##0,00", { reverse: true });

    // Decimais com 4 casas
    $(".js-decimal-4").mask("000.000.000.000.000,0000", { reverse: true });
}
```

---

## 9. Select2 — Configuração

```javascript
// Select2 padrão (select estáticos)
$(".select2-static").select2({
    theme: "bootstrap-5",
    width: "100%",
    language: "pt-BR",
    allowClear: true,
    placeholder: $(this).data("placeholder") || "Selecione...",
});

// Select2 AJAX (categorias, marcas)
$(".select2-ajax").each(function () {
    $(this).select2({
        theme: "bootstrap-5",
        width: "100%",
        language: "pt-BR",
        allowClear: true,
        placeholder: $(this).data("placeholder") || "Selecione...",
        ajax: {
            url: $(this).data("url"),
            dataType: "json",
            delay: 300,
            data: (params) => ({ q: params.term }),
            processResults: (data) => ({ results: data }),
            cache: true,
        },
        minimumInputLength: 0,
    });
});

// Select2 para Fornecedores (com formatação customizada)
function initSelect2Fornecedor($el) {
    $el.select2({
        theme: "bootstrap-5",
        width: "100%",
        language: "pt-BR",
        placeholder: "Buscar fornecedor...",
        ajax: {
            url: "/api/fornecedores/buscar",
            dataType: "json",
            delay: 300,
            data: (params) => ({ q: params.term }),
            processResults: (data) => data,
            cache: true,
        },
        minimumInputLength: 2,
        templateResult: (item) =>
            item.text
                ? $(
                      `<span>${item.text} <small class="text-muted">${item.cpf_cnpj || ""}</small></span>`,
                  )
                : item.text,
    });
}
```

**Dependências CSS/JS:**

- `select2.min.css` + `select2-bootstrap-5-theme.min.css`
- `select2.min.js` + `select2.pt-BR.js`

Incluir no `@section('style')` e `@section('scripts')` da view.

---

## 10. Modals de Cadastro Inline (AJAX)

### 10.1 Modal Nova Categoria

```html
<div class="modal fade" id="modalNovaCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Categoria</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nome *</label>
                    <input
                        type="text"
                        class="form-control"
                        id="cat_nome"
                        maxlength="150"
                        required
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea
                        class="form-control"
                        id="cat_descricao"
                        rows="2"
                    ></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancelar
                </button>
                <button
                    type="button"
                    class="btn btn-primary"
                    id="btn-salvar-categoria"
                >
                    Salvar
                </button>
            </div>
        </div>
    </div>
</div>
```

**JS do Modal:**

```javascript
$("#btn-salvar-categoria").on("click", function () {
    const nome = $("#cat_nome").val().trim();
    if (!nome) {
        showAlert("Informe o nome da categoria.", "warning");
        return;
    }

    $.ajax({
        url: "/api/categorias",
        method: "POST",
        data: { nome, descricao: $("#cat_descricao").val(), _token: csrfToken },
        headers: { Accept: "application/json" },
        success: function (res) {
            // Adiciona opção no Select2 e seleciona
            const option = new Option(res.nome, res.id, true, true);
            $("#categoria_id").append(option).trigger("change");
            $("#modalNovaCategoria").modal("hide");
            $("#cat_nome").val("");
            $("#cat_descricao").val("");
            showAlert(res.message, "success");
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.message || "Erro ao criar categoria.";
            showAlert(msg, "danger");
        },
    });
});
```

### 10.2 Modal Nova Marca

Idêntico ao de Categoria, porém:

- ID: `#modalNovaMarca`
- Campo: apenas `nome`
- Endpoint: `POST /api/marcas`
- Select2 target: `#marca_id`

### 10.3 Modal Novo Fornecedor

- ID: `#modalNovoFornecedor`
- Campos: `nome`, `cpf_cnpj` (com máscara `000.000.000-00` ou `00.000.000/0000-00`)
- Endpoint: `POST /api/fornecedores`
- Após sucesso: adiciona como opção no Select2 da linha corrente de fornecedor

---

## 11. Fluxo AJAX — Submit e Reload

### 11.1 Submit do Formulário (Store/Update)

```javascript
$("#btn-salvar").on("click", function () {
    if (!validateCurrentStep()) return;

    const produtoId = $("#produto_id").val();
    const isUpdate = !!produtoId;
    const formData = new FormData($("#form-produto")[0]);

    if (isUpdate) formData.append("_method", "PUT");

    // Serializar dados de fornecedores corretamente
    $("#fornecedores-body tr").each(function (idx) {
        const $tr = $(this);
        formData.set(
            `fornecedores[${idx}][fornecedor_id]`,
            $tr.find("[name*=fornecedor_id]").val(),
        );
        formData.set(
            `fornecedores[${idx}][codigo_fornecedor]`,
            $tr.find("[name*=codigo_fornecedor]").val(),
        );
        formData.set(
            `fornecedores[${idx}][preco_fornecedor]`,
            $tr.find("[name*=preco_fornecedor]").val(),
        );
        formData.set(
            `fornecedores[${idx}][prazo_entrega_dias]`,
            $tr.find("[name*=prazo_entrega_dias]").val(),
        );
        formData.set(
            `fornecedores[${idx}][principal]`,
            $('input[name="fornecedor_principal"]:checked').val() == idx
                ? 1
                : 0,
        );
    });

    const $btn = $(this);
    $btn.prop("disabled", true).html(
        '<span class="spinner-border spinner-border-sm me-1"></span> Salvando...',
    );

    $.ajax({
        url: isUpdate ? `/produto/${produtoId}` : "/produto",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: { "X-CSRF-TOKEN": csrfToken, Accept: "application/json" },
        success: function (res) {
            showAlert(res.message || "Produto salvo com sucesso!", "success");
            window.location.href = "/produto"; // Volta para listagem
        },
        error: function (xhr) {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                // Marca campos com erro e exibe primeiro erro
                Object.keys(errors).forEach((field) => {
                    $(`[name="${field}"]`).addClass("is-invalid");
                });
                showAlert(Object.values(errors)[0][0], "danger");
            } else {
                showAlert(
                    xhr.responseJSON?.message || "Erro ao salvar o produto.",
                    "danger",
                );
            }
            $btn.prop("disabled", false).html(
                '<i class="ti ti-device-floppy me-1"></i> Salvar',
            );
        },
    });
});
```

### 11.2 Inativar/Reativar (na listagem)

Já existente — usa `PUT /produto/{id}` com `_status_only=1` e `DataTables.ajax.reload()`.

---

## 12. Regras de Negócio e Validações

### 12.1 Regras de Domínio

| Regra                  | Descrição                                                         |
| ---------------------- | ----------------------------------------------------------------- |
| SKU único              | `codigo_sku` é `unique` na tabela                                 |
| Sem exclusão           | Produto nunca é deletado; apenas `ativo = false`                  |
| 1 imagem principal     | Apenas uma imagem com `imagem_principal = true` por produto       |
| Máx. 10 imagens        | Upload limitado a 10 arquivos por produto                         |
| 1 fornecedor principal | Apenas um fornecedor com `principal = true` por produto           |
| Decimais BR            | Frontend envia `1.234,5678`; backend normaliza para `1234.5678`   |
| Transação atômica      | Produto + imagens + fornecedores em uma única `DB::transaction()` |

### 12.2 Validação por Etapa (Frontend)

O wizard valida campos `required` e padrões antes de avançar. Campos com erro recebem classe `is-invalid`.

### 12.3 Validação Backend (Laravel)

Regras completas descritas na Seção 6.1 (ProdutoController). Toda resposta de erro segue o formato Laravel:

```json
{
    "message": "The nome field is required.",
    "errors": {
        "nome": ["The nome field is required."]
    }
}
```

---

## 13. Tratamento de Erros e Mensagens

| Situação              | Tipo           | Mensagem                                   |
| --------------------- | -------------- | ------------------------------------------ |
| Salvar com sucesso    | `success`      | "Produto salvo com sucesso!"               |
| Atualizar com sucesso | `success`      | "Produto atualizado com sucesso!"          |
| Inativar              | `success`      | "Produto inativado com sucesso!"           |
| Reativar              | `success`      | "Produto reativado com sucesso!"           |
| Validação falhou      | `danger` (422) | Primeiro erro do `errors`                  |
| Erro interno          | `danger` (500) | "Não foi possível salvar o produto."       |
| Categoria criada      | `success`      | "Categoria criada com sucesso!"            |
| Marca criada          | `success`      | "Marca criada com sucesso!"                |
| Fornecedor criado     | `success`      | "Fornecedor criado com sucesso!"           |
| NCM inválido          | `warning`      | "NCM deve ter 8 dígitos."                  |
| CEST inválido         | `warning`      | "CEST deve ter 7 dígitos."                 |
| Sem imagem principal  | `warning`      | "Selecione a imagem principal."            |
| Limite de imagens     | `warning`      | "Limite máximo de 10 imagens por produto." |

Todas exibidas via `showAlert()` (já disponível no layout master).

---

## 14. Segurança

- Todas as rotas protegidas por middleware `auth`
- CSRF Token em todas as requisições AJAX (`X-CSRF-TOKEN` header)
- Upload validado: `mimes:jpg,jpeg,png,webp`, `max:5120` (5 MB)
- Nomes de arquivo gerados com `Str::uuid()` — sem expor nome original
- Escape de saída com `e()` nos dados do DataTables
- Sem ACL por perfil nesta entrega

---

## 15. Critérios de Aceite (DoD)

- [ ] Listagem DataTables com filtros por status, tipo e NCM funciona via AJAX
- [ ] Wizard de 4 etapas navega corretamente com validação por etapa
- [ ] Cadastro de produto via AJAX cria registro e redireciona para listagem
- [ ] Edição de produto carrega todos os dados nos campos corretos
- [ ] Upload de múltiplas imagens com preview, ordem e imagem principal
- [ ] Cadastro inline de Categoria, Marca e Fornecedor via modal AJAX
- [ ] Select2 em todos os selects (estáticos e AJAX)
- [ ] Máscaras aplicadas em todos os campos pertinentes
- [ ] Inativação/reativação funciona sem reload da página
- [ ] Campos da Reforma Tributária (IBS, CBS, IS) presentes e validados
- [ ] Todas as operações dentro de `DB::transaction()`
- [ ] Testes automatizados passando (Feature + Unit)

---

## 16. Plano de Testes

### 16.1 Feature Tests (`tests/Feature/ProdutoV3Test.php`)

| Teste                               | Descrição                                                    |
| ----------------------------------- | ------------------------------------------------------------ |
| `test_listagem_retorna_html`        | GET `/produto` → 200 com view                                |
| `test_dados_retorna_json_datatable` | GET `/produto/dados` → JSON com `draw`, `data`               |
| `test_dados_filtra_por_ativo`       | GET `/produto/dados?ativo=1` → só ativos                     |
| `test_create_retorna_form`          | GET `/produto/novo` → 200, contém wizard                     |
| `test_store_produto_valido`         | POST `/produto` com payload mínimo → 200 `{ message, id }`   |
| `test_store_falha_sem_nome`         | POST `/produto` sem nome → 422 com erro                      |
| `test_store_com_imagens`            | POST com files → produto + imagens criadas                   |
| `test_store_com_fornecedores`       | POST com array fornecedores → pivot criada                   |
| `test_update_produto`               | PUT `/produto/{id}` → 200                                    |
| `test_inativar_produto`             | PUT com `_status_only=1, ativo=0` → inativado                |
| `test_reativar_produto`             | PUT com `_status_only=1, ativo=1` → reativado                |
| `test_sem_rota_delete`              | DELETE `/produto/{id}` → 405                                 |
| `test_campos_reforma_tributaria`    | POST com `aliquota_ibs`, `aliquota_cbs` → salva corretamente |

### 16.2 Unit Tests

| Teste                                     | Descrição                                    |
| ----------------------------------------- | -------------------------------------------- |
| `test_model_fillable_contem_novos_campos` | `$fillable` inclui campos IBS/CBS            |
| `test_model_casts_decimais`               | Casts corretos para decimais                 |
| `test_relationship_categoria`             | `$produto->categoria` retorna `ErpCategoria` |
| `test_relationship_marca`                 | `$produto->marcaRelation` retorna `ErpMarca` |
| `test_relationship_fornecedores`          | `$produto->fornecedores` retorna collection  |
| `test_relationship_imagens`               | `$produto->imagens` retorna collection       |

### 16.3 API Tests (Categorias, Marcas, Fornecedores)

| Teste                            | Descrição                                     |
| -------------------------------- | --------------------------------------------- |
| `test_listar_categorias`         | GET `/api/categorias` → JSON array            |
| `test_criar_categoria`           | POST `/api/categorias` → 201                  |
| `test_criar_categoria_duplicada` | POST com nome existente → 422                 |
| `test_listar_marcas`             | GET `/api/marcas` → JSON array                |
| `test_criar_marca`               | POST `/api/marcas` → 201                      |
| `test_buscar_fornecedores`       | GET `/api/fornecedores/buscar?q=teste` → JSON |

---

## 17. Arquivos a Criar / Modificar

### Criar:

| Arquivo                                                                 | Descrição         |
| ----------------------------------------------------------------------- | ----------------- |
| `database/migrations/xxxx_create_erp_categorias_table.php`              | Tabela categorias |
| `database/migrations/xxxx_create_erp_marcas_table.php`                  | Tabela marcas     |
| `database/migrations/xxxx_create_erp_produto_fornecedor_table.php`      | Tabela pivot      |
| `database/migrations/xxxx_alter_erp_produto_add_reforma_tributaria.php` | Novos campos      |
| `app/Models/ErpCategoria.php`                                           | Model Categoria   |
| `app/Models/ErpMarca.php`                                               | Model Marca       |
| `app/Models/ErpProdutoFornecedor.php`                                   | Model Pivot       |
| `app/Http/Controllers/CategoriaController.php`                          | Controller AJAX   |
| `app/Http/Controllers/MarcaController.php`                              | Controller AJAX   |
| `tests/Feature/ProdutoV3Test.php`                                       | Testes feature    |

### Modificar:

| Arquivo                                         | Alteração                                                                      |
| ----------------------------------------------- | ------------------------------------------------------------------------------ |
| `app/Models/ErpProduto.php`                     | Adicionar `fillable`, `casts`, relationships                                   |
| `app/Http/Controllers/ProdutoController.php`    | Adaptar `create()`, `edit()`, adicionar `syncFornecedores()`, novas validações |
| `app/Http/Controllers/FornecedorController.php` | Adicionar `buscar()`, `storeInline()`                                          |
| `resources/views/pages/produto/form.blade.php`  | Reescrever wizard 4 etapas                                                     |
| `routes/web.php`                                | Adicionar rotas API para categorias, marcas e fornecedores                     |

---

## 18. Dependências Frontend (a incluir)

| Pacote                    | Uso                      | CDN/Local                      |
| ------------------------- | ------------------------ | ------------------------------ |
| jQuery Mask Plugin        | Máscaras de input        | Já incluído (`jquery.mask.js`) |
| Select2 v4.1+             | Selects com busca e AJAX | Incluir CSS + JS               |
| Select2 Bootstrap 5 Theme | Tema visual compatível   | Incluir CSS                    |
| DataTables                | Listagem server-side     | Já incluído                    |

**Incluir no layout ou na view:**

```html
<!-- CSS -->
<link
    href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet"
/>
<link
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet"
/>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/pt-BR.js"></script>
```

---

## 19. Fora de Escopo

- Integração com SEFAZ / emissão de NF-e
- Regras de ICMS-ST (cálculo automático de MVA/BC)
- Cálculo automático de IBS/CBS (depende de regulamentação complementar)
- ACL por perfil de usuário
- Importação/exportação de produtos em lote (CSV/Excel)
- Controle de estoque por lote/série
- Migração de dados da tabela legada `erp_produtos`
- Integração com marketplace/e-commerce

---

## 20. Referência da Reforma Tributária

### 20.1 Resumo EC 132/2023 + LC 214/2025

| Tributo Novo              | Substitui    | Esfera               | Alíquota Estimada    |
| ------------------------- | ------------ | -------------------- | -------------------- |
| **CBS**                   | PIS + COFINS | Federal (União)      | ~8,8%                |
| **IBS**                   | ICMS + ISS   | Estadual + Municipal | ~17,7%               |
| **IS** (Imposto Seletivo) | — (novo)     | Federal              | Variável por produto |

**Total IVA Dual estimado:** ~26,5%

### 20.2 Cronograma Oficial

| Ano      | Evento                                                    |
| -------- | --------------------------------------------------------- |
| **2026** | Teste: CBS 0,9% + IBS 0,1% (convivem com tributos atuais) |
| **2027** | CBS entra em vigor a 100%; PIS e COFINS extintos          |
| **2029** | IBS começa a substituir ICMS/ISS gradualmente (10%/ano)   |
| **2030** | IBS 20%, ICMS/ISS reduzidos em 20%                        |
| **2031** | IBS 40%, ICMS/ISS reduzidos em 40%                        |
| **2032** | IBS 80%, ICMS/ISS reduzidos em 80%                        |
| **2033** | IBS 100%; ICMS e ISS totalmente extintos                  |

### 20.3 Impacto no ERP

- **Período de transição (2026–2033):** O sistema deve calcular e armazenar **ambos** os conjuntos de tributos (vigentes + novos)
- **Princípio do destino:** IBS/CBS cobrados no local de consumo (não na origem)
- **Não cumulatividade plena:** Crédito amplo sobre todos os insumos — essencial rastrear
- **Imposto Seletivo:** Aplicável a produtos específicos (tabaco, bebidas alcoólicas, açúcar em excesso, combustíveis fósseis, veículos poluentes, minerais)
- **NFS-e Nacional:** Layout unificado elimina portais municipais distintos
