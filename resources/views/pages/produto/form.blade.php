@extends('layouts.form')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
@endsection

@section('form')
@php
    $unidades = ['UN', 'CX', 'KG', 'G', 'L', 'ML', 'M', 'CM', 'PC', 'PCT'];
    $origens = [
        0 => '0 - Nacional',
        1 => '1 - Estrangeira (importação direta)',
        2 => '2 - Estrangeira (mercado interno)',
        3 => '3 - Nacional > 40% importadofornecedoresExistentes',
        4 => '4 - Nacional processo básico',
        5 => '5 - Nacional <= 40% importado',
        6 => '6 - Importação direta sem similar',
        7 => '7 - Mercado interno sem similar',
        8 => '8 - Nacional > 70% importado',
    ];
    $regimes = [
        '' => 'Selecione...',
        'SIMPLES' => 'Simples Nacional',
        'LUCRO_PRESUMIDO' => 'Lucro Presumido',
        'LUCRO_REAL' => 'Lucro Real',
    ];
    $imagens = $produto?->imagens ?? collect();
    $fornecedoresExistentes = $produto?->fornecedores ?? collect();
    $principalAtual = old('imagem_principal');
    if (!$principalAtual && $imagens->isNotEmpty()) {
        $principal = $imagens->firstWhere('imagem_principal', true) ?? $imagens->first();
        $principalAtual = $principal ? 'existing:' . $principal->id : null;
    }
@endphp

<div class="container-fluid" style="margin-top:20px;">
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
                        <a class="nav-link active" data-bs-toggle="tab" href="#stepBasico" data-step="0">
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
                        <a class="nav-link" data-bs-toggle="tab" href="#stepFornecedores" data-step="1">
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
                        <a class="nav-link" data-bs-toggle="tab" href="#stepTributacao" data-step="2">
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
                        <a class="nav-link" data-bs-toggle="tab" href="#stepImagens" data-step="3">
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

                <form id="form-produto" novalidate enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="produto_id" value="{{ optional($produto)->id }}">

                    <div class="tab-content pt-3" data-wizard-content>
                        <!-- Step 1: Informações Básicas -->
                        <div class="tab-pane fade show active" id="stepBasico">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label">Nome *</label>
                                    <input class="form-control" id="nome" name="nome" maxlength="255" required value="{{ old('nome', optional($produto)->nome) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Código SKU *</label>
                                    <input class="form-control" id="codigo_sku" name="codigo_sku" maxlength="100" required value="{{ old('codigo_sku', optional($produto)->codigo_sku) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">GTIN/Cód. Barras</label>
                                    <input class="form-control" id="codigo_barras" name="codigo_barras" maxlength="14" value="{{ old('codigo_barras', optional($produto)->codigo_barras) }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Categoria</label>
                                    <div class="input-group">
                                        <select name="categoria_id" id="categoria_id" class="form-select select2-ajax" data-name="Categoria" data-url="{{ route('api.categorias.listar') }}" data-placeholder="Selecione uma categoria">
                                            @if($produto?->categoria)
                                                <option value="{{ $produto->categoria_id }}" selected>{{ $produto->categoria->nome }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Marca</label>
                                    <div class="input-group">
                                        <select name="marca_id" id="marca_id" class="form-select select2-ajax" data-name="Marca" data-url="{{ route('api.marcas.listar') }}" data-placeholder="Selecione uma marca">
                                            @if($produto?->marcaRelation)
                                                <option value="{{ $produto->marca_id }}" selected>{{ $produto->marcaRelation->nome }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Unidade Medida *</label>
                                    <select name="unidade_medida" id="unidade_medida" class="form-select select2-static" required>
                                        @foreach ($unidades as $u)
                                            <option value="{{ $u }}" {{ old('unidade_medida', optional($produto)->unidade_medida ?? 'UN') === $u ? 'selected' : '' }}>{{ $u }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Status</label>
                                    <input type="hidden" name="ativo" value="0">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="ativo" name="ativo" value="1" {{ old('ativo', optional($produto)->ativo ?? 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ativo">Ativo</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Descrição</label>
                                    <textarea class="form-control" rows="3" id="descricao" name="descricao">{{ old('descricao', optional($produto)->descricao) }}</textarea>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Preço Custo *</label>
                                    <input class="form-control js-decimal-4" id="preco_custo" name="preco_custo" required value="{{ old('preco_custo', optional($produto)->preco_custo ?? '0,0000') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Preço Venda *</label>
                                    <input class="form-control js-decimal-4" id="preco_venda" name="preco_venda" required value="{{ old('preco_venda', optional($produto)->preco_venda ?? '0,0000') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Qtd. Estoque *</label>
                                    <input class="form-control js-decimal-4" id="quantidade_estoque" name="quantidade_estoque" required value="{{ old('quantidade_estoque', optional($produto)->quantidade_estoque ?? '0,0000') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Estoque Mínimo *</label>
                                    <input class="form-control js-decimal-4" id="estoque_minimo" name="estoque_minimo" required value="{{ old('estoque_minimo', optional($produto)->estoque_minimo ?? '0,0000') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Peso (kg)</label>
                                    <input class="form-control js-decimal-4" id="peso" name="peso" value="{{ old('peso', optional($produto)->peso) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Largura (cm)</label>
                                    <input class="form-control js-decimal-4" id="largura" name="largura" value="{{ old('largura', optional($produto)->largura) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Altura (cm)</label>
                                    <input class="form-control js-decimal-4" id="altura" name="altura" value="{{ old('altura', optional($produto)->altura) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Comprimento (cm)</label>
                                    <input class="form-control js-decimal-4" id="comprimento" name="comprimento" value="{{ old('comprimento', optional($produto)->comprimento) }}">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Observações</label>
                                    <textarea class="form-control" rows="2" name="observacoes">{{ old('observacoes', optional($produto)->observacoes) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Fornecedores -->
                        <div class="tab-pane fade" id="stepFornecedores">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Fornecedores vinculados</h6>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-fornecedor">
                                        <i class="ti ti-plus me-1"></i>Adicionar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalNovoFornecedor">
                                        <i class="ti ti-user-plus me-1"></i>Novo Fornecedor
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="tabela-fornecedores">
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
                                        {{-- Linhas serão preenchidas via JS --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Step 3: Tributação -->
                        <div class="tab-pane fade" id="stepTributacao">
                            <!-- Seção A: Dados Fiscais Gerais -->
                            <h6 class="mb-3"><i class="ti ti-file-text me-1"></i>Dados Fiscais Gerais</h6>
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">NCM</label>
                                    <input class="form-control" id="ncm" name="ncm" maxlength="8" value="{{ old('ncm', optional($produto)->ncm) }}" placeholder="0000.00.00">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">CEST</label>
                                    <input class="form-control" id="cest" name="cest" maxlength="7" value="{{ old('cest', optional($produto)->cest) }}" placeholder="00.000.00">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Código ANP</label>
                                    <input class="form-control" id="codigo_anp" name="codigo_anp" maxlength="20" value="{{ old('codigo_anp', optional($produto)->codigo_anp) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Origem Mercadoria *</label>
                                    <select class="form-select select2-static" id="origem_mercadoria" name="origem_mercadoria" required>
                                        @foreach ($origens as $k => $v)
                                            <option value="{{ $k }}" {{ (int) old('origem_mercadoria', optional($produto)->origem_mercadoria ?? 0) === (int) $k ? 'selected' : '' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">EX TIPI</label>
                                    <input class="form-control" id="ex_tipi" name="ex_tipi" maxlength="5" value="{{ old('ex_tipi', optional($produto)->ex_tipi) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Cód. Benefício Fiscal</label>
                                    <input class="form-control" id="codigo_beneficio_fiscal" name="codigo_beneficio_fiscal" maxlength="20" value="{{ old('codigo_beneficio_fiscal', optional($produto)->codigo_beneficio_fiscal) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Regime Tributário</label>
                                    <select class="form-select select2-static" id="regime_tributario" name="regime_tributario">
                                        @foreach ($regimes as $k => $v)
                                            <option value="{{ $k }}" {{ old('regime_tributario', optional($produto)->regime_tributario) === $k ? 'selected' : '' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Seção B: Tributos Vigentes (ICMS/PIS/COFINS/IPI) -->
                            <h6 class="mb-3"><i class="ti ti-calculator me-1"></i>Tributos Vigentes (ICMS/PIS/COFINS/IPI)</h6>
                            <p class="text-muted small mb-3">Campos obrigatórios durante o período de transição 2026–2033. Após 2033 serão deprecados.</p>
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">CST ICMS</label>
                                    <input class="form-control" name="cst_icms" maxlength="3" value="{{ old('cst_icms', optional($produto)->cst_icms) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">CSOSN</label>
                                    <input class="form-control" name="csosn" maxlength="3" value="{{ old('csosn', optional($produto)->csosn) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Mod. BC ICMS</label>
                                    <input class="form-control" name="modalidade_bc_icms" maxlength="2" value="{{ old('modalidade_bc_icms', optional($produto)->modalidade_bc_icms) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Alíq. ICMS (%)</label>
                                    <input class="form-control js-percent" name="aliquota_icms" value="{{ old('aliquota_icms', optional($produto)->aliquota_icms) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Redução BC (%)</label>
                                    <input class="form-control js-percent" name="reducao_bc_icms" value="{{ old('reducao_bc_icms', optional($produto)->reducao_bc_icms) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">MVA (%)</label>
                                    <input class="form-control js-percent" name="mva_icms" value="{{ old('mva_icms', optional($produto)->mva_icms) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Alíq. ICMS ST (%)</label>
                                    <input class="form-control js-percent" name="aliquota_icms_st" value="{{ old('aliquota_icms_st', optional($produto)->aliquota_icms_st) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Alíq. FCP (%)</label>
                                    <input class="form-control js-percent" name="aliquota_fcp" value="{{ old('aliquota_fcp', optional($produto)->aliquota_fcp) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Alíq. FCP ST (%)</label>
                                    <input class="form-control js-percent" name="aliquota_fcp_st" value="{{ old('aliquota_fcp_st', optional($produto)->aliquota_fcp_st) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">CST PIS</label>
                                    <input class="form-control" name="cst_pis" maxlength="3" value="{{ old('cst_pis', optional($produto)->cst_pis) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Alíq. PIS (%)</label>
                                    <input class="form-control js-percent" name="aliquota_pis" value="{{ old('aliquota_pis', optional($produto)->aliquota_pis) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Base PIS</label>
                                    <input class="form-control js-decimal-4" name="base_calculo_pis" value="{{ old('base_calculo_pis', optional($produto)->base_calculo_pis) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">CST COFINS</label>
                                    <input class="form-control" name="cst_cofins" maxlength="3" value="{{ old('cst_cofins', optional($produto)->cst_cofins) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Alíq. COFINS (%)</label>
                                    <input class="form-control js-percent" name="aliquota_cofins" value="{{ old('aliquota_cofins', optional($produto)->aliquota_cofins) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Base COFINS</label>
                                    <input class="form-control js-decimal-4" name="base_calculo_cofins" value="{{ old('base_calculo_cofins', optional($produto)->base_calculo_cofins) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">CST IPI</label>
                                    <input class="form-control" name="cst_ipi" maxlength="3" value="{{ old('cst_ipi', optional($produto)->cst_ipi) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Cód. Enquad. IPI</label>
                                    <input class="form-control" name="codigo_enquadramento_ipi" maxlength="5" value="{{ old('codigo_enquadramento_ipi', optional($produto)->codigo_enquadramento_ipi) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Alíq. IPI (%)</label>
                                    <input class="form-control js-percent" name="aliquota_ipi" value="{{ old('aliquota_ipi', optional($produto)->aliquota_ipi) }}">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Seção C: Reforma Tributária (IBS/CBS/IS) -->
                            <h6 class="mb-3"><i class="ti ti-exchange me-1"></i>Reforma Tributária (IBS / CBS / IS) — Transição 2026–2033</h6>
                            <div class="alert alert-info mb-3">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>Campos da Reforma Tributária (EC 132/2023).</strong> Durante o período de transição (2026–2033), é necessário manter tanto os tributos vigentes quanto os novos.
                            </div>
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label" title="Substitui ICMS + ISS">Alíquota IBS (%)</label>
                                    <input class="form-control js-percent" name="aliquota_ibs" value="{{ old('aliquota_ibs', optional($produto)->aliquota_ibs) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Redução BC IBS (%)</label>
                                    <input class="form-control js-percent" name="reducao_bc_ibs" value="{{ old('reducao_bc_ibs', optional($produto)->reducao_bc_ibs) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" title="Substitui PIS + COFINS">Alíquota CBS (%)</label>
                                    <input class="form-control js-percent" name="aliquota_cbs" value="{{ old('aliquota_cbs', optional($produto)->aliquota_cbs) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Redução BC CBS (%)</label>
                                    <input class="form-control js-percent" name="reducao_bc_cbs" value="{{ old('reducao_bc_cbs', optional($produto)->reducao_bc_cbs) }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Sujeito a Imp. Seletivo?</label>
                                    <input type="hidden" name="sujeito_imposto_seletivo" value="0">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="sujeito_imposto_seletivo" name="sujeito_imposto_seletivo" value="1" {{ old('sujeito_imposto_seletivo', optional($produto)->sujeito_imposto_seletivo) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sujeito_imposto_seletivo">Sim</label>
                                    </div>
                                </div>
                                <div class="col-md-2" id="campo-aliquota-is" style="{{ old('sujeito_imposto_seletivo', optional($produto)->sujeito_imposto_seletivo) ? '' : 'display:none;' }}">
                                    <label class="form-label">Alíq. Imp. Seletivo (%)</label>
                                    <input class="form-control js-percent" name="aliquota_imposto_seletivo" value="{{ old('aliquota_imposto_seletivo', optional($produto)->aliquota_imposto_seletivo) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Imagens -->
                        <div class="tab-pane fade" id="stepImagens">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Upload de imagens</label>
                                    <input type="file" class="form-control" id="imagens" name="imagens[]" accept=".jpg,.jpeg,.png,.webp" multiple>
                                    <div class="form-text">JPG/PNG/WEBP, máximo 10 imagens por produto, 5 MB cada.</div>
                                </div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-12">
                                    <h6>Imagens existentes</h6>
                                    <div class="row g-3" id="existing-images-container">
                                        @forelse ($imagens as $imagem)
                                            <div class="col-md-3 existing-image-card" data-image-id="{{ $imagem->id }}">
                                                <div class="card h-100 border">
                                                    <img src="{{ asset('storage/' . ltrim($imagem->caminho_arquivo, '/')) }}" class="card-img-top" style="height:170px;object-fit:cover;" alt="Imagem {{ $imagem->id }}">
                                                    <div class="card-body p-2">
                                                        <label class="form-label mb-1">Ordem</label>
                                                        <input type="number" min="0" class="form-control form-control-sm mb-2" name="ordem_existente[{{ $imagem->id }}]" value="{{ old('ordem_existente.' . $imagem->id, $imagem->ordem) }}">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="radio" name="imagem_principal" id="principal_existing_{{ $imagem->id }}" value="existing:{{ $imagem->id }}" {{ $principalAtual === 'existing:' . $imagem->id ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="principal_existing_{{ $imagem->id }}">Principal</label>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-danger w-100 js-remove-existing" data-id="{{ $imagem->id }}">Remover</button>
                                                        <input type="checkbox" class="d-none js-remove-existing-input" name="imagens_remover[]" value="{{ $imagem->id }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-muted" id="existing-empty">Nenhuma imagem cadastrada.</div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h6>Novas imagens</h6>
                                    <div class="row g-3" id="new-images-container">
                                        <div class="col-12 text-muted" id="new-empty">Nenhuma nova imagem selecionada.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" id="btn-prev" class="btn btn-outline-secondary" data-wizard-prev>
                    <i class="ti ti-arrow-left me-1"></i>Voltar etapa
                </button>
                <button type="button" id="btn-next" class="btn btn-primary" data-wizard-next>
                    Avançar etapa<i class="ti ti-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Categoria -->
<div class="modal fade" id="modalNovaCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="cat_nome" maxlength="150" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control" id="cat_descricao" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-salvar-categoria">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova Marca -->
<div class="modal fade" id="modalNovaMarca" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Marca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="marca_nome" maxlength="150" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-salvar-marca">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Fornecedor -->
<div class="modal fade" id="modalNovoFornecedor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Fornecedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nome/Razão Social *</label>
                    <input type="text" class="form-control" id="forn_nome" maxlength="255" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">CPF/CNPJ *</label>
                    <input type="text" class="form-control" id="forn_cpf_cnpj" maxlength="18" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-salvar-fornecedor">Salvar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/pt-BR.js"></script>
<script>
$(function () {
    const csrfToken = '{{ csrf_token() }}';
    const $fileInput = $('#imagens');
    let selectedFiles = [];
    let currentStep = 0;
    const totalSteps = 4;

    // Fornecedores existentes para carregar na edição
    const fornecedoresExistentes = @json($fornecedoresExistentes);

    // ==================== MASKS ====================
    function applyMasks() {
        if (!$.fn.mask) return;

        $('#codigo_barras').mask('00000000000000');
        $('#ncm').mask('0000.00.00');
        $('#cest').mask('00.000.00');
        $('input[name="cst_icms"]').mask('000');
        $('input[name="csosn"]').mask('000');
        $('input[name="modalidade_bc_icms"]').mask('00');
        $('input[name="cst_pis"]').mask('000');
        $('input[name="cst_cofins"]').mask('000');
        $('input[name="cst_ipi"]').mask('000');
        $('input[name="codigo_enquadramento_ipi"]').mask('00000');
        $('#forn_cpf_cnpj').mask('00.000.000/0000-00');

        $('.js-percent').mask('##0,00', { reverse: true });
        $('.js-decimal-4').mask('0.00' { reverse: true });
    }

    // ==================== SELECT2 ====================
    function initSelect2() {
        // Select2 estático
        $('.select2-static').select2({
            theme: 'bootstrap-5',
            width: '100%',
            language: 'pt-BR',
            allowClear: true,
            placeholder: 'Selecione...'
        });

        // Select2 AJAX (categorias, marcas)
        $('.select2-ajax').each(function () {
            var name = $(this).data('name');
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                language: 'pt-BR',
                allowClear: true,
                placeholder: $(this).data('placeholder') || 'Selecione...',
                ajax: {
                    url: $(this).data('url'),
                    dataType: 'json',
                    delay: 300,
                    data: (params) => ({ q: params.term }),
                    processResults: (data) => ({ results: data }),
                    cache: true
                },
                minimumInputLength: 0,
                language: {
                    noResults: function() {
                        return `<button type='button' class='btn btn-sm btn-link p-0' data-bs-toggle="modal" data-bs-target="#modalNova${name}" title="Nova ${name}" >Criar nova ${name}</button>`;
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
        });
    }

    function initSelect2Fornecedor($el, selectedData = null) {
        $el.select2({
            theme: 'bootstrap-5',
            width: '100%',
            language: 'pt-BR',
            placeholder: 'Buscar fornecedor...',
            ajax: {
                url: '{{ route("api.fornecedores.buscar") }}',
                dataType: 'json',
                delay: 300,
                data: (params) => ({ q: params.term }),
                processResults: (data) => data,
                cache: true
            },
            minimumInputLength: 2,
            templateResult: (item) => item.text
                ? $(`<span>${item.text} <small class="text-muted">${item.cpf_cnpj || ''}</small></span>`)
                : item.text
        });

        if (selectedData) {
            const option = new Option(selectedData.text, selectedData.id, true, true);
            $el.append(option).trigger('change');
        }
    }

    // ==================== WIZARD ====================
    function updateWizard() {
        $('.wizard-tabs .nav-link').removeClass('active');
        $(`.wizard-tabs .nav-link[data-step="${currentStep}"]`).addClass('active').tab('show');

        $('#btn-prev').prop('disabled', currentStep === 0);
        $('#btn-next').html(currentStep === totalSteps - 1
            ? 'Finalizar e Salvar <i class="ti ti-check ms-1"></i>'
            : 'Avançar etapa <i class="ti ti-arrow-right ms-1"></i>');
    }

    function validateCurrentStep() {
        const $pane = $(`.tab-pane`).eq(currentStep);
        let valid = true;

        $pane.find('input[required], select[required], textarea[required]').each(function () {
            if (!$(this).is(':visible') || $(this).is(':disabled')) return;
            if (typeof this.checkValidity === 'function' && !this.checkValidity()) {
                valid = false;
                this.reportValidity();
                return false;
            }
        });

        if (!valid) return false;

        // Validação específica step tributação
        if (currentStep === 2) {
            const ncm = ($('#ncm').val() || '').replace(/\D/g, '');
            const cest = ($('#cest').val() || '').replace(/\D/g, '');
            if (ncm.length > 0 && ncm.length !== 8) {
                showAlert('NCM deve ter 8 dígitos.', 'warning');
                return false;
            }
            if (cest.length > 0 && cest.length !== 7) {
                showAlert('CEST deve ter 7 dígitos.', 'warning');
                return false;
            }
        }

        return true;
    }

    function validateFinalStep() {
        const visibleExisting = $('.existing-image-card:visible').length;
        const totalImages = visibleExisting + selectedFiles.length;
        if (totalImages > 0 && $('input[name="imagem_principal"]:checked').length === 0) {
            showAlert('Selecione a imagem principal.', 'warning');
            return false;
        }
        if (totalImages > 10) {
            showAlert('Limite máximo de 10 imagens por produto.', 'warning');
            return false;
        }
        return true;
    }

    // ==================== IMAGENS ====================
    function syncFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        $fileInput[0].files = dt.files;
    }

    function renderNewImages() {
        const $container = $('#new-images-container');
        $container.empty();

        if (!selectedFiles.length) {
            $container.append('<div class="col-12 text-muted" id="new-empty">Nenhuma nova imagem selecionada.</div>');
            return;
        }

        selectedFiles.forEach((file, index) => {
            const url = URL.createObjectURL(file);
            $container.append(`
                <div class="col-md-3">
                    <div class="card h-100 border">
                        <img src="${url}" class="card-img-top" style="height:170px;object-fit:cover;">
                        <div class="card-body p-2">
                            <label class="form-label mb-1">Ordem</label>
                            <input type="number" min="0" class="form-control form-control-sm mb-2" name="ordem_nova[${index}]" value="${1000 + index}">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="imagem_principal" id="principal_new_${index}" value="new:${index}">
                                <label class="form-check-label" for="principal_new_${index}">Principal</label>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger w-100 js-remove-new" data-index="${index}">Remover</button>
                        </div>
                    </div>
                </div>`);
        });

        syncFileInput();
    }

    // ==================== FORNECEDORES ====================
    function addFornecedorRow(data = null) {
        const idx = $('#fornecedores-body tr').length;
        const principalIdx = data?.pivot?.principal ? idx : null;

        const row = `<tr data-index="${idx}">
            <td><select name="fornecedores[${idx}][fornecedor_id]" class="form-select select2-fornecedor" data-placeholder="Buscar fornecedor..."></select></td>
            <td><input type="text" class="form-control" name="fornecedores[${idx}][codigo_fornecedor]" maxlength="100" value="${data?.pivot?.codigo_fornecedor || ''}"></td>
            <td><input type="text" class="form-control js-decimal-4" name="fornecedores[${idx}][preco_fornecedor]" value="${data?.pivot?.preco_fornecedor || ''}"></td>
            <td><input type="number" min="0" class="form-control" name="fornecedores[${idx}][prazo_entrega_dias]" value="${data?.pivot?.prazo_entrega_dias || ''}"></td>
            <td class="text-center"><input type="radio" name="fornecedor_principal" value="${idx}" class="form-check-input" ${data?.pivot?.principal ? 'checked' : ''}></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger js-remove-fornecedor"><i class="ti ti-trash"></i></button></td>
        </tr>`;

        $('#fornecedores-body').append(row);

        const $select = $(`tr[data-index="${idx}"] .select2-fornecedor`);
        initSelect2Fornecedor($select, data ? { id: data.id, text: data.text, cpf_cnpj: data.cpf_cnpj } : null);

        applyMasks();
    }

    function loadExistingFornecedores() {
        fornecedoresExistentes.forEach(f => addFornecedorRow(f));
    }

    // ==================== SUBMIT ====================
    function submitForm() {
        syncFileInput();
        const produtoId = $('#produto_id').val();
        const isUpdate = !!produtoId;
        const formData = new FormData($('#form-produto')[0]);

        if (isUpdate) formData.append('_method', 'PUT');

        // Processar fornecedores principal
        $('#fornecedores-body tr').each(function (idx) {
            const isPrincipal = $('input[name="fornecedor_principal"]:checked').val() == idx ? 1 : 0;
            formData.set(`fornecedores[${idx}][principal]`, isPrincipal);
        });

        const $btn = $('#btn-salvar');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Salvando...');

        $.ajax({
            url: isUpdate ? `/produto/${produtoId}` : '/produto',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            success: function (res) {
                showAlert(res.message || 'Produto salvo com sucesso!', 'success');
                window.location.href = '/produto';
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.keys(errors).forEach((field) => {
                        $(`[name="${field}"]`).addClass('is-invalid');
                    });
                    showAlert(Object.values(errors)[0][0], 'danger');
                } else {
                    showAlert(xhr.responseJSON?.message || 'Erro ao salvar o produto.', 'danger');
                }
                $btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Salvar');
            }
        });
    }

    // ==================== EVENTS ====================

    // Wizard navigation
    $('.wizard-tabs .nav-link').on('click', function (e) {
        e.preventDefault();
        const step = Number($(this).data('step'));
        if (step > currentStep && !validateCurrentStep()) return;
        currentStep = step;
        updateWizard();
    });

    $('#btn-prev').on('click', function () {
        if (currentStep > 0) {
            currentStep -= 1;
            updateWizard();
        }
    });

    $('#btn-next').on('click', function () {
        if (!validateCurrentStep()) return;
        if (currentStep === totalSteps - 1) {
            if (!validateFinalStep()) return;
            submitForm();
            return;
        }
        currentStep += 1;
        updateWizard();
    });

    $('#btn-salvar').on('click', function () {
        if (!validateCurrentStep()) return;
        if (currentStep === totalSteps - 1 && !validateFinalStep()) return;
        submitForm();
    });

    // Imagens
    $fileInput.on('change', function () {
        const files = Array.from(this.files || []);
        if (!files.length) return;
        selectedFiles = selectedFiles.concat(files);
        renderNewImages();
    });

    $(document).on('click', '.js-remove-new', function () {
        const index = Number($(this).data('index'));
        selectedFiles.splice(index, 1);
        renderNewImages();
    });

    $(document).on('click', '.js-remove-existing', function () {
        const id = $(this).data('id');
        const $card = $(`.existing-image-card[data-image-id="${id}"]`);
        $card.find('.js-remove-existing-input').prop('checked', true);
        $card.hide();
        if (!$('.existing-image-card:visible').length && !$('#existing-empty').length) {
            $('#existing-images-container').append('<div class="col-12 text-muted" id="existing-empty">Nenhuma imagem cadastrada.</div>');
        }
    });

    // Fornecedores
    $('#btn-add-fornecedor').on('click', function () {
        addFornecedorRow();
    });

    $(document).on('click', '.js-remove-fornecedor', function () {
        $(this).closest('tr').remove();
        // Reindexar
        $('#fornecedores-body tr').each(function (idx) {
            $(this).attr('data-index', idx);
            $(this).find('[name*="fornecedores["]').each(function () {
                const name = $(this).attr('name').replace(/fornecedores\[\d+\]/, `fornecedores[${idx}]`);
                $(this).attr('name', name);
            });
            $(this).find('input[name="fornecedor_principal"]').val(idx);
        });
    });

    // Imposto Seletivo toggle
    $('#sujeito_imposto_seletivo').on('change', function () {
        $('#campo-aliquota-is').toggle($(this).is(':checked'));
    });

    // Modal Categoria
    $('#btn-salvar-categoria').on('click', function () {
        const nome = $('#cat_nome').val().trim();
        if (!nome) {
            showAlert('Informe o nome da categoria.', 'warning');
            return;
        }

        $.ajax({
            url: '{{ route("api.categorias.store") }}',
            method: 'POST',
            data: { nome, descricao: $('#cat_descricao').val(), _token: csrfToken },
            headers: { 'Accept': 'application/json' },
            success: function (res) {
                const option = new Option(res.nome, res.id, true, true);
                $('#categoria_id').append(option).trigger('change');
                $('#modalNovaCategoria').modal('hide');
                $('#cat_nome').val('');
                $('#cat_descricao').val('');
                showAlert(res.message, 'success');
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Erro ao criar categoria.';
                showAlert(msg, 'danger');
            }
        });
    });

    // Modal Marca
    $('#btn-salvar-marca').on('click', function () {
        const nome = $('#marca_nome').val().trim();
        if (!nome) {
            showAlert('Informe o nome da marca.', 'warning');
            return;
        }

        $.ajax({
            url: '{{ route("api.marcas.store") }}',
            method: 'POST',
            data: { nome, _token: csrfToken },
            headers: { 'Accept': 'application/json' },
            success: function (res) {
                const option = new Option(res.nome, res.id, true, true);
                $('#marca_id').append(option).trigger('change');
                $('#modalNovaMarca').modal('hide');
                $('#marca_nome').val('');
                showAlert(res.message, 'success');
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Erro ao criar marca.';
                showAlert(msg, 'danger');
            }
        });
    });

    // Modal Fornecedor
    $('#btn-salvar-fornecedor').on('click', function () {
        const nome = $('#forn_nome').val().trim();
        const cpf_cnpj = $('#forn_cpf_cnpj').val().trim();
        if (!nome || !cpf_cnpj) {
            showAlert('Preencha nome e CPF/CNPJ.', 'warning');
            return;
        }

        $.ajax({
            url: '{{ route("api.fornecedores.store") }}',
            method: 'POST',
            data: { nome, cpf_cnpj, _token: csrfToken },
            headers: { 'Accept': 'application/json' },
            success: function (res) {
                addFornecedorRow({ id: res.id, text: res.nome, cpf_cnpj: cpf_cnpj, pivot: {} });
                $('#modalNovoFornecedor').modal('hide');
                $('#forn_nome').val('');
                $('#forn_cpf_cnpj').val('');
                showAlert(res.message, 'success');
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || 'Erro ao criar fornecedor.';
                showAlert(msg, 'danger');
            }
        });
    });

    // Limpar modal ao fechar
    $('#modalNovaCategoria').on('hidden.bs.modal', function () {
        $('#cat_nome, #cat_descricao').val('');
    });
    $('#modalNovaMarca').on('hidden.bs.modal', function () {
        $('#marca_nome').val('');
    });
    $('#modalNovoFornecedor').on('hidden.bs.modal', function () {
        $('#forn_nome, #forn_cpf_cnpj').val('');
    });

    // ==================== INIT ====================
    applyMasks();
    initSelect2();
    loadExistingFornecedores();
    updateWizard();
});
</script>
@endsection
