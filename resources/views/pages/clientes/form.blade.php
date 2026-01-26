@extends('layouts.form')

@section('form')
<div class="container-fluid" style="margin-top: 20px;">
  <div class="card">
    <div class="card-header">
      <h4 class="card-title">Cadastro de Cliente</h4>
    </div>
    <div class="card-body">
         <div class="ins-wizard" data-wizard>
                                    <!-- Navigation Tabs -->
                                    <ul class="nav nav-tabs wizard-tabs" data-wizard-nav role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#stuInfo">
                                                <span class="d-flex align-items-center">
                                                    <i class="ti ti-id-badge fs-32"></i>
                                                    <span class="flex-grow-1 ms-2 text-truncate">
                                                        <span class="mb-0 lh-base d-block fw-semibold text-body fs-base">Dados</span>
                                                        <span class="mb-0 fw-normal">Dados Gerais</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#addrInfo">
                                                <span class="d-flex align-items-center">
                                                    <i class="ti ti-file fs-32"></i>
                                                    <span class="flex-grow-1 ms-2 text-truncate">
                                                        <span class="mb-0 lh-base d-block fw-semibold text-body fs-base">Dados adicionais</span>
                                                        <span class="mb-0 fw-normal">Fiscais</span>
                                                    </span>
                                                </span>
                                            </a>
                                        </li>

                                    </ul>

                                    <form id="form-clientes" class="needs-validation" novalidate>
                                    <input type="hidden" id="cliente_id" name="cliente_id" value="{{ optional($cliente)->cliente_id }}">
                                    <!-- Content -->
                                    <div class="tab-content pt-3" data-wizard-content>

                                        <!-- Step 1: Principais informações -->
                                        <div class="tab-pane fade show active" id="stuInfo">
                                           <div  class="row g-3 needs-validation">

                                                <!-- Tipo de pessoa -->
                                                <div class="col-md-2 position-relative">
                                                <label for="tipo_pessoa" class="form-label">Tipo de Pessoa</label>
                                                <select id="tipo_pessoa" name="tipo_pessoa" class="form-select" required data-value="{{ old('tipo_pessoa', optional($cliente)->tipo_pessoa) }}">
                                                    <option selected disabled value="">Selecione...</option>
                                                    <option value="F">Física</option>
                                                    <option value="J">Jurídica</option>
                                                </select>
                                                <div class="invalid-tooltip">
                                                    Selecione o tipo de pessoa.
                                                </div>
                                                </div>

                                                <!-- CPF / CNPJ -->
                                                <div class="col-md-3 position-relative">
                                                <label for="cnpj_cpf" class="form-label">CPF / CNPJ</label>
                                                <input type="text" id="cnpj_cpf" name="cnpj_cpf" class="form-control" required value="{{ old('cnpj_cpf', optional($cliente)->cnpj_cpf) }}">
                                                <div class="invalid-tooltip">
                                                    Informe um CPF ou CNPJ válido.
                                                </div>
                                                </div>

                                                <!-- Nome / Razão Social -->
                                                <div class="col-md-5 position-relative">
                                                <label for="razao_social" class="form-label">Nome / Razão Social</label>
                                                <input type="text" id="razao_social" name="razao_social" class="form-control" required value="{{ old('razao_social', optional($cliente)->razao_social) }}">
                                                <div class="invalid-tooltip">
                                                    Informe o nome completo ou razão social.
                                                </div>
                                                </div>

                                                <!-- Nome fantasia -->
                                                <div class="col-md-4 position-relative">
                                                <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                                                <input type="text" id="nome_fantasia" name="nome_fantasia" class="form-control" value="{{ old('nome_fantasia', optional($cliente)->nome_fantasia) }}">
                                                <div class="valid-tooltip">
                                                    Opcional.
                                                </div>
                                                </div>

                                                <!-- Email -->
                                                <div class="col-md-4 position-relative">
                                                <label for="email" class="form-label">E-mail</label>
                                                <input type="email" id="email" name="email" class="form-control" required value="{{ old('email', optional($cliente)->email) }}">
                                                <div class="invalid-tooltip">
                                                    Informe um e-mail válido.
                                                </div>
                                                </div>

                                                <!-- Telefone -->
                                                <div class="col-md-3 position-relative">
                                                <label for="telefone" class="form-label">Telefone</label>
                                                <input type="text" id="telefone" name="telefone" class="form-control" required value="{{ old('telefone', optional($cliente)->telefone) }}">
                                                <div class="invalid-tooltip">
                                                    Informe um telefone válido.
                                                </div>
                                                </div>

                                                <!-- Endereço -->
                                                <div class="col-md-3 position-relative">
                                                <label for="cep" class="form-label">CEP</label>
                                                <input type="text" id="cep" name="cep" class="form-control" required value="{{ old('cep', optional($cliente)->cep) }}">
                                                <div class="invalid-tooltip">
                                                    Informe um CEP válido.
                                                </div>
                                                </div>
                                                <div class="col-md-5 position-relative">
                                                <label for="logradouro" class="form-label">Logradouro</label>
                                                <input type="text" id="logradouro" name="logradouro" class="form-control" required value="{{ old('logradouro', optional($cliente)->logradouro) }}">
                                                <div class="invalid-tooltip">
                                                    Informe o logradouro.
                                                </div>
                                                </div>

                                                <div class="col-md-2 position-relative">
                                                <label for="numero" class="form-label">Número</label>
                                                <input type="text" id="numero" name="numero" class="form-control" required value="{{ old('numero', optional($cliente)->numero) }}">
                                                <div class="invalid-tooltip">
                                                    Informe o número.
                                                </div>
                                                </div>

                                                <div class="col-md-3 position-relative">
                                                <label for="bairro" class="form-label">Bairro</label>
                                                <input type="text" id="bairro" name="bairro" class="form-control" required value="{{ old('bairro', optional($cliente)->bairro) }}">
                                                <div class="invalid-tooltip">
                                                    Informe o bairro.
                                                </div>
                                                </div>

                                                <div class="col-md-4 position-relative">
                                                <label for="cidade" class="form-label">Cidade</label>
                                                <input type="text" id="cidade" name="cidade" class="form-control" required value="{{ old('cidade', optional($cliente)->cidade) }}">
                                                <div class="invalid-tooltip">
                                                    Informe a cidade.
                                                </div>
                                                </div>

                                                <div class="col-md-2 position-relative">
                                                <label for="uf" class="form-label">UF</label>
                                                <select id="uf" name="uf" class="form-select" required data-value="{{ old('uf', optional($cliente)->uf) }}">
                                                   <option value="" disabled selected>Selecione</option>
                                                    <option value="AC">Acre</option>
                                                    <option value="AL">Alagoas</option>
                                                    <option value="AP">Amapá</option>
                                                    <option value="AM">Amazonas</option>
                                                    <option value="BA">Bahia</option>
                                                    <option value="CE">Ceará</option>
                                                    <option value="DF">Distrito Federal</option>
                                                    <option value="ES">Espirito Santo</option>
                                                    <option value="GO">Goiás</option>
                                                    <option value="MA">Maranhão</option>
                                                    <option value="MS">Mato Grosso do Sul</option>
                                                    <option value="MT">Mato Grosso</option>
                                                    <option value="MG">Minas Gerais</option>
                                                    <option value="PA">Pará</option>
                                                    <option value="PB">Paraíba</option>
                                                    <option value="PR">Paraná</option>
                                                    <option value="PE">Pernambuco</option>
                                                    <option value="PI">Piauí</option>
                                                    <option value="RJ">Rio de Janeiro</option>
                                                    <option value="RN">Rio Grande do Norte</option>
                                                    <option value="RS">Rio Grande do Sul</option>
                                                    <option value="RO">Rondônia</option>
                                                    <option value="RR">Roraima</option>
                                                    <option value="SC">Santa Catarina</option>
                                                    <option value="SP">São Paulo</option>
                                                    <option value="SE">Sergipe</option>
                                                    <option value="TO">Tocantins</option>
                                                @marcossouz

                                                @matheusolivesilva

                                                @evandrosimenes

                                                obrigado
                                                @gilvansantos

                                                @jaquelineabreu

                                                </select>
                                                <div class="invalid-tooltip">
                                                    Selecione o estado.
                                                </div>
                                                </div>

                                                <div class="col-md-2 position-relative">
                                                <label for="ativo" class="form-label">Ativo</label>
                                                <select id="ativo" name="ativo" class="form-select" data-value="{{ old('ativo', optional($cliente)->ativo) }}">
                                                    <option value="1">Sim</option>
                                                    <option value="0">Não</option>
                                                </select>
                                                </div>

                                                

                                            </div>
                                        </div>

                                        <!-- Step 2: Address Info -->
                                        <div class="tab-pane fade" id="addrInfo">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <label class="form-label">Cliente final?</label>
                                                    <div class="btn-group w-100" role="group" aria-label="Cliente final">
                                                        <input type="radio" class="btn-check" name="cliente_final" id="cliente_final_sim" value="1">
                                                        <label class="btn btn-outline-primary" for="cliente_final_sim">Sim</label>
                                                        <input type="radio" class="btn-check" name="cliente_final" id="cliente_final_nao" value="0" checked>
                                                        <label class="btn btn-outline-primary" for="cliente_final_nao">Nao</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <label for="indicador_ie" class="form-label">Indicador de IE</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ti ti-list"></i></span>
                                                        <select id="indicador_ie" name="indicador_ie" class="form-select">
                                                            <option value="1" selected>Nao contribuinte</option>
                                                            <option value="2">Contribuinte</option>
                                                            <option value="9">Isento</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inscricao_estadual" class="form-label">Inscricao Estadual</label>
                                                    <input type="text" id="inscricao_estadual" name="inscricao_estadual" class="form-control" value="{{ old('inscricao_estadual', optional($cliente)->inscr_estadual) }}">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="ie_subst_trib" class="form-label">IE Subst. Trib.</label>
                                                    <input type="text" id="ie_subst_trib" name="ie_subst_trib" class="form-control">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inscricao_municipal" class="form-label">Inscricao Municipal</label>
                                                    <input type="text" id="inscricao_municipal" name="inscricao_municipal" class="form-control">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="suframa" class="form-label">Suframa</label>
                                                    <input type="text" id="suframa" name="suframa" class="form-control">
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <h6 class="text-muted mb-1">Outras informacoes</h6>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="data_nascimento" class="form-label">Data nascimento</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                                        <input type="date" id="data_nascimento" name="data_nascimento" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-9">
                                                    <label for="palavras_chave" class="form-label">Palavras-chave</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ti ti-tags"></i></span>
                                                        <input type="text" id="palavras_chave" name="palavras_chave" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="data_comemorativa" class="form-label">Data comemorativa</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ti ti-calendar-event"></i></span>
                                                        <input type="date" id="data_comemorativa" name="data_comemorativa" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-9">
                                                    <label for="descricao_comemoracao" class="form-label">Descricao curta da comemoracao</label>
                                                    <input type="text" id="descricao_comemoracao" name="descricao_comemoracao" class="form-control">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="bases_legais_lgpd" class="form-label">Bases legais (LGPD)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ti ti-list"></i></span>
                                                        <select id="bases_legais_lgpd" name="bases_legais_lgpd" class="form-select">
                                                            <option value="padrao" selected>Utilizar configuracao padrao</option>
                                                            <option value="consentimento">Consentimento</option>
                                                            <option value="contrato">Execucao de contrato</option>
                                                            <option value="obrigacao_legal">Obrigacao legal</option>
                                                            <option value="legitimo_interesse">Legitimo interesse</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <label for="observacoes" class="form-label">Observacoes</label>
                                                    <textarea id="observacoes" name="observacoes" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>

                                            
                                        </div>

                                        {{--AÇÕES - CANCELAR E SALVAR --}}
                                        <div class="row">
                                                <div class="col d-flex justify-content-end mt-4">
                                                <a href="/clientes" class="btn btn-secondary me-2">
                                                    <i class="ti ti-x-circle me-1"></i> Cancelar
                                                </a>
                                                <button class="btn btn-primary" type="button" id="btn-salvar">
                                                    <i class="ti ti-device-floppy me-1"></i> Salvar
                                                </button>
                                                </div>
                                        </div>
                                        </div>

                                        


                                    </div> <!-- tab-content -->
                                    
                                </div> <!-- ins-wizard -->
      
    </div>
  </div>
</div>

<!-- jQuery + jQuery Mask Plugin -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
$(document).ready(function() {

    function applySelectValue(selector) {
        var value = $(selector).data('value');
        if (value !== undefined && value !== null && value !== '') {
            $(selector).val(value);
        }
    }

    applySelectValue('#tipo_pessoa');
    applySelectValue('#uf');
    applySelectValue('#ativo');
    applySelectValue('#indicador_ie');
    applySelectValue('#bases_legais_lgpd');

    // Envia o formulario
    $('#btn-salvar').on('click', function() {

        // Verifica campos obrigatorios
        if (!$('#form-clientes')[0].checkValidity()) {
            $('#form-clientes').addClass('was-validated');
            return;
        }
        // Desabilita enquanto processa
        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');

        var formData = $('#form-clientes').serialize();
        console.table(formData); // Debug

        var clienteId = $('#cliente_id').val();
        var url = '/clientes';
        if (clienteId) {
            url = '/clientes/' + clienteId;
            formData += '&_method=PUT';
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                showAlert(response.message, 'success');
                $('#btn-salvar').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Salvar');
                window.location.href = '/clientes';
            },
            error: function(xhr) {
                showAlert('Erro ao salvar o cliente. Tente novamente.', 'danger');
                $('#btn-salvar').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Salvar');
            }
        });
    });

    // Mascaras basicas
    $('#telefone').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');
    $('#numero').mask('00000');

    // CPF/CNPJ conforme tipo de pessoa
    function updateCpfCnpjMask(preserveValue) {
        const tipo = $('#tipo_pessoa').val();
        const $input = $('#cnpj_cpf');
        const currentValue = $input.val();
        if (!preserveValue) {
            $input.val('');
        }
        if (tipo === 'F') {
            $input.mask('000.000.000-00');
            $input.attr('placeholder', '000.000.000-00');
        } else if (tipo === 'J') {
            $input.mask('00.000.000/0000-00');
            $input.attr('placeholder', '00.000.000/0000-00');
        } else {
            $input.unmask();
        }
        if (preserveValue) {
            $input.val(currentValue);
        }
    }

    // Valida CPF/CNPJ
    $('#cnpj_cpf').on('blur', function() {
        $.ajax({
            url: '/api/cpf-cnpj/',
            method: 'POST',
            data: { cnpj_cpf: $('#cnpj_cpf').val(), tipo: $('#tipo_pessoa').val(), cliente_id: $('#cliente_id').val() },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            success: function(response) {
                if (!response.success) {
                    showAlert(response.message, 'danger');
                    $('#cnpj_cpf').val('').focus();
                }
            },
            error: function(xhr) {
                showAlert('Erro ao validar o ' + ( $('#tipo_pessoa').val() === 'F' ? 'CPF' : 'CNPJ' ) + '. Tente novamente.', 'danger');
            }
        });
    });

    // Consulta CEP via API
    $('#cep').on('blur', function() {
        const cep = $(this).val().replace(/\D/g, '');
        if (cep.length === 8) {
            $.ajax({
                url: `https://viacep.com.br/ws/${cep}/json/`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (!data.erro) {
                        $('#logradouro').val(data.logradouro);
                        $('#bairro').val(data.bairro);
                        $('#cidade').val(data.localidade);
                        $('#uf').val(data.uf);
                    } else {
                        showAlert('CEP nao encontrado.', 'warning');
                    }
                },
                error: function() {
                    showAlert('Erro ao buscar o CEP. Tente novamente.', 'danger');
                }
            });
        }
    });

    $('#tipo_pessoa').on('change', function () {
        updateCpfCnpjMask(false);
    });
    updateCpfCnpjMask(true); // inicializa

    // Validacao bootstrap
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');

        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
    })();
});
</script>


@endsection
