<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Inicio de sesión del sistema" />
    <meta name="author" content="SakCode" />
    <title>Productora Audiovisual - Login</title>

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Fuente moderna: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        /* Tipografía moderna */
        body, input, button, label, h4, h5 {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            letter-spacing: 0.2px;
            line-height: 1.5;
        }

        body.bg-custom-gray {
            background-color: #f4f6f9 !important;
        }

        .card-header-custom {
            background-color: #e9ecef;
            border-bottom: 1px solid #dee2e6;
        }

        .btn-luna {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            font-weight: 500;
        }

        .btn-luna:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .logo-placeholder {
            height: 50px;
            width: auto;
            margin-bottom: 15px;
        }

        h4, h5 {
            font-weight: 500;
        }
    </style>
</head>

<body class="bg-custom-gray">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header card-header-custom pt-4 pb-2">
                                    <div class="text-center">
                                        <img src="placeholder-logo.png" alt="Logo de LUNA STUDIOS" class="logo-placeholder">
                                        <h4 class="text-center mb-1 text-secondary"><strong>LUNA STUDIOS</strong></h4>
                                        <h5 class="text-center mb-4 text-muted">Productora Audiovisual</h5>
                                    </div>
                                </div>
                                <div class="card-body pt-4">
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $item)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{$item}}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endforeach
                                    @endif

                                    <form action="{{route('login.login')}}" method="post">
                                        @csrf
                                        <div class="form-floating mb-4">
                                            <input autofocus autocomplete="off" value="invitado@gmail.com" class="form-control" name="email" id="inputEmail" type="email" placeholder="name@example.com" />
                                            <label for="inputEmail">Correo electrónico</label>
                                        </div>
                                        <div class="form-floating mb-4">
                                            <input class="form-control" name="password" value="12345678" id="inputPassword" type="password" placeholder="Password" />
                                            <label for="inputPassword">Contraseña</label>
                                        </div>
                                        <div class="d-grid mt-5 mb-0">
                                            <button class="btn btn-luna btn-block" type="submit">Iniciar sesión</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <div id="layoutAuthentication_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; LUNA STUDIOS {{ date('Y') }}</div>
                        <div>
                            <a href="#">Política de Privacidad</a>
                            &middot;
                            <a href="#">Términos y Condiciones</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
