@extends('layouts.list')

@section('list')
<div class="container-fluid" style="margin-top: 20px;">
    <div class="row justify-content-center">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="card-title mb-0">Produtos</h5>
                    <a href="{{ url('/produto/novo') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Cadastrar Produto
                    </a>
                </div>

                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-2">
                            <label for="filtro_ativo" class="form-label">Status</label>
                            <select id="filtro_ativo" class="form-select">
                                <option value="">Todos</option>
                                <option value="1">Ativos</option>
                                <option value="0">Inativos</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="filtro_ncm" class="form-label">NCM</label>
                            <input type="text" id="filtro_ncm" class="form-control" maxlength="8" placeholder="Ex.: 84713012">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="btn-filtrar" class="btn btn-outline-primary w-100">Aplicar filtros</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tabela-produtos" class="table table-striped align-middle mb-0 w-100">
                            <thead class="thead-sm text-uppercase fs-xxs">
                                <tr>
                                    <th>SKU</th>
                                    <th>Nome</th>
                                    <th>NCM</th>
                                    <th class="text-end">Preco venda</th>
                                    <th class="text-end">Estoque</th>
                                    <th>Status</th>
                                    <th class="text-center">Acoes</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
$(function () {
    if ($.fn.mask) {
        $('#filtro_ncm').mask('00000000');
    }

    const table = $('#tabela-produtos').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('produto.dados') }}',
            data: function (d) {
                d.ativo = $('#filtro_ativo').val();
                d.ncm = $('#filtro_ncm').val();
            }
        },
        columns: [
            { data: 'codigo_sku', name: 'codigo_sku' },
            { data: 'nome', name: 'nome' },
            { data: 'ncm', name: 'ncm' },
            { data: 'preco_venda', name: 'preco_venda', className: 'text-end' },
            { data: 'quantidade_estoque', name: 'quantidade_estoque', className: 'text-end' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'acoes', name: 'acoes', orderable: false, searchable: false, className: 'text-center' }
        ],
        pageLength: 25,
        order: [[1, 'asc']],
        language: {
            search: 'Buscar:',
            lengthMenu: 'Mostrar _MENU_',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
            infoEmpty: 'Nenhum registro encontrado',
            zeroRecords: 'Nenhum registro encontrado',
            paginate: {
                first: 'Primeiro',
                last: 'Ultimo',
                next: 'Proximo',
                previous: 'Anterior'
            }
        }
    });

    $('#btn-filtrar, #filtro_ativo').on('click change', function () {
        table.ajax.reload();
    });

    $('#filtro_ncm').on('keyup', function (e) {
        if (e.key === 'Enter') {
            table.ajax.reload();
        }
    });

    $(document).on('click', '.js-toggle-status', function (e) {
        e.preventDefault();

        const produtoId = $(this).data('id');
        const ativo = $(this).data('ativo');

        $.ajax({
            url: `/produto/${produtoId}`,
            method: 'POST',
            data: {
                _method: 'PUT',
                _status_only: 1,
                ativo: ativo,
                _token: '{{ csrf_token() }}'
            },
            headers: {
                'Accept': 'application/json'
            },
            success: function (response) {
                showAlert(response.message || 'Status atualizado com sucesso!', 'success');
                table.ajax.reload(null, false);
            },
            error: function (xhr) {
                const message = (xhr.responseJSON && (xhr.responseJSON.message || (xhr.responseJSON.errors && Object.values(xhr.responseJSON.errors)[0][0])))
                    ? (xhr.responseJSON.message || Object.values(xhr.responseJSON.errors)[0][0])
                    : 'Erro ao alterar status do produto.';
                showAlert(message, 'danger');
            }
        });
    });
});
</script>
@endsection
