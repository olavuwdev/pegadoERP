

<!DOCTYPE html>
<html lang="pt-BR">


<head>
    <meta charset="utf-8">
    <title>PegadoERP | Responsive Bootstrap 5 Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="admin dashboard template on Themeforest. Perfect for building CRM, CMS, project management tools, and custom web apps with clean UI, responsive design, and powerful features.">
    <meta name="keywords" content="Vona, Admin dashboard, Themeforest, HTML template,Shadcn, Bootstrap admin, CRM template, CMS template, responsive admin, web app UI, admin theme, best admin template">
    <meta name="author" content="Coderthemes">
    @yield('style')

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css">

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">

    <script src="{{ asset('assets/plugins/lucide/lucide.min.js') }}"></script>

</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        
       @include('layouts.header')

        @include('layouts.sidebar')


        <div class="content-page">
            {{-- Container para alertas posição fixa --}}
            {{-- ALERTAS GLOBAIS --}}
            <div id="alert-container"
                class="position-fixed top-8 end-0 p-3"
                style="z-index: 1050; width: 420px;">
            </div>

            
                <!-- container -->
            @yield('content')
                <!-- Footer Start -->
            @include('layouts.footer')
                <!-- end Footer -->

        </div>
        <!-- ============================================================== -->
        <!-- End of Main Content -->
        <!-- ============================================================== -->

    </div>
   

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/mask/jquery.mask.js') }}"></script>

    <!-- Dashboard Page js -->
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>
    @yield('scripts')
    <script>
    window.showAlert = function (message, type = 'success', autoClose = true, delay = 5000) {

        const alert = $(`
            <div class="alert alert-${type} alert-dismissible fade show mb-2" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);

        $('#alert-container').append(alert);

        if (autoClose) {
            setTimeout(function () {
                alert.alert('close');
            }, delay);
        }
    };
</script>


</body>



</html>