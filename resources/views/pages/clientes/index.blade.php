    @extends('layouts.list')

@section('list')
<style>
    .nav{
        width:60%;
        margin-top: 20px;
    }
</style>
    <div class="container-fluid" style="margin-top: 20px;">

        <div class="row">
            <ul class="nav nav-tabs nav-justified nav-bordered nav-bordered-danger mb-3">
                <li class="nav-item">
                    <a href="#home-b2" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                        <i class="ti ti-users fs-lg me-md-1 align-middle"></i>
                        <span class="d-none d-md-inline-block align-middle">Clientes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#profile-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        <i class="ti ti-clipboard-data fs-lg me-md-1 align-middle"></i>
                        <span class="d-none d-md-inline-block align-middle">Fornecedores</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#settings-b2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        <i class="ti ti-truck fs-lg me-md-1 align-middle"></i>
                        <span class="d-none d-md-inline-block align-middle">Transportadores</span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane show active" id="home-b2">
                   <div class="row justify-content-center">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header justify-content-between">
                                <div class="actions">
                                    <a href="{{ url('/clientes/create') }}" class="btn btn-primary" type="button">
                                        <i class="ti ti-user-plus me-1"></i> Novo
                                    </a>
                                    {{-- Botao de Excluir selecionados --}}
                                    <button class="btn btn-danger" type="button">
                                        <i class="ti ti-trash me-1"></i> Excluir Selecionados
                                    </button>
                                </div>
                                
                               
                            </div>

                            <div class="card-body">
                                <table id="checkbox-select-data" class="table table-striped dt-responsive checkbox-select-datatable align-middle mb-0">
                                    <thead class="thead-sm text-uppercase fs-xxs">
                                        <tr>
                                            <th class="fs-sm" style="width: 1%;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="checkbox-select-all">
                                                    <label class="form-check-label" for="checkbox-select-all"></label>
                                                </div>
                                            </th>
                                            <th>ID</th>
                                            <th>CPF/CNPJ</th>
                                            <th>Razao Social</th>
                                            <th>Telefone</th>
                                            <th>Cidade</th>
                                            <th>Ativo</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div>
                </div>
                </div>
                <div class="tab-pane show" id="profile-b2">
                    <p class="mb-0">Hi there! I'm an avid explorer with a passion for technology, fitness, and continuous learning. I enjoy meeting like-minded individuals and believe in expanding my knowledge on diverse subjects, from the latest gadgets to personal development.</p>
                </div>
                <div class="tab-pane" id="settings-b2">
                    <p class="mb-0">In the center of the city stands a quiet, charming bookstore that offers a peaceful retreat. Surrounded by vibrant streets, it provides a calm, inviting atmosphere for readers to lose themselves in books while enjoying a cup of coffee in the cozy corner.</p>
                </div>
            </div>

        </div>
    </div>
<script>
    $(document).ready(function () {
      var $table = $('#checkbox-select-data');
      var dataTable;

      if ($table.length) {
        dataTable = $table.DataTable({
          ajax: {
            url: "{{ url('/clientes/dados') }}",
            dataSrc: 'data'
          },
          columns: [
            {
              data: null,
              orderable: false,
              searchable: false,
              className: 'select-checkbox',
              render: function () {
                return '<div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-label"></label></div>';
              }
            },
            { data: 'cliente_id' },
            { data: 'cnpj_cpf' },
            { data: 'razao_social' },
            { data: 'telefone' },
            { data: 'cidade' },
            { data: 'ativo' },
            { data: 'acoes' }
          ],
          language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
          },
          order: [[1, 'desc']]
        });
      }

      $('#checkbox-select-all').on('click', function () {
        if (!dataTable) {
          return;
        }
        var rows = dataTable.rows({ search: 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
      });
    });

</script>
@endsection
