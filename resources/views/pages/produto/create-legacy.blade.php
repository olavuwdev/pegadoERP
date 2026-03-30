@extends('layouts.form')

@section('form')
<div class="container-fluid" style="margin-top: 20px;">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4 class="card-title mb-0">Cadastrar Produto</h4>
                <div class="text-muted small">Layout antigo de cadastro</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ url('/produto') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Voltar
                </a>
                <button type="submit" form="form-produto-legacy" id="btn-salvar" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i> Salvar
                </button>
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs wizard-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tabGeralLegacy">Dados gerais</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tabFiscalLegacy">Fiscal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tabEstoqueLegacy">Estoque</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tabObsLegacy">Observacoes</a>
                </li>
            </ul>

            @php
                $unidades = ['UN','PC','CX','KG','G','L','ML','M','CM','MT','PCT','SC'];
                $origens = [
                    0 => '0 - Nacional',
                    1 => '1 - Estrangeira (importacao direta)',
                    2 => '2 - Estrangeira (mercado interno)',
                    3 => '3 - Nacional (conteudo import. > 40%)',
                    4 => '4 - Nacional (processo produtivo basico)',
                    5 => '5 - Nacional (conteudo import. <= 40%)',
                    6 => '6 - Estrangeira (importacao direta, sem similar)',
                    7 => '7 - Estrangeira (mercado interno, sem similar)',
                    8 => '8 - Nacional (conteudo import. > 70%)',
                ];
            @endphp

            <form id="form-produto-legacy" class="needs-validation pt-3" novalidate enctype="multipart/form-data">
                @csrf
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tabGeralLegacy">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label for="codigo" class="form-label">Codigo</label>
                                <input type="text" id="codigo" name="codigo" class="form-control" maxlength="100" placeholder="Ex.: 0001">
                            </div>
                            <div class="col-md-7">
                                <label for="descricao" class="form-label">Descricao</label>
                                <input type="text" id="descricao" name="descricao" class="form-control" required maxlength="255" placeholder="Ex.: GABINETE ATX">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ativo</label>
                                <div class="form-check form-switch mt-1">
                                    <input class="form-check-input" type="checkbox" role="switch" id="ativo" name="ativo" value="1" checked>
                                    <label class="form-check-label" for="ativo">Registro ativo</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="referencia" class="form-label">Referencia</label>
                                <input type="text" id="referencia" name="referencia" class="form-control" maxlength="100" placeholder="Ex.: GAB_ATX">
                            </div>
                            <div class="col-md-4">
                                <label for="gtin" class="form-label">GTIN</label>
                                <input type="text" id="gtin" name="gtin" class="form-control" maxlength="14" placeholder="Somente numeros">
                            </div>
                            <div class="col-md-4">
                                <label for="unidade_comercial" class="form-label">Unidade</label>
                                <select id="unidade_comercial" name="unidade_comercial" class="form-select" required>
                                    @foreach ($unidades as $u)
                                        <option value="{{ $u }}" {{ $u === 'UN' ? 'selected' : '' }}>{{ $u }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" id="marca" name="marca" class="form-control" maxlength="150">
                            </div>
                            <div class="col-md-2">
                                <label for="peso_liquido" class="form-label">Peso liquido</label>
                                <input type="text" id="peso_liquido" name="peso_liquido" class="form-control" placeholder="0,0000">
                            </div>
                            <div class="col-md-2">
                                <label for="peso_bruto" class="form-label">Peso bruto</label>
                                <input type="text" id="peso_bruto" name="peso_bruto" class="form-control" placeholder="0,0000">
                            </div>
                            <div class="col-md-2">
                                <label for="preco_custo" class="form-label">Preco custo</label>
                                <input type="text" id="preco_custo" name="preco_custo" class="form-control" placeholder="0,00">
                            </div>
                            <div class="col-md-2">
                                <label for="preco_venda" class="form-label">Preco venda</label>
                                <input type="text" id="preco_venda" name="preco_venda" class="form-control" placeholder="0,00">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tabFiscalLegacy">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="ncm" class="form-label">NCM</label>
                                <input type="text" id="ncm" name="ncm" class="form-control" maxlength="8" placeholder="8 digitos">
                            </div>
                            <div class="col-md-3">
                                <label for="cest" class="form-label">CEST</label>
                                <input type="text" id="cest" name="cest" class="form-control" maxlength="7" placeholder="7 digitos">
                            </div>
                            <div class="col-md-3">
                                <label for="origem" class="form-label">Origem</label>
                                <select id="origem" name="origem" class="form-select">
                                    @foreach ($origens as $k => $label)
                                        <option value="{{ $k }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="icms_cst" class="form-label">CST/CSOSN ICMS</label>
                                <input type="text" id="icms_cst" name="icms_cst" class="form-control" maxlength="3">
                            </div>
                            <div class="col-md-3">
                                <label for="icms_aliquota" class="form-label">ICMS (%)</label>
                                <input type="text" id="icms_aliquota" name="icms_aliquota" class="form-control" placeholder="0,00">
                            </div>
                            <div class="col-md-3">
                                <label for="pis_cst" class="form-label">CST PIS</label>
                                <input type="text" id="pis_cst" name="pis_cst" class="form-control" maxlength="3">
                            </div>
                            <div class="col-md-3">
                                <label for="pis_aliquota" class="form-label">PIS (%)</label>
                                <input type="text" id="pis_aliquota" name="pis_aliquota" class="form-control" placeholder="0,00">
                            </div>
                            <div class="col-md-3">
                                <label for="cofins_cst" class="form-label">CST COFINS</label>
                                <input type="text" id="cofins_cst" name="cofins_cst" class="form-control" maxlength="3">
                            </div>
                            <div class="col-md-3">
                                <label for="cofins_aliquota" class="form-label">COFINS (%)</label>
                                <input type="text" id="cofins_aliquota" name="cofins_aliquota" class="form-control" placeholder="0,00">
                            </div>
                            <div class="col-md-3">
                                <label for="ipi_cst" class="form-label">CST IPI</label>
                                <input type="text" id="ipi_cst" name="ipi_cst" class="form-control" maxlength="3">
                            </div>
                            <div class="col-md-3">
                                <label for="ipi_aliquota" class="form-label">IPI (%)</label>
                                <input type="text" id="ipi_aliquota" name="ipi_aliquota" class="form-control" placeholder="0,00">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tabEstoqueLegacy">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="estoque_atual" class="form-label">Estoque atual</label>
                                <input type="text" id="estoque_atual" name="estoque_atual" class="form-control" placeholder="0,0000">
                            </div>
                            <div class="col-md-3">
                                <label for="estoque_minimo" class="form-label">Estoque minimo</label>
                                <input type="text" id="estoque_minimo" name="estoque_minimo" class="form-control" placeholder="0,0000">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tabObsLegacy">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="foto" class="form-label">Foto do produto</label>
                                <input type="file" id="foto" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                            </div>
                            <div class="col-md-6">
                                <label for="observacoes" class="form-label">Observacoes</label>
                                <textarea id="observacoes" name="observacoes" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    if ($.fn.mask) {
        $('#ncm').mask('00000000');
        $('#cest').mask('0000000');
        $('#gtin').mask('00000000000000');
    }

    $('#form-produto-legacy').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        if (form.checkValidity && !form.checkValidity()) {
            form.reportValidity();
            return;
        }

        $('#btn-salvar').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Salvando...');

        const rawCodigo = ($('#codigo').val() || '').trim();
        const rawReferencia = ($('#referencia').val() || '').trim();
        const fallbackSku = `SKU-${Date.now()}`;
        const codigoSku = rawCodigo || rawReferencia || fallbackSku;
        const nome = ($('#descricao').val() || '').trim();
        const unidade = ($('#unidade_comercial').val() || 'UN').trim();
        const pesoLiquido = ($('#peso_liquido').val() || '').trim();
        const pesoBruto = ($('#peso_bruto').val() || '').trim();
        const peso = pesoBruto || pesoLiquido;

        const payload = new FormData();
        payload.append('tipo', 'PRODUTO');
        payload.append('nome', nome);
        payload.append('codigo_sku', codigoSku);
        payload.append('codigo_barras', ($('#gtin').val() || '').trim());
        payload.append('descricao', nome);
        payload.append('marca', ($('#marca').val() || '').trim());
        payload.append('preco_custo', ($('#preco_custo').val() || '0,00').trim());
        payload.append('preco_venda', ($('#preco_venda').val() || '0,00').trim());
        payload.append('quantidade_estoque', ($('#estoque_atual').val() || '0,0000').trim());
        payload.append('estoque_minimo', ($('#estoque_minimo').val() || '0,0000').trim());
        payload.append('unidade_medida', unidade);
        payload.append('peso', peso);
        payload.append('ncm', ($('#ncm').val() || '').trim());
        payload.append('cest', ($('#cest').val() || '').trim());
        payload.append('origem_mercadoria', ($('#origem').val() || '0').trim());
        payload.append('cst_icms', ($('#icms_cst').val() || '').trim());
        payload.append('aliquota_icms', ($('#icms_aliquota').val() || '').trim());
        payload.append('cst_pis', ($('#pis_cst').val() || '').trim());
        payload.append('aliquota_pis', ($('#pis_aliquota').val() || '').trim());
        payload.append('cst_cofins', ($('#cofins_cst').val() || '').trim());
        payload.append('aliquota_cofins', ($('#cofins_aliquota').val() || '').trim());
        payload.append('cst_ipi', ($('#ipi_cst').val() || '').trim());
        payload.append('aliquota_ipi', ($('#ipi_aliquota').val() || '').trim());
        payload.append('observacoes', ($('#observacoes').val() || '').trim());
        payload.append('ativo', $('#ativo').is(':checked') ? '1' : '0');

        const foto = $('#foto')[0].files[0];
        if (foto) {
            payload.append('imagens[]', foto);
            payload.append('imagem_principal', 'new:0');
        }

        $.ajax({
            url: '/produto',
            method: 'POST',
            data: payload,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            success: function (response) {
                showAlert(response.message || 'Produto salvo com sucesso!', 'success');
                window.location.href = '/produto';
            },
            error: function (xhr) {
                const message = (xhr.responseJSON && (xhr.responseJSON.message || (xhr.responseJSON.errors && Object.values(xhr.responseJSON.errors)[0][0])))
                    ? (xhr.responseJSON.message || Object.values(xhr.responseJSON.errors)[0][0])
                    : 'Erro ao salvar o produto.';
                showAlert(message, 'danger');
                $('#btn-salvar').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Salvar');
            }
        });
    });
});
</script>
@endsection
