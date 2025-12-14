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
                                    <button class="btn btn-primary" type="button">
                                        <i class="ti ti-user-plus me-1"></i> Novo
                                    </button>
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
                                            <th>Company</th>
                                            <th>Symbol</th>
                                            <th>Price</th>
                                            <th>Change</th>
                                            <th>Volume</th>
                                            <th>Market Cap</th>
                                            <th>Rating</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Abrir um modal ao clicar na linha --}}
                                        <tr class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <td class="fs-sm" style="width: 1%;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="checkbox1">
                                                    <label class="form-check-label" for="checkbox1"></label>
                                                </div>
                                            </td>
                                            <td>Apple Inc.</td>
                                            <td>AAPL</td>
                                            <td>$2109.53</td>
                                            <td>-0.42%</td>
                                            <td>48,374,838</td>
                                            <td>$53.59B</td>
                                            <td>4.7 ★</td>
                                            <td><span class="badge badge-label badge-soft-danger">Bearish</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fs-sm" style="width: 1%;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="checkbox1">
                                                    <label class="form-check-label" for="checkbox1"></label>
                                                </div>
                                            </td>
                                            <td>Microsoft Corp.</td>
                                            <td>MSFT</td>
                                            <td>$450.98</td>
                                            <td>-2.04%</td>
                                            <td>26,604,335</td>
                                            <td>$927.77B</td>
                                            <td>3.8 ★</td>
                                            <td><span class="badge badge-label badge-soft-danger">Bearish</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fs-sm" style="width: 1%;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="checkbox1">
                                                    <label class="form-check-label" for="checkbox1"></label>
                                                </div>
                                            </td>
                                            <td>Alphabet Inc.</td>
                                            <td>GOOGL</td>
                                            <td>$2803.77</td>
                                            <td>+0.68%</td>
                                            <td>22,545,332</td>
                                            <td>$1.88T</td>
                                            <td>4.6 ★</td>
                                            <td><span class="badge badge-label badge-soft-success">Bullish</span></td>
                                        </tr>
                                    </tbody>
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

  if ($table.length) {
    //colocar o datatable em portugues
    $table.DataTable({
      columnDefs: [
        { 
          orderable: false, 
          className: 'select-checkbox', 
          targets: 0 
        }
      ],
      language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
      order: [[1, 'asc']]
    });
  }

  $('#checkbox-select-all').on('click', function() {
    var rows = $table.DataTable().rows({ 'search': 'applied' }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
    if (this.checked) {
      $table.DataTable().rows().select();
    } else {
      $table.DataTable().rows().deselect();
    }
  });
});

</script>
@endsection
