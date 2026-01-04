
        <header class="app-topbar">
            <div class="container-fluid topbar-menu">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <a href="index.html" class="logo-dark">
                            <img src="{{ asset('assets/images/pegadoERP-logo.png') }}" height="50" alt="Logo">
                        </a>
                        <a href="index.html" class="logo-light">
                            <span class="d-flex align-items-center gap-1">
                                <img src="{{ asset('assets/images/pegadoERP-logo.png') }}" height="28" alt="Logo">
                            </span>
                        </a>
                    </div>

                    <div class="d-lg-none d-flex mx-1">
                        <a href="index.html">
                            <img src="{{ asset('assets/images/pegadoERP-logo.png') }}" height="30" alt="Logo">
                        </a>
                    </div>

                    <!-- Sidebar Hover Menu Toggle Button -->
                    <button class="button-collapse-toggle d-xl-none">
                        <i data-lucide="menu" class="fs-22 align-middle"></i>
                    </button>

                    <!-- Topbar Link Item -->
                    <div class="topbar-item d-none d-lg-flex">
                        <a href="#!" class="topbar-link btn shadow-none btn-link px-2 disabled"> v1.0.0</a>
                    </div>

                    <!-- Topbar Link Item -->
                    <div class="topbar-item d-none d-lg-flex">
                        <a href="#!" class="topbar-link btn shadow-none btn-link px-2"> Components</a>
                    </div>

                    <!-- Dropdown -->
                    <div class="topbar-item">
                        <div class="dropdown">
                            <a href="#!" class="topbar-link btn shadow-none btn-link dropdown-toggle drop-arrow-none px-2"
                               data-bs-toggle="dropdown" data-bs-offset="0,13">
                                Dropdown <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#!">
                                    <i class="ti ti-user-plus fs-15 me-1"></i> Add Project Member
                                </a>
                                <a class="dropdown-item" href="#!">
                                    <i class="ti ti-activity fs-15 me-1"></i> View Activity
                                </a>
                                <a class="dropdown-item" href="#!">
                                    <i class="ti ti-settings fs-15 me-1"></i> Settings
                                </a>
                            </div> <!-- end dropdown-menu-->
                        </div> <!-- end dropdown-->
                    </div> <!-- end topbar item-->

                    <!-- Mega Menu Dropdown -->
                    <div class="topbar-item d-none d-md-flex">
                        <div class="dropdown">
                            <button class="topbar-link btn shadow-none btn-link px-2 dropdown-toggle drop-arrow-none"
                                    data-bs-toggle="dropdown" data-bs-offset="0,13" type="button" aria-haspopup="false"
                                    aria-expanded="false">
                                Mega Menu <i class="ti ti-chevron-down ms-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-xxl p-0">
                                <div class="h-100" style="max-height: 380px;" data-simplebar>
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <div class="p-3">
                                                <h5 class="fw-semibold fs-sm dropdown-header">Workspace Tools</h5>
                                                <ul class="list-unstyled">
                                                    <li><a href="javascript:void(0);" class="dropdown-item">My Dashboard</a>
                                                    </li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Recent Activity</a>
                                                    </li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Notifications
                                                        Center</a></li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">File Manager</a>
                                                    </li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Calendar View</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="p-3">
                                                <h5 class="fw-semibold fs-sm dropdown-header">Team Operations</h5>
                                                <ul class="list-unstyled">
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Team Overview</a>
                                                    </li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Meeting Schedule</a>
                                                    </li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Timesheets</a></li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Feedback Hub</a>
                                                    </li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Resource
                                                        Allocation</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="p-3">
                                                <h5 class="fw-semibold fs-sm dropdown-header">Account Settings</h5>
                                                <ul class="list-unstyled">
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Profile Settings</a>
                                                    </li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Billing & Plans</a>
                                                    </li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Integrations</a>
                                                    </li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Privacy &
                                                        Security</a></li>
                                                    <li><a href="javascript:void(0);" class="dropdown-item">Support Center</a>
                                                    </li>
                                                </ul>
                                            </div> <!-- end dropdown-->
                                        </div> <!-- end col-->
                                    </div> <!-- end row-->
                                </div> <!-- end .h-100-->
                            </div> <!-- .dropdown-menu-->
                        </div> <!-- .dropdown-->
                    </div> <!-- end topbar-item -->
                </div> <!-- .d-flex-->

                <div class="d-flex align-items-center gap-2">
                    <!-- Search -->
                    <div class="app-search d-none d-xl-flex me-xl-2">
                        <input type="search" class="form-control topbar-search" name="search"
                               placeholder="Search for something...">
                        <i data-lucide="search" class="app-search-icon text-muted"></i>
                    </div>

                 

                    

                    <!-- Notification Dropdown -->
                    <div class="topbar-item">
                        <div class="dropdown">
                            <button class="topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown"
                                    data-bs-offset="0,19" type="button" data-bs-auto-close="outside" aria-haspopup="false"
                                    aria-expanded="false">
                                <i data-lucide="bell" class="fs-xxl"></i>
                                <span class="badge badge-square text-bg-success topbar-badge">9</span>
                            </button>

                            <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg">
                                <div class="px-3 py-2 border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fs-md fw-semibold">Notifications</h6>
                                        </div>
                                        <div class="col text-end">
                                            <a href="#!" class="badge text-bg-light badge-label py-1">9 Alerts</a>
                                        </div>
                                    </div>
                                </div>

                                <div style="max-height: 300px;" data-simplebar>
                                    <!-- item 1 -->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="notification-1">
                                        <span class="d-flex gap-2">
                                            <span class="avatar-md flex-shrink-0">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-22">
                                                    <i data-lucide="cloud-cog" class="fs-xl fill-primary"></i>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">Backup completed successfully</span><br>
                                                <span class="fs-xs">Just now</span>
                                            </span>
                                            <button type="button" class="flex-shrink-0 text-muted btn shadow-none btn-link p-0"
                                                    data-dismissible="#notification-1">
                                                <i data-lucide="circle-x" class="fs-xxl"></i>
                                            </button>
                                        </span>
                                    </div>

                                    <!-- item 2 -->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="notification-2">
                                        <span class="d-flex gap-2">
                                            <span class="avatar-md flex-shrink-0">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-22">
                                                    <i data-lucide="bug" class="fs-xl fill-primary"></i>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">New bug reported in Payment Module</span><br>
                                                <span class="fs-xs">8 minutes ago</span>
                                            </span>
                                            <button type="button" class="flex-shrink-0 text-muted btn shadow-none btn-link p-0"
                                                    data-dismissible="#notification-2">
                                                <i data-lucide="circle-x" class="fs-xxl"></i>
                                            </button>
                                        </span>
                                    </div>

                                    <!-- item 3 -->
                                    <div class="dropdown-item notification-item py-2 text-wrap active" id="message-1">
                                        <span class="d-flex gap-2">
                                            <span class="flex-shrink-0">
                                                <img src="assets/images/users/user-3.jpg" class="avatar-md rounded-circle"
                                                     alt="User Avatar">
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">Olivia Bennett</span> shared a new report in <span
                                                    class="fw-medium text-body">Weekly Planning</span><br>
                                                <span class="fs-xs">2 minutes ago</span>
                                            </span>
                                            <button type="button" class="flex-shrink-0 text-muted btn shadow-none btn-link p-0"
                                                    data-dismissible="#message-1">
                                                <i data-lucide="circle-x" class="fs-xxl"></i>
                                            </button>
                                        </span>
                                    </div>

                                    <!-- item 4 -->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="message-2">
                                        <span class="d-flex gap-2">
                                            <span class="flex-shrink-0">
                                                <img src="assets/images/users/user-4.jpg" class="avatar-md rounded-circle"
                                                     alt="User Avatar">
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">Lucas Gray</span> mentioned you in <span
                                                    class="fw-medium text-body">Sprint Standup</span><br>
                                                <span class="fs-xs">14 minutes ago</span>
                                            </span>
                                            <button type="button" class="flex-shrink-0 text-muted btn shadow-none btn-link p-0"
                                                    data-dismissible="#message-2">
                                                <i data-lucide="circle-x" class="fs-xxl"></i>
                                            </button>
                                        </span>
                                    </div>

                                    <!-- item 5 -->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="message-3">
                                        <span class="d-flex gap-2">
                                            <span class="avatar-md flex-shrink-0">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-22">
                                                    <i data-lucide="file-warning" class="fs-22 fill-primary"></i>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                Security policy update required for your account<br>
                                                <span class="fs-xs">22 minutes ago</span>
                                            </span>
                                            <button type="button" class="flex-shrink-0 text-muted btn shadow-none btn-link p-0"
                                                    data-dismissible="#message-3">
                                                <i data-lucide="circle-x" class="fs-xxl"></i>
                                            </button>
                                        </span>
                                    </div>

                                    <!-- item 6 -->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="notification-6">
                                        <span class="d-flex gap-2">
                                            <span class="avatar-md flex-shrink-0">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-22">
                                                    <i data-lucide="mail" class="fs-xl fill-primary"></i>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">You've received a new support ticket</span><br>
                                                <span class="fs-xs">18 minutes ago</span>
                                            </span>
                                            <button type="button" class="flex-shrink-0 text-muted btn shadow-none btn-link p-0"
                                                    data-dismissible="#notification-6">
                                                <i data-lucide="circle-x" class="fs-xxl"></i>
                                            </button>
                                        </span>
                                    </div>

                                    <!-- item 7 -->
                                    <div class="dropdown-item notification-item py-2 text-wrap" id="notification-7">
                                        <span class="d-flex gap-2">
                                            <span class="avatar-md flex-shrink-0">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-22">
                                                    <i data-lucide="calendar-clock" class="fs-xl fill-primary"></i>
                                                </span>
                                            </span>
                                            <span class="flex-grow-1 text-muted">
                                                <span class="fw-medium text-body">System maintenance starts at 12 AM</span><br>
                                                <span class="fs-xs">1 hour ago</span>
                                            </span>
                                            <button type="button" class="flex-shrink-0 text-muted btn shadow-none btn-link p-0"
                                                    data-dismissible="#notification-7">
                                                <i data-lucide="circle-x" class="fs-xxl"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div> <!-- end dropdown -->


                                <!-- All-->
                                <a href="javascript:void(0);"
                                   class="dropdown-item text-center text-reset text-decoration-underline link-offset-2 fw-bold notify-item border-top border-light py-2">
                                    View All Notifications
                                </a>

                            </div>
                        </div>
                    </div>

                    <!-- Button Trigger Customizer Offcanvas -->
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas"
                                type="button">
                            <i data-lucide="settings" class="fs-xxl"></i>
                        </button>
                    </div>

                    <!-- Light/Dark Mode Button -->
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" id="light-dark-mode" type="button">
                            <i data-lucide="moon" class="fs-xxl mode-light-moon"></i>
                            <i data-lucide="sun" class="fs-xxl mode-light-sun"></i>
                        </button>
                    </div>

                    <!-- Monochrome Mode Button -->
                    <div class="topbar-item d-none d-sm-flex">
                        <button class="topbar-link" id="monochrome-mode" type="button">
                            <i data-lucide="palette" class="fs-xxl mode-light-moon"></i>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="topbar-item nav-user">
                        <div class="dropdown">
                            <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                               data-bs-offset="0,13" href="#!" aria-haspopup="false" aria-expanded="false">
                                <img src="assets/images/users/user-2.jpg" width="32" class="rounded-circle d-flex"
                                     alt="user-image">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- Header -->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome back!</h6>
                                </div>

                                <!-- My Profile -->
                                <a href="#!" class="dropdown-item">
                                    <i class="ti ti-user-circle me-2 fs-17 align-middle"></i>
                                    <span class="align-middle">Profile</span>
                                </a>

                                <!-- Notifications -->
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <i class="ti ti-bell-ringing me-2 fs-17 align-middle"></i>
                                    <span class="align-middle">Notifications</span>
                                </a>

                                <!-- Wallet -->
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <i class="ti ti-credit-card me-2 fs-17 align-middle"></i>
                                    <span class="align-middle">Balance: <span class="fw-semibold">$985.25</span></span>
                                </a>

                                <!-- Settings -->
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <i class="ti ti-settings-2 me-2 fs-17 align-middle"></i>
                                    <span class="align-middle">Account Settings</span>
                                </a>

                                <!-- Support -->
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <i class="ti ti-headset me-2 fs-17 align-middle"></i>
                                    <span class="align-middle">Support Center</span>
                                </a>

                                <!-- Divider -->
                                <div class="dropdown-divider"></div>

                                <!-- Lock -->
                                <a href="auth-lock-screen.html" class="dropdown-item">
                                    <i class="ti ti-lock me-2 fs-17 align-middle"></i>
                                    <span class="align-middle">Lock Screen</span>
                                </a>

                                <!-- Logout -->
                                <a href="javascript:void(0);" class="dropdown-item text-danger fw-semibold">
                                    <i class="ti ti-logout-2 me-2 fs-17 align-middle"></i>
                                    <span class="align-middle">Log Out</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Topbar End -->

        <script>
            // Skin Dropdown
            document.querySelectorAll('[data-dropdown="custom"]').forEach(dropdown => {
                const trigger = dropdown.querySelector('a[data-bs-toggle="dropdown"], button[data-bs-toggle="dropdown"]');
                const items = dropdown.querySelectorAll('button[data-skin]');

                const triggerImg = trigger.querySelector('[data-trigger-img]');
                const triggerLabel = trigger.querySelector('[data-trigger-label]');

                const config = JSON.parse(JSON.stringify(window.config));
                const currentSkin = config.skin;

                items.forEach(item => {
                    const itemSkin = item.getAttribute('data-skin');
                    const itemImg = item.querySelector('img')?.getAttribute('src');
                    const itemText = item.querySelector('span')?.textContent.trim();

                    // Set active on load
                    if (itemSkin === currentSkin) {
                        item.classList.add('drop-custom-active');
                        if (triggerImg && itemImg) triggerImg.setAttribute('src', itemImg);
                        if (triggerLabel && itemText) triggerLabel.textContent = itemText;
                    } else {
                        item.classList.remove('drop-custom-active');
                    }

                    // Click handler
                    item.addEventListener('click', function () {
                        items.forEach(i => i.classList.remove('drop-custom-active'));
                        this.classList.add('drop-custom-active');

                        const newImg = this.querySelector('img')?.getAttribute('src');
                        const newText = this.querySelector('span')?.textContent.trim();

                        if (triggerImg && newImg) triggerImg.setAttribute('src', newImg);
                        if (triggerLabel && newText) triggerLabel.textContent = newText;

                        if (typeof layoutCustomizer !== 'undefined') {
                            layoutCustomizer.changeSkin(itemSkin);
                        }
                    });
                });
            });
        </script>