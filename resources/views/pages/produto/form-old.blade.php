
@extends('layouts.form')

@section('form')
@php
    $unidades = ['UN', 'CX', 'KG', 'G', 'L', 'ML', 'M', 'CM', 'PC', 'PCT'];
    $origens = [
        0 => '0 - Nacional',
        1 => '1 - Estrangeira (importacao direta)',
        2 => '2 - Estrangeira (mercado interno)',
        3 => '3 - Nacional > 40% importado',
        4 => '4 - Nacional processo basico',
        5 => '5 - Nacional <= 40% importado',
        6 => '6 - Importacao direta sem similar',
        7 => '7 - Mercado interno sem similar',
        8 => '8 - Nacional > 70% importado',
    ];
    $imagens = $produto?->imagens ?? collect();
    $principalAtual = old('imagem_principal');
    if (!$principalAtual && $imagens->isNotEmpty()) {
        $principal = $imagens->firstWhere('imagem_principal', true) ?? $imagens->first();
        $principalAtual = $principal ? 'existing:' . $principal->id : null;
    }
@endphp

<div class="container-fluid" style="margin-top:20px;">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>
        <h4 class="card-title mb-0">{{ $produto ? 'Editar Produto' : 'Cadastrar Produto' }}</h4>
        <small class="text-muted">Wizard com 5 etapas</small>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ url('/produto') }}" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-1"></i>Voltar</a>
        <button type="button" id="btn-salvar" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Salvar</button>
      </div>
    </div>

    <div class="card-body">
      <div id="wizard-nav" class="d-flex flex-wrap gap-2 mb-3">
        <button class="btn btn-soft-primary active" type="button" data-step="0">1. Identificacao</button>
        <button class="btn btn-soft-primary" type="button" data-step="1">2. Preco/Estoque</button>
        <button class="btn btn-soft-primary" type="button" data-step="2">3. Fiscal</button>
        <button class="btn btn-soft-primary" type="button" data-step="3">4. Tributos</button>
        <button class="btn btn-soft-primary" type="button" data-step="4">5. Imagens</button>
      </div>

      <form id="form-produto" novalidate enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="produto_id" value="{{ optional($produto)->id }}">

        <div class="wizard-step" data-step="0">
          <div class="row g-3">
            <div class="col-md-2"><label class="form-label">Tipo</label><select name="tipo" id="tipo" class="form-select" required><option value="PRODUTO" {{ old('tipo', optional($produto)->tipo ?? 'PRODUTO') === 'PRODUTO' ? 'selected' : '' }}>Produto</option><option value="SERVICO" {{ old('tipo', optional($produto)->tipo) === 'SERVICO' ? 'selected' : '' }}>Servico</option></select></div>
            <div class="col-md-7"><label class="form-label">Nome</label><input class="form-control" id="nome" name="nome" maxlength="255" required value="{{ old('nome', optional($produto)->nome) }}"></div>
            <div class="col-md-3"><label class="form-label">Codigo SKU</label><input class="form-control" id="codigo_sku" name="codigo_sku" maxlength="100" required value="{{ old('codigo_sku', optional($produto)->codigo_sku) }}"></div>
            <div class="col-md-4"><label class="form-label">GTIN/Codigo barras</label><input class="form-control" id="codigo_barras" name="codigo_barras" maxlength="14" value="{{ old('codigo_barras', optional($produto)->codigo_barras) }}"></div>
            <div class="col-md-4"><label class="form-label">Marca</label><input class="form-control" id="marca" name="marca" maxlength="150" value="{{ old('marca', optional($produto)->marca) }}"></div>
            <div class="col-md-4"><label class="form-label">Categoria ID</label><input type="number" min="1" class="form-control" id="categoria_id" name="categoria_id" value="{{ old('categoria_id', optional($produto)->categoria_id) }}"></div>
            <div class="col-12"><label class="form-label">Descricao</label><textarea class="form-control" rows="3" id="descricao" name="descricao">{{ old('descricao', optional($produto)->descricao) }}</textarea></div>
          </div>
        </div>

        <div class="wizard-step d-none" data-step="1">
          <div class="row g-3">
            <div class="col-md-3"><label class="form-label">Preco custo</label><input class="form-control js-decimal-4" id="preco_custo" name="preco_custo" required value="{{ old('preco_custo', optional($produto)->preco_custo ?? '0,0000') }}"></div>
            <div class="col-md-3"><label class="form-label">Preco venda</label><input class="form-control js-decimal-4" id="preco_venda" name="preco_venda" required value="{{ old('preco_venda', optional($produto)->preco_venda ?? '0,0000') }}"></div>
            <div class="col-md-3"><label class="form-label">Qtd estoque</label><input class="form-control js-decimal-4" id="quantidade_estoque" name="quantidade_estoque" required value="{{ old('quantidade_estoque', optional($produto)->quantidade_estoque ?? '0,0000') }}"></div>
            <div class="col-md-3"><label class="form-label">Estoque minimo</label><input class="form-control js-decimal-4" id="estoque_minimo" name="estoque_minimo" required value="{{ old('estoque_minimo', optional($produto)->estoque_minimo ?? '0,0000') }}"></div>
            <div class="col-md-3"><label class="form-label">Unidade</label><select name="unidade_medida" id="unidade_medida" class="form-select" required>@foreach ($unidades as $u)<option value="{{ $u }}" {{ old('unidade_medida', optional($produto)->unidade_medida ?? 'UN') === $u ? 'selected' : '' }}>{{ $u }}</option>@endforeach</select></div>
            <div class="col-md-3"><label class="form-label">Peso</label><input class="form-control js-decimal-4" id="peso" name="peso" value="{{ old('peso', optional($produto)->peso) }}"></div>
            <div class="col-md-2"><label class="form-label">Largura</label><input class="form-control js-decimal-4" id="largura" name="largura" value="{{ old('largura', optional($produto)->largura) }}"></div>
            <div class="col-md-2"><label class="form-label">Altura</label><input class="form-control js-decimal-4" id="altura" name="altura" value="{{ old('altura', optional($produto)->altura) }}"></div>
            <div class="col-md-2"><label class="form-label">Comprimento</label><input class="form-control js-decimal-4" id="comprimento" name="comprimento" value="{{ old('comprimento', optional($produto)->comprimento) }}"></div>
          </div>
        </div>

        <div class="wizard-step d-none" data-step="2">
          <div class="row g-3">
            <div class="col-md-3"><label class="form-label">NCM</label><input class="form-control" id="ncm" name="ncm" maxlength="8" pattern="\d{8}" value="{{ old('ncm', optional($produto)->ncm) }}"></div>
            <div class="col-md-3"><label class="form-label">CEST</label><input class="form-control" id="cest" name="cest" maxlength="7" pattern="\d{7}" value="{{ old('cest', optional($produto)->cest) }}"></div>
            <div class="col-md-3"><label class="form-label">Codigo ANP</label><input class="form-control" id="codigo_anp" name="codigo_anp" maxlength="20" value="{{ old('codigo_anp', optional($produto)->codigo_anp) }}"></div>
            <div class="col-md-3"><label class="form-label">Origem mercadoria</label><select class="form-select" id="origem_mercadoria" name="origem_mercadoria" required>@foreach ($origens as $k => $v)<option value="{{ $k }}" {{ (int) old('origem_mercadoria', optional($produto)->origem_mercadoria ?? 0) === (int) $k ? 'selected' : '' }}>{{ $v }}</option>@endforeach</select></div>
            <div class="col-md-3"><label class="form-label">EX TIPI</label><input class="form-control" id="ex_tipi" name="ex_tipi" maxlength="5" value="{{ old('ex_tipi', optional($produto)->ex_tipi) }}"></div>
            <div class="col-md-4"><label class="form-label">Codigo beneficio fiscal</label><input class="form-control" id="codigo_beneficio_fiscal" name="codigo_beneficio_fiscal" maxlength="20" value="{{ old('codigo_beneficio_fiscal', optional($produto)->codigo_beneficio_fiscal) }}"></div>
          </div>
        </div>
        <div class="wizard-step d-none" data-step="3">
          <div class="row g-3">
            <div class="col-md-2"><label class="form-label">CST ICMS</label><input class="form-control" name="cst_icms" maxlength="3" value="{{ old('cst_icms', optional($produto)->cst_icms) }}"></div>
            <div class="col-md-2"><label class="form-label">CSOSN</label><input class="form-control" name="csosn" maxlength="3" value="{{ old('csosn', optional($produto)->csosn) }}"></div>
            <div class="col-md-2"><label class="form-label">Mod. BC ICMS</label><input class="form-control" name="modalidade_bc_icms" maxlength="2" value="{{ old('modalidade_bc_icms', optional($produto)->modalidade_bc_icms) }}"></div>
            <div class="col-md-2"><label class="form-label">Aliq. ICMS (%)</label><input class="form-control js-percent" name="aliquota_icms" value="{{ old('aliquota_icms', optional($produto)->aliquota_icms) }}"></div>
            <div class="col-md-2"><label class="form-label">Reducao BC (%)</label><input class="form-control js-percent" name="reducao_bc_icms" value="{{ old('reducao_bc_icms', optional($produto)->reducao_bc_icms) }}"></div>
            <div class="col-md-2"><label class="form-label">MVA (%)</label><input class="form-control js-percent" name="mva_icms" value="{{ old('mva_icms', optional($produto)->mva_icms) }}"></div>
            <div class="col-md-2"><label class="form-label">Aliq. ICMS ST (%)</label><input class="form-control js-percent" name="aliquota_icms_st" value="{{ old('aliquota_icms_st', optional($produto)->aliquota_icms_st) }}"></div>
            <div class="col-md-2"><label class="form-label">Aliq. FCP (%)</label><input class="form-control js-percent" name="aliquota_fcp" value="{{ old('aliquota_fcp', optional($produto)->aliquota_fcp) }}"></div>
            <div class="col-md-2"><label class="form-label">Aliq. FCP ST (%)</label><input class="form-control js-percent" name="aliquota_fcp_st" value="{{ old('aliquota_fcp_st', optional($produto)->aliquota_fcp_st) }}"></div>
            <div class="col-md-2"><label class="form-label">CST PIS</label><input class="form-control" name="cst_pis" maxlength="3" value="{{ old('cst_pis', optional($produto)->cst_pis) }}"></div>
            <div class="col-md-2"><label class="form-label">Aliq. PIS (%)</label><input class="form-control js-percent" name="aliquota_pis" value="{{ old('aliquota_pis', optional($produto)->aliquota_pis) }}"></div>
            <div class="col-md-2"><label class="form-label">Base PIS</label><input class="form-control js-decimal-4" name="base_calculo_pis" value="{{ old('base_calculo_pis', optional($produto)->base_calculo_pis) }}"></div>
            <div class="col-md-2"><label class="form-label">CST COFINS</label><input class="form-control" name="cst_cofins" maxlength="3" value="{{ old('cst_cofins', optional($produto)->cst_cofins) }}"></div>
            <div class="col-md-2"><label class="form-label">Aliq. COFINS (%)</label><input class="form-control js-percent" name="aliquota_cofins" value="{{ old('aliquota_cofins', optional($produto)->aliquota_cofins) }}"></div>
            <div class="col-md-2"><label class="form-label">Base COFINS</label><input class="form-control js-decimal-4" name="base_calculo_cofins" value="{{ old('base_calculo_cofins', optional($produto)->base_calculo_cofins) }}"></div>
            <div class="col-md-2"><label class="form-label">CST IPI</label><input class="form-control" name="cst_ipi" maxlength="3" value="{{ old('cst_ipi', optional($produto)->cst_ipi) }}"></div>
            <div class="col-md-2"><label class="form-label">Cod. enquad. IPI</label><input class="form-control" name="codigo_enquadramento_ipi" maxlength="5" value="{{ old('codigo_enquadramento_ipi', optional($produto)->codigo_enquadramento_ipi) }}"></div>
            <div class="col-md-2"><label class="form-label">Aliq. IPI (%)</label><input class="form-control js-percent" name="aliquota_ipi" value="{{ old('aliquota_ipi', optional($produto)->aliquota_ipi) }}"></div>
          </div>
        </div>

        <div class="wizard-step d-none" data-step="4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Upload de imagens</label>
              <input type="file" class="form-control" id="imagens" name="imagens[]" accept=".jpg,.jpeg,.png,.webp" multiple>
              <div class="form-text">JPG/PNG/WEBP, maximo 10 imagens por produto.</div>
            </div>
            <div class="col-md-3">
              <label class="form-label">Status</label>
              <input type="hidden" name="ativo" value="0">
              <div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" id="ativo" name="ativo" value="1" {{ old('ativo', optional($produto)->ativo ?? 1) ? 'checked' : '' }}><label class="form-check-label" for="ativo">Produto ativo</label></div>
            </div>
            <div class="col-12"><label class="form-label">Observacoes</label><textarea class="form-control" rows="3" name="observacoes">{{ old('observacoes', optional($produto)->observacoes) }}</textarea></div>

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
                        <div class="form-check mb-2"><input class="form-check-input" type="radio" name="imagem_principal" id="principal_existing_{{ $imagem->id }}" value="existing:{{ $imagem->id }}" {{ $principalAtual === 'existing:' . $imagem->id ? 'checked' : '' }}><label class="form-check-label" for="principal_existing_{{ $imagem->id }}">Principal</label></div>
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
              <div class="row g-3" id="new-images-container"><div class="col-12 text-muted" id="new-empty">Nenhuma nova imagem selecionada.</div></div>
            </div>
          </div>
        </div>
      </form>

      <div class="d-flex justify-content-between mt-4">
        <button type="button" id="btn-prev" class="btn btn-outline-secondary">Voltar etapa</button>
        <button type="button" id="btn-next" class="btn btn-primary">Avancar etapa</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
  const $steps = $('.wizard-step');
  const $wizardNavButtons = $('#wizard-nav button');
  const $btnPrev = $('#btn-prev');
  const $btnNext = $('#btn-next');
  const $btnSalvar = $('#btn-salvar');
  const totalSteps = $steps.length;
  let currentStep = 0;
  const $fileInput = $('#imagens');
  let selectedFiles = [];

  function applyMasks() {
    if (!$.fn.mask) return;
    $('#ncm').mask('00000000');
    $('#cest').mask('0000000');
    $('#codigo_barras').mask('00000000000000');
    $('.js-percent').mask('##0,00', { reverse: true });
    $('.js-decimal-4').mask('000.000.000.000.000,0000', { reverse: true });
  }

  function updateWizard() {
    $steps.addClass('d-none');
    $steps.filter(`[data-step="${currentStep}"]`).removeClass('d-none');
    $wizardNavButtons.removeClass('active');
    $wizardNavButtons.filter(`[data-step="${currentStep}"]`).addClass('active');
    $btnPrev.prop('disabled', currentStep === 0);
    $btnNext.text(currentStep === totalSteps - 1 ? 'Finalizar e salvar' : 'Avancar etapa');
  }

  function validateCurrentStep() {
    const $step = $steps.filter(`[data-step="${currentStep}"]`);
    let valid = true;
    $step.find('input,select,textarea').each(function () {
      if (!$(this).is(':visible') || $(this).is(':disabled')) return;
      if (typeof this.checkValidity === 'function' && !this.checkValidity()) {
        valid = false;
        this.reportValidity();
        return false;
      }
    });
    if (!valid) return false;

    if (currentStep === 2) {
      const ncm = ($('#ncm').val() || '').replace(/\D/g, '');
      const cest = ($('#cest').val() || '').replace(/\D/g, '');
      if (ncm.length > 0 && ncm.length !== 8) { showAlert('NCM deve ter 8 digitos.', 'warning'); return false; }
      if (cest.length > 0 && cest.length !== 7) { showAlert('CEST deve ter 7 digitos.', 'warning'); return false; }
    }

    return true;
  }

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
              <div class="form-check mb-2"><input class="form-check-input" type="radio" name="imagem_principal" id="principal_new_${index}" value="new:${index}"><label class="form-check-label" for="principal_new_${index}">Principal</label></div>
              <button type="button" class="btn btn-sm btn-outline-danger w-100 js-remove-new" data-index="${index}">Remover</button>
            </div>
          </div>
        </div>`);
    });

    syncFileInput();
  }

  function validateFinalStep() {
    const visibleExisting = $('.existing-image-card:visible').length;
    const totalImages = visibleExisting + selectedFiles.length;
    if (totalImages > 0 && $('input[name="imagem_principal"]:checked').length === 0) {
      showAlert('Selecione a imagem principal.', 'warning');
      return false;
    }
    if (totalImages > 10) {
      showAlert('Limite maximo de 10 imagens por produto.', 'warning');
      return false;
    }
    return true;
  }

  function submitForm() {
    syncFileInput();
    const produtoId = $('#produto_id').val();
    const isUpdate = !!produtoId;
    const formData = new FormData($('#form-produto')[0]);
    if (isUpdate) formData.append('_method', 'PUT');

    $btnSalvar.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Salvando...');

    $.ajax({
      url: isUpdate ? `/produto/${produtoId}` : '/produto',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      success: function (response) {
        showAlert(response.message || 'Produto salvo com sucesso!', 'success');
        window.location.href = '/produto';
      },
      error: function (xhr) {
        const message = (xhr.responseJSON && (xhr.responseJSON.message || (xhr.responseJSON.errors && Object.values(xhr.responseJSON.errors)[0][0])))
          ? (xhr.responseJSON.message || Object.values(xhr.responseJSON.errors)[0][0])
          : 'Erro ao salvar o produto.';
        showAlert(message, 'danger');
        $btnSalvar.prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Salvar');
      }
    });
  }

  $wizardNavButtons.on('click', function () {
    const step = Number($(this).data('step'));
    if (step > currentStep && !validateCurrentStep()) return;
    currentStep = step;
    updateWizard();
  });

  $btnPrev.on('click', function () { if (currentStep > 0) { currentStep -= 1; updateWizard(); } });

  $btnNext.on('click', function () {
    if (!validateCurrentStep()) return;
    if (currentStep === totalSteps - 1) {
      if (!validateFinalStep()) return;
      submitForm();
      return;
    }
    currentStep += 1;
    updateWizard();
  });

  $btnSalvar.on('click', function () {
    if (!validateCurrentStep()) return;
    if (currentStep === totalSteps - 1 && !validateFinalStep()) return;
    submitForm();
  });

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

  applyMasks();
  updateWizard();
});
</script>
@endsection
