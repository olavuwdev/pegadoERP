@extends('layouts.form')

@section('form')
    <div class="container-fluid">

                    
                    <div class="row justify-content-center py-5">
                        <div class="col-xxl-5 col-xl-7 text-center">
                            <span class="badge badge-default fw-normal shadow px-2 py-1 mb-2 fst-italic fs-xxs">
                                <i data-lucide="sparkles" class="fs-sm me-1"></i> Medium and Large Business
                            </span>
                            <h3 class="fw-bold">
                                The Ultimate Admin & Dashboard Theme
                            </h3>

                            <p class="fs-md text-muted mb-0">
                                A premium collection of elegant, accessible components and a powerful codebase. Built for modern frameworks. Developer Friendly. Production Ready.
                            </p>
                        </div>
                    </div>
                    

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

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- AI Requests -->
                                        <div class="col-xl-3 col-md-6">
                                            <div class="text-center">
                                                <p class="mb-4"><i data-lucide="bot"></i> AI Requests</p>
                                                <h2 class="fw-bold mb-0"><span data-target="807,621">0</span></h2>
                                                <p class="text-muted">Total AI requests in last 30 days</p>
                                                <p class="mb-0 mt-4"><i data-lucide="calendar"></i> Data from May</p>
                                            </div>
                                        </div>

                                        <!-- Usage Duration -->
                                        <div class="col-xl-3 col-md-6 order-xl-last">
                                            <div class="text-center">
                                                <p class="mb-4"><i data-lucide="timer"></i> Usage Duration</p>
                                                <h2 class="fw-bold mb-0"> 9 Months</h2>
                                                <p class="text-muted">Including 4 weeks this quarter</p>
                                                <p class="mb-0 mt-4"><i data-lucide="clock"></i> Last accessed: 12.06.2025</p>
                                            </div>
                                        </div>

                                        <!-- AI Usage Chart -->
                                        <div class="col-xl-6">
                                            <div class="w-100" style="height: 240px;">
                                                <canvas id="activeUsersChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="d-flex align-items-center text-muted justify-content-between">
                                        <div>
                                            Last update: 16.06.2025
                                        </div>
                                        <div>
                                            You received 2 new AI feedback reports
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end row -->

                    <div class="row">
                        <div class="col-xxl-6">
                            <div class="card">
                                <div class="card-header justify-content-between align-items-center border-dashed">
                                    <h4 class="card-title mb-0">Recent AI Sessions</h4>
                                    <div class="d-flex gap-2">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-light">
                                            <i class="ti ti-plus me-1"></i> New Session
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary">
                                            <i class="ti ti-file-export me-1"></i> Export Logs
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-custom table-sm table-nowrap table-hover mb-0">
                                            <tbody>
                                                <!-- Session Row Example -->
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/user-1.jpg" alt="" class="avatar-sm rounded-circle me-2">
                                                            <div>
                                                                <span class="text-muted fs-xs">Alice Cooper</span>
                                                                <h5 class="fs-base mb-0"><a href="#!" class="text-body">#AI-5001</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-xs">Model</span>
                                                        <h5 class="fs-base mb-0 fw-normal">GPT-4</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-xs">Date</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2025-05-01</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-xs">Tokens</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2,304</h5>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fs-xs">Status</span>
                                                        <h5 class="fs-base mb-0 fw-normal"><i class="ti ti-circle-filled fs-xs"></i> Completed</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical fs-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="#" class="dropdown-item">View Details</a>
                                                                <a href="#" class="dropdown-item">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Repeat more AI session entries -->
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/user-2.jpg" alt="" class="avatar-sm rounded-circle me-2">
                                                            <div>
                                                                <span class="text-muted fs-xs">David Lee</span>
                                                                <h5 class="fs-base mb-0"><a href="#!" class="text-body">#AI-5002</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Model</span>
                                                        <h5 class="fs-base mb-0 fw-normal">DALL·E</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Date</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2025-04-30</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Tokens</span>
                                                        <h5 class="fs-base mb-0 fw-normal">580</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Status</span>
                                                        <h5 class="fs-base mb-0 fw-normal"><i class="ti ti-circle-filled fs-xs"></i> Pending</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical fs-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="#" class="dropdown-item">View Details</a>
                                                                <a href="#" class="dropdown-item">Retry</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/user-3.jpg" alt="" class="avatar-sm rounded-circle me-2">
                                                            <div>
                                                                <span class="text-muted fs-xs">Sophia Turner</span>
                                                                <h5 class="fs-base mb-0"><a href="#!" class="text-body">#AI-5003</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Model</span>
                                                        <h5 class="fs-base mb-0 fw-normal">Whisper</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Date</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2025-04-29</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Tokens</span>
                                                        <h5 class="fs-base mb-0 fw-normal">1,102</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Status</span>
                                                        <h5 class="fs-base mb-0 fw-normal"><i class="ti ti-circle-filled fs-xs"></i> Completed</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical fs-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="#" class="dropdown-item">View Details</a>
                                                                <a href="#" class="dropdown-item">Retry</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/user-4.jpg" alt="" class="avatar-sm rounded-circle me-2">
                                                            <div>
                                                                <span class="text-muted fs-xs">James Wilson</span>
                                                                <h5 class="fs-base mb-0"><a href="#!" class="text-body">#AI-5004</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Model</span>
                                                        <h5 class="fs-base mb-0 fw-normal">GPT-3.5</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Date</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2025-04-28</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Tokens</span>
                                                        <h5 class="fs-base mb-0 fw-normal">760</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Status</span>
                                                        <h5 class="fs-base mb-0 fw-normal"><i class="ti ti-circle-filled fs-xs text-danger"></i> Failed</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical fs-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="#" class="dropdown-item">View Details</a>
                                                                <a href="#" class="dropdown-item">Retry</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/user-5.jpg" alt="" class="avatar-sm rounded-circle me-2">
                                                            <div>
                                                                <span class="text-muted fs-xs">Ava Carter</span>
                                                                <h5 class="fs-base mb-0"><a href="#!" class="text-body">#AI-5005</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Model</span>
                                                        <h5 class="fs-base mb-0 fw-normal">Claude 2</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Date</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2025-04-27</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Tokens</span>
                                                        <h5 class="fs-base mb-0 fw-normal">1,678</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Status</span>
                                                        <h5 class="fs-base mb-0 fw-normal"><i class="ti ti-circle-filled fs-xs"></i> Completed</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical fs-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="#" class="dropdown-item">View Details</a>
                                                                <a href="#" class="dropdown-item">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/user-6.jpg" alt="" class="avatar-sm rounded-circle me-2">
                                                            <div>
                                                                <span class="text-muted fs-xs">Ethan Brooks</span>
                                                                <h5 class="fs-base mb-0"><a href="#!" class="text-body">#AI-5006</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Model</span>
                                                        <h5 class="fs-base mb-0 fw-normal">Gemini Pro</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Date</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2025-04-26</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Tokens</span>
                                                        <h5 class="fs-base mb-0 fw-normal">945</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Status</span>
                                                        <h5 class="fs-base mb-0 fw-normal"><i class="ti ti-circle-filled fs-xs"></i> Pending</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical fs-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="#" class="dropdown-item">View Details</a>
                                                                <a href="#" class="dropdown-item">Retry</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/user-7.jpg" alt="" class="avatar-sm rounded-circle me-2">
                                                            <div>
                                                                <span class="text-muted fs-xs">Mia Clarke</span>
                                                                <h5 class="fs-base mb-0"><a href="#!" class="text-body">#AI-5007</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Model</span>
                                                        <h5 class="fs-base mb-0 fw-normal">GPT-4 Turbo</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Date</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2025-04-25</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Tokens</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2,189</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Status</span>
                                                        <h5 class="fs-base mb-0 fw-normal"><i class="ti ti-circle-filled fs-xs"></i> Completed</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical fs-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="#" class="dropdown-item">View Details</a>
                                                                <a href="#" class="dropdown-item">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/user-8.jpg" alt="" class="avatar-sm rounded-circle me-2">
                                                            <div>
                                                                <span class="text-muted fs-xs">Lucas Perry</span>
                                                                <h5 class="fs-base mb-0"><a href="#!" class="text-body">#AI-5008</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Model</span>
                                                        <h5 class="fs-base mb-0 fw-normal">Stable Diffusion</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Date</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2025-04-24</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Tokens</span>
                                                        <h5 class="fs-base mb-0 fw-normal">312</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Status</span>
                                                        <h5 class="fs-base mb-0 fw-normal"><i class="ti ti-circle-filled fs-xs text-danger"></i> Failed</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical fs-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="#" class="dropdown-item">View Details</a>
                                                                <a href="#" class="dropdown-item">Retry</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/user-9.jpg" alt="" class="avatar-sm rounded-circle me-2">
                                                            <div>
                                                                <span class="text-muted fs-xs">Chloe Adams</span>
                                                                <h5 class="fs-base mb-0"><a href="#!" class="text-body">#AI-5009</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Model</span>
                                                        <h5 class="fs-base mb-0 fw-normal">GPT-4</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Date</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2025-04-23</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Tokens</span>
                                                        <h5 class="fs-base mb-0 fw-normal">1,784</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Status</span>
                                                        <h5 class="fs-base mb-0 fw-normal"><i class="ti ti-circle-filled fs-xs"></i> Completed</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical fs-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="#" class="dropdown-item">View Details</a>
                                                                <a href="#" class="dropdown-item">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="assets/images/users/user-10.jpg" alt="" class="avatar-sm rounded-circle me-2">
                                                            <div>
                                                                <span class="text-muted fs-xs">Benjamin Gray</span>
                                                                <h5 class="fs-base mb-0"><a href="#!" class="text-body">#AI-5010</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Model</span>
                                                        <h5 class="fs-base mb-0 fw-normal">Whisper</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Date</span>
                                                        <h5 class="fs-base mb-0 fw-normal">2025-04-22</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Tokens</span>
                                                        <h5 class="fs-base mb-0 fw-normal">890</h5>
                                                    </td>
                                                    <td><span class="text-muted fs-xs">Status</span>
                                                        <h5 class="fs-base mb-0 fw-normal"><i class="ti ti-circle-filled fs-xs"></i> Pending</h5>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle text-muted drop-arrow-none card-drop p-0" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical fs-lg"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a href="#" class="dropdown-item">View Details</a>
                                                                <a href="#" class="dropdown-item">Retry</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-footer border-0">
                                    <div class="align-items-center justify-content-between row text-center text-sm-start">
                                        <div class="col-sm">
                                            <div class="text-muted">Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">10</span> of <span class="fw-semibold">2684</span> Sessions</div>
                                        </div>
                                        <div class="col-sm-auto mt-3 mt-sm-0">
                                            <ul class="pagination pagination-sm pagination-boxed mb-0 justify-content-center">
                                                <li class="page-item disabled"><a href="#" class="page-link"><i class="ti ti-chevron-left"></i></a></li>
                                                <li class="page-item active"><a href="#" class="page-link">1</a></li>
                                                <li class="page-item "><a href="#" class="page-link">2</a></li>
                                                <li class="page-item "><a href="#" class="page-link"><i class="ti ti-chevron-right"></i></a></li>
                                            </ul>
                                        </div> <!-- end col-->
                                    </div> <!-- end row-->
                                </div>
                            </div> <!-- end card-->
                        </div> <!-- end col-->

                        <div class="col-xxl-6">
                            <div class="card">
                                <div class="card-header border-dashed">
                                    <h4 class="card-title mb-0">AI Model Usage Summary</h4>
                                </div>

                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-centered table-custom table-nowrap mb-0">
                                            <thead class="bg-light-subtle thead-sm">
                                                <tr class="text-uppercase fs-xxs">
                                                    <th>Model</th>
                                                    <th>Requests</th>
                                                    <th>Total Tokens</th>
                                                    <th>Average Tokens</th>
                                                    <th>Last Used</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>GPT-4</td>
                                                    <td>1,248</td>
                                                    <td>2,483,920</td>
                                                    <td>1,989</td>
                                                    <td>2025-06-15</td>
                                                </tr>
                                                <tr>
                                                    <td>DALL·E</td>
                                                    <td>328</td>
                                                    <td>194,320</td>
                                                    <td>592</td>
                                                    <td>2025-06-14</td>
                                                </tr>
                                                <tr>
                                                    <td>Claude 2</td>
                                                    <td>814</td>
                                                    <td>1,102,390</td>
                                                    <td>1,354</td>
                                                    <td>2025-06-13</td>
                                                </tr>
                                                <tr>
                                                    <td>Whisper</td>
                                                    <td>512</td>
                                                    <td>653,210</td>
                                                    <td>1,275</td>
                                                    <td>2025-06-12</td>
                                                </tr>
                                                <tr>
                                                    <td>Stable Diffusion</td>
                                                    <td>102</td>
                                                    <td>61,400</td>
                                                    <td>602</td>
                                                    <td>2025-06-10</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-footer border-top-0 text-end">
                                    <span class="text-muted">Updated 1 hour ago</span>
                                </div>
                            </div> <!-- end card-->

                            <div class="card">
                                <div class="card-header border-dashed">
                                    <h4 class="card-title mb-0">AI API Performance Metrics</h4>
                                </div>

                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-centered table-nowrap table-custom mb-0">
                                            <thead class="bg-light-subtle thead-sm">
                                                <tr class="text-uppercase fs-xxs">
                                                    <th>Endpoint</th>
                                                    <th>Avg Latency</th>
                                                    <th>Requests</th>
                                                    <th>Error Rate</th>
                                                    <th>Cost ($)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>/v1/chat/completions</td>
                                                    <td>720ms</td>
                                                    <td>8,204</td>
                                                    <td>0.18%</td>
                                                    <td>128.34</td>
                                                </tr>
                                                <tr>
                                                    <td>/v1/images/generations</td>
                                                    <td>930ms</td>
                                                    <td>1,029</td>
                                                    <td>0.03%</td>
                                                    <td>43.89</td>
                                                </tr>
                                                <tr>
                                                    <td>/v1/audio/transcriptions</td>
                                                    <td>1.2s</td>
                                                    <td>489</td>
                                                    <td>0.00%</td>
                                                    <td>16.45</td>
                                                </tr>
                                                <tr>
                                                    <td>/v1/embeddings</td>
                                                    <td>610ms</td>
                                                    <td>2,170</td>
                                                    <td>0.10%</td>
                                                    <td>24.98</td>
                                                </tr>
                                                <tr>
                                                    <td>/v1/chat/moderation</td>
                                                    <td>450ms</td>
                                                    <td>5,025</td>
                                                    <td>0.01%</td>
                                                    <td>7.52</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-footer border-top-0 text-end">
                                    <span class="text-muted">API stats updated: 2025-06-16 08:32 AM</span>
                                </div>
                            </div>

                        </div>


                    </div> <!-- end row-->



    </div>
@endsection
