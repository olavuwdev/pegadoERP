
<!DOCTYPE html>
<html lang="pt-BR">


<head>
    <meta charset="utf-8">
    <title>Login - PegadoERP </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Login do sistema PegadoERP. Acesse sua conta para gerenciar suas operações empresariais de forma eficiente e segura.">
    <meta name="keywords" content="PegadoERP, login, sistema ERP, gestão empresarial, acesso seguro">
    <meta name="author" content="OlavwuDev">

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

    <div class="auth-box overflow-hidden align-items-center d-flex">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-md-6 col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="auth-brand mb-4">
                                <a href="{{ url('/') }}" class="logo-dark">
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="avatar avatar-xs rounded-circle text-bg-dark">
                                            <span class="avatar-title">
                                                <i data-lucide="sparkles" class="fs-md"></i>
                                            </span>
                                        </span>
                                        <span class="logo-text text-body fw-bold fs-xl">PegadoERP</span>
                                    </span>
                                </a>
                                <a href="{{ url('/') }}" class="logo-light">
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="avatar avatar-xs rounded-circle text-bg-dark">
                                            <span class="avatar-title">
                                                <i data-lucide="sparkles" class="fs-md"></i>
                                            </span>
                                        </span>
                                        <span class="logo-text text-white fw-bold fs-xl">PegadoERP</span>
                                    </span>
                                </a>
                                <p class="text-muted w-lg-75 mt-3">Faça o login. Informe o CPF, email e senha para continuar.</p>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="">
                                <form action="{{ route('login.attempt') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="userCPFCNPJ" class="form-label">CPF/CNPJ <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="userCPFCNPJ" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" placeholder="000.000.000-00" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="userEmail" class="form-label">Endereço de e-mail <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="email" class="form-control" id="userEmail" name="email" value="{{ old('email') }}" placeholder="voce@exemplo.com" autocomplete="email" required>
                                        </div>
                                    </div>
            
                                    <div class="mb-3">
                                        <label for="userPassword" class="form-label">Senha <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="userPassword" name="password" placeholder="********" autocomplete="current-password" required>
                                        </div>
                                    </div>
            
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input form-check-input-light fs-14" type="checkbox" id="rememberMe" name="remember">
                                            <label class="form-check-label" for="rememberMe">Manter-me conectado</label>
                                        </div>
                                        <a href="auth-reset-pass.html" class="text-decoration-underline link-offset-3 text-muted">Esqueceu a senha?</a>
                                    </div>
            
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary fw-semibold py-2">Entrar</button>
                                    </div>
                                </form>
            
                                <p class="text-muted text-center mt-4 mb-0">
                                    Novo por aqui? <a href="auth-sign-up.html" class="text-decoration-underline link-offset-3 fw-semibold">Crie uma conta</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-muted mt-4 mb-0">
                        © <script>document.write(new Date().getFullYear())</script> by <span class="fw-semibold">OlavwuDev</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    

    <!-- end auth-fluid-->
    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script>
        (function () {
            var cpfInput      = document.getElementById('userCPFCNPJ');
            var emailInput    = document.getElementById('userEmail');
            var rememberCheck = document.getElementById('rememberMe');
            var form          = document.querySelector('form');

            var saved = localStorage.getItem('pegado_remember');
            if (saved) {
                var data = JSON.parse(saved);
                cpfInput.value   = data.cpf_cnpj || '';
                emailInput.value = data.email    || '';
                rememberCheck.checked = true;
            }

            form.addEventListener('submit', function () {
                if (rememberCheck.checked) {
                    localStorage.setItem('pegado_remember', JSON.stringify({
                        cpf_cnpj: cpfInput.value,
                        email:    emailInput.value
                    }));
                } else {
                    localStorage.removeItem('pegado_remember');
                }
            });
        })();
    </script>

</body>


</html>
