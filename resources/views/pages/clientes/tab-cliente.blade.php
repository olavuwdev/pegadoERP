<div class="row justify-content-center">
  <div class="col-xxl-12">
    <div class="card">
      <div class="card-header justify-content-between">
        <h5 class="mb-0">{{ $titulo }}</h5>
        <div class="actions">
          <button class="btn btn-primary" type="button">
            <i class="ti ti-user-plus me-1"></i> Novo
          </button>
          <button class="btn btn-danger" type="button">
            <i class="ti ti-trash me-1"></i> Excluir Selecionados
          </button>
        </div>
      </div>
      <div class="card-body">
        <table id="{{ $id }}" class="table table-striped align-middle w-100"></table>
      </div>
    </div>
  </div>
</div>
