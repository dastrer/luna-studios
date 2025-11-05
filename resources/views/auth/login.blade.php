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
        :root {
            --luna-primary: #2c3e50;
            --luna-accent: #3498db;
            --luna-accent-light: #5dade2;
            --luna-light: #ecf0f1;
            --luna-dark: #1a252f;
            --luna-gray: #bdc3c7;
            --luna-bg: #0c1a27;
        }

        /* Tipografía moderna */
        body, input, button, label, h4, h5 {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            letter-spacing: 0.2px;
            line-height: 1.5;
        }

        body.bg-custom-gray {
            background: linear-gradient(135deg, var(--luna-bg) 0%, var(--luna-dark) 50%, var(--luna-primary) 100%) !important;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        body.bg-custom-gray::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(52, 152, 219, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(236, 240, 241, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--luna-primary), var(--luna-dark)) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px 15px 0 0 !important;
        }

        .btn-luna {
            background: linear-gradient(135deg, var(--luna-accent), var(--luna-accent-light));
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.9rem 2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-luna:hover {
            background: linear-gradient(135deg, var(--luna-accent-light), var(--luna-accent));
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }

        .logo-placeholder {
            height: 60px;
            width: auto;
            margin-bottom: 15px;
        }

        h4 {
            font-weight: 700;
            background: linear-gradient(45deg, var(--luna-light), var(--luna-accent-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.8rem;
        }

        h5 {
            font-weight: 400;
            color: var(--luna-gray) !important;
            font-size: 1.1rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: 1px solid rgba(44, 62, 80, 0.2);
            border-radius: 8px;
            padding: 1rem 1.2rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: var(--luna-accent);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            background: white;
        }

        .form-floating label {
            color: var(--luna-primary);
            font-weight: 500;
            padding: 1rem 1.2rem;
        }

        .form-control:focus + label {
            color: var(--luna-accent);
        }

        .alert-danger {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid rgba(231, 76, 60, 0.3);
            color: #c0392b;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .alert-danger .btn-close {
            filter: brightness(0.8);
        }

        #layoutAuthentication_footer {
            background: rgba(26, 37, 47, 0.9) !important;
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        #layoutAuthentication_footer .text-muted {
            color: var(--luna-gray) !important;
        }

        #layoutAuthentication_footer a {
            color: var(--luna-accent-light) !important;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        #layoutAuthentication_footer a:hover {
            color: var(--luna-accent) !important;
        }

        /* Efectos de animación suave */
        .card {
            animation: cardEntrance 0.6s ease-out;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Floating elements */
        .floating-element {
            position: absolute;
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, var(--luna-accent), transparent);
            border-radius: 50%;
            filter: blur(30px);
            opacity: 0.1;
            animation: float 8s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            top: 60%;
            right: 15%;
            animation-delay: 3s;
        }

        .floating-element:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 6s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(180deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card {
                margin: 1rem;
            }
            
            h4 {
                font-size: 1.5rem;
            }
            
            h5 {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body class="bg-custom-gray">
    <!-- Floating background elements -->
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>

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
                                        <h4 class="text-center mb-1"><strong>LUNA STUDIOS</strong></h4>
                                        <h5 class="text-center mb-4">Productora Audiovisual</h5>
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
            <footer class="py-4 mt-auto">
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