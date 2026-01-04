@extends('layouts.form')

@section('form')
<div class="container-fluid" style="margin-top: 20px;">
  <div class="card">
    <div class="card-header">
      <h4 class="card-title">Cadastro de Cliente</h4>
    </div>
    <div class="card-body">
      <form id="form-clientes" class="row g-3 needs-validation" novalidate>

        <!-- Tipo de pessoa -->
        <div class="col-md-2 position-relative">
          <label for="tipo_pessoa" class="form-label">Tipo de Pessoa</label>
          <select id="tipo_pessoa" name="tipo_pessoa" class="form-select" required>
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
          <input type="text" id="cnpj_cpf" name="cnpj_cpf" class="form-control" required>
          <div class="invalid-tooltip">
            Informe um CPF ou CNPJ válido.
          </div>
        </div>

        <!-- Nome / Razão Social -->
        <div class="col-md-5 position-relative">
          <label for="razao_social" class="form-label">Nome / Razão Social</label>
          <input type="text" id="razao_social" name="razao_social" class="form-control" required>
          <div class="invalid-tooltip">
            Informe o nome completo ou razão social.
          </div>
        </div>

        <!-- Nome fantasia -->
        <div class="col-md-4 position-relative">
          <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
          <input type="text" id="nome_fantasia" name="nome_fantasia" class="form-control">
          <div class="valid-tooltip">
            Opcional.
          </div>
        </div>

        <!-- Email -->
        <div class="col-md-4 position-relative">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" id="email" name="email" class="form-control" required>
          <div class="invalid-tooltip">
            Informe um e-mail válido.
          </div>
        </div>

        <!-- Telefone -->
        <div class="col-md-3 position-relative">
          <label for="telefone" class="form-label">Telefone</label>
          <input type="text" id="telefone" name="telefone" class="form-control" required>
          <div class="invalid-tooltip">
            Informe um telefone válido.
          </div>
        </div>

        <!-- Endereço -->
        <div class="col-md-5 position-relative">
          <label for="logradouro" class="form-label">Logradouro</label>
          <input type="text" id="logradouro" name="logradouro" class="form-control" required>
          <div class="invalid-tooltip">
            Informe o logradouro.
          </div>
        </div>

        <div class="col-md-2 position-relative">
          <label for="numero" class="form-label">Número</label>
          <input type="text" id="numero" name="numero" class="form-control" required>
          <div class="invalid-tooltip">
            Informe o número.
          </div>
        </div>

        <div class="col-md-3 position-relative">
          <label for="bairro" class="form-label">Bairro</label>
          <input type="text" id="bairro" name="bairro" class="form-control" required>
          <div class="invalid-tooltip">
            Informe o bairro.
          </div>
        </div>

        <div class="col-md-4 position-relative">
          <label for="cidade" class="form-label">Cidade</label>
          <input type="text" id="cidade" name="cidade" class="form-control" required>
          <div class="invalid-tooltip">
            Informe a cidade.
          </div>
        </div>

        <div class="col-md-2 position-relative">
          <label for="uf" class="form-label">UF</label>
          <select id="uf" name="uf" class="form-select" required>
            <option selected disabled value="">Selecione...</option>
            <option value="SP">SP</option>
            <option value="RJ">RJ</option>
            <option value="MG">MG</option>
            <option value="RS">RS</option>
            <option value="PR">PR</option>
            <option value="SC">SC</option>
          </select>
          <div class="invalid-tooltip">
            Selecione o estado.
          </div>
        </div>

        <div class="col-md-3 position-relative">
          <label for="cep" class="form-label">CEP</label>
          <input type="text" id="cep" name="cep" class="form-control" required>
          <div class="invalid-tooltip">
            Informe um CEP válido.
          </div>
        </div>

        <div class="col-md-2 position-relative">
          <label for="ativo" class="form-label">Ativo</label>
          <select id="ativo" name="ativo" class="form-select">
            <option value="1">Sim</option>
            <option value="0">Não</option>
          </select>
        </div>

        <div class="col-12">
          <button class="btn btn-primary" type="button" id="btn-salvar">
            <i class="ti ti-device-floppy me-1"></i> Salvar
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- jQuery + jQuery Mask Plugin -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
$(document).ready(function() {

    //Enviando o formulário
    $('#btn-salvar').on('click', function() {
    
    
        //Adicionar um reload no btn e desabilitar enquanto processa
        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');

       

        const formData = $('#form-clientes').serialize();

        $.ajax({
            url: '/clientes', // Ajuste a URL conforme necessário
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Cliente salvo com sucesso!');
                // Redirecionar ou limpar o formulário conforme necessário
                //habiliar o botão
                $('#btn-salvar').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Salvar');
            },
            error: function(xhr) {
                alert('Erro ao salvar o cliente. Tente novamente.');
                $('#btn-salvar').prop('disabled', false).html('<i class="ti ti-device-floppy me-1"></i> Salvar');
            }
        });
    });



  // 🧩 Máscaras básicas
  $('#telefone').mask('(00) 00000-0000');
  $('#cep').mask('00000-000');
  $('#numero').mask('00000');

  // 🧩 CPF/CNPJ dinâmico conforme tipo de pessoa
  function updateCpfCnpjMask() {
    const tipo = $('#tipo_pessoa').val();
    const $input = $('#cnpj_cpf');
    $input.val(''); // limpa o valor quando troca
    if (tipo === 'F') {
      $input.mask('000.000.000-00');
      $input.attr('placeholder', '000.000.000-00');
    } else if (tipo === 'J') {
      $input.mask('00.000.000/0000-00');
      $input.attr('placeholder', '00.000.000/0000-00');
    } else {
      $input.unmask();
    }
  }

  $('#tipo_pessoa').on('change', updateCpfCnpjMask);
  updateCpfCnpjMask(); // inicializa

  // 🧩 Validação Bootstrap com tooltips
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
