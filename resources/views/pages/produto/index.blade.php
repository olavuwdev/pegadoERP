@extends('layouts.list')

@section('list')
    <div class="container-fluid" style="margin-top: 20px;">

        <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1">
            <!-- Today's Prompts Widget -->
            <div class="col">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="text-uppercase">Today's Prompts</h5>
                            </div>
                            <div>
                                <i data-lucide="message-square" class="text-muted fs-24 svg-sw-10"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <canvas id="promptsChart" height="60"></canvas>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="text-muted">Today</span>
                                <div class="fw-semibold"><span data-target="1,245">1,245</span> prompts</div>
                            </div>
                            <div class="text-end">
                                <span class="text-muted">Yesterday</span>
                                <div class="fw-semibold"><span data-target="1,110">1,110</span> <i class="ti ti-arrow-up"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-center">
                        Prompt volume increased by <strong>12%</strong> today
                    </div>
                </div>
            </div>

            <!-- Active Users Widget -->
            <div class="col">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-uppercase mb-3">Active Users</h5>
                                <h3 class="mb-0 fw-normal"><span data-target="342">342</span></h3>
                                <p class="text-muted mb-2">In the last hour</p>
                            </div>
                            <div>
                                <i data-lucide="users" class="text-muted fs-24 svg-sw-10"></i>
                            </div>
                        </div>

                        <div class="progress progress-lg mb-3">
                            <div class="progress-bar" style="width: 68%;" role="progressbar"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="text-muted">Avg. Session Time</span>
                                <h5 class="mb-0">4m 12s</h5>
                            </div>
                            <div class="text-end">
                                <span class="text-muted">Returning Users</span>
                                <h5 class="mb-0">54.9%</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-center">
                        52 new users joined today
                    </div>
                </div>
            </div>

            <!-- Response Accuracy Widget -->
            <div class="col">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="text-uppercase">Response Accuracy</h5>
                            </div>
                            <div>
                                <i data-lucide="activity" class="text-muted fs-24 svg-sw-10"></i>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-center">
                            <canvas id="accuracyChart" height="120" width="120"></canvas>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-center">
                        Current accuracy: <strong>94.3%</strong>
                    </div>
                </div>
            </div>

            <!-- Token Consumption Widget -->
            <div class="col">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="text-uppercase">Token Usage</h5>
                            </div>
                            <div>
                                <i data-lucide="cpu" class="text-muted fs-24 svg-sw-10"></i>
                            </div>
                        </div>

                        <div class="mb-3">
                            <canvas id="tokenChart" height="60"></canvas>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="text-muted">Today</span>
                                <div class="fw-semibold"><span data-target="920,400">920,400</span> tokens</div>
                            </div>
                            <div class="text-end">
                                <span class="text-muted">Yesterday</span>
                                <div class="fw-semibold"><span data-target="865,100">865,100</span> <i class="ti ti-arrow-up"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-center">
                        Token usage up <strong>6.4%</strong> from yesterday
                    </div>
                </div>
            </div>
        </div> <!-- end row-->
        <div class="row justify-content-center">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header justify-content-between">
                                <h5 class="card-title"> Exportar </h5>
                                
                            </div>

                            <div class="card-body">
                                <table data-tables="export-data" class="table table-striped align-middle mt-2 mb-0">
                                    <thead class="thead-sm text-uppercase fs-xxs">
                                        <tr>
                                            <th>Produto</th>
                                            <th>Referencia</th>
                                            <th>Preço</th>
                                            <th>Change</th>
                                            <th>Volume</th>
                                            <th>Market Cap</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produtos as $p)
                                        <tr>
                                            <td>{{ $p['nome'] }}</td>
                                            <td>{{ $p['referencia'] }}</td>
                                            <td>{{ $p['valor'] }}</td>
                                            <td>{{ $p['change'] ?? 'N/A' }}</td>
                                            <td>{{ $p['volume'] ?? 'N/A' }}</td>
                                            <td>{{ $p['market_cap'] ?? 'N/A' }}</td>
                                            <td>{{ $p['status'] ?? 'Ativo' }}</td>
                                            <td class="text-center">
                                                <div class="dropdown text-muted">
                                                    <a href="#" class="dropdown-toggle drop-arrow-none fs-xxl link-reset p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <a href="#" class="dropdown-item"><i class="ti ti-eye me-1"></i> Visualizar</a>
                                                        <a href="#" class="dropdown-item"><i class="ti ti-edit me-1"></i> Editar</a>
                                                        <a href="#" class="dropdown-item text-danger"><i class="ti ti-trash me-1"></i> Deletar</a>
                                                    </div>
                                                </div>
                                                </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->

                        
                    </div>
        </div>

    </div>
@endsection
