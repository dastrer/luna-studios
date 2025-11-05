<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistema de gestión para LUNA STUDIOS - Proyecto de grado INCOS El Alto" />
    <meta name="author" content="LUNA STUDIOS" />
    <title>LUNA STUDIOS - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --luna-primary: #2c3e50;
            --luna-accent: #3498db;
            --luna-accent-light: #5dade2;
            --luna-light: #ecf0f1;
            --luna-dark: #1a252f;
            --luna-gray: #bdc3c7;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0c1a27 0%, #1a252f 50%, #2c3e50 100%);
            color: var(--luna-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(52, 152, 219, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(236, 240, 241, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(52, 152, 219, 0.08) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .navbar {
            background: rgba(26, 37, 47, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(52, 152, 219, 0.3);
            padding: 1.2rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-scrolled {
            background: rgba(26, 37, 47, 0.98);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--luna-light);
            font-size: 1.4rem;
            letter-spacing: 1px;
            background: linear-gradient(45deg, var(--luna-light), var(--luna-accent-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--luna-accent), var(--luna-accent-light));
            border: none;
            border-radius: 8px;
            padding: 0.9rem 2.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
            background: linear-gradient(135deg, var(--luna-accent-light), var(--luna-accent));
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 0.9rem 2.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.6);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.1);
        }
        
        .hero-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 0;
            position: relative;
        }
        
        .welcome-container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }
        
        .welcome-title {
            font-size: 3.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, var(--luna-light), var(--luna-accent-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.1;
            text-align: center;
        }
        
        .welcome-subtitle {
            font-size: 1.3rem;
            font-weight: 400;
            color: var(--luna-accent-light);
            text-align: center;
            margin-bottom: 2rem;
            letter-spacing: 0.5px;
        }
        
        .welcome-description {
            font-size: 1.2rem;
            line-height: 1.7;
            color: var(--luna-gray);
            margin-bottom: 3rem;
            text-align: center;
            font-weight: 300;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 3rem 0;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(52, 152, 219, 0.5);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, var(--luna-accent), var(--luna-accent-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .feature-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--luna-light);
            margin-bottom: 0.5rem;
        }
        
        .feature-description {
            font-size: 0.9rem;
            color: var(--luna-gray);
            line-height: 1.5;
        }
        
        .project-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 2.5rem;
            margin: 3rem auto;
            max-width: 700px;
            backdrop-filter: blur(10px);
            border-left: 4px solid var(--luna-accent);
        }
        
        .project-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--luna-light);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .project-info {
            color: var(--luna-gray);
            line-height: 1.6;
        }
        
        .project-info p {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .project-info strong {
            color: var(--luna-accent-light);
            min-width: 150px;
            text-align: right;
        }
        
        .action-buttons {
            margin-top: 4rem;
            text-align: center;
        }
        
        footer {
            background: rgba(12, 26, 39, 0.95);
            border-top: 1px solid rgba(52, 152, 219, 0.2);
            padding: 2rem 0;
            margin-top: auto;
            position: relative;
        }
        
        .footer-text {
            color: var(--luna-gray);
            font-size: 0.95rem;
            text-align: center;
            line-height: 1.6;
        }
        
        .floating-element {
            position: absolute;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, var(--luna-accent), transparent);
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .floating-element:nth-child(3) {
            bottom: 30%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2.5rem;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
            }
            
            .project-info p {
                flex-direction: column;
                text-align: center;
                gap: 0.2rem;
            }
            
            .project-info strong {
                text-align: center;
                min-width: auto;
            }
        }
    </style>
</head>

<body>
    <!-- Elementos flotantes de fondo -->
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>

    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{route('panel')}}">
                LUNA STUDIOS
            </a>
            <div class="navbar-nav ms-auto">
                <a href="{{route('login.index')}}" class="btn btn-primary">Iniciar Sesión</a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="hero-section">
        <div class="container">
            <div class="welcome-container">
                <br>
                <br>
                <h1 class="welcome-title">
                    LUNA STUDIOS
                </h1>
                <div class="welcome-subtitle">
                    Sistema de Gestión Audiovisual
                </div>
                
                <p class="welcome-description">
                    Plataforma especializada que transforma la gestión de producción audiovisual. 
                    Control total sobre equipos, proyectos y recursos en una interfaz moderna e intuitiva.
                </p>

                <!-- Características destacadas -->
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <div class="feature-title">Gestión Inteligente</div>
                        <div class="feature-description">Control completo de inventario y equipos audiovisuales</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🎬</div>
                        <div class="feature-title">Proyectos en Tiempo Real</div>
                        <div class="feature-description">Seguimiento detallado de cada producción</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <div class="feature-title">Optimización</div>
                        <div class="feature-description">Automatización de procesos operativos</div>
                    </div>
                </div>

                <!-- Información académica -->
                <div class="project-card">
                    <div class="project-title">Proyecto de Grado Académico</div>
                    <div class="project-info">
                        <p>
                            <strong>Proyecto:</strong> 
                            <span>Sistema de Gestión para Productora Audiovisual</span>
                        </p>
                        <p>
                            <strong>Carrera:</strong> 
                            <span>Sistemas Informáticos</span>
                        </p>
                        <p>
                            <strong>Turno:</strong> 
                            <span>Noche - Tercero "B"</span>
                        </p>
                        <p>
                            <strong>Institución:</strong> 
                            <span>Instituto Técnico Comercial INCOS El Alto</span>
                        </p>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="action-buttons">
                    <a href="{{route('login.index')}}" class="btn btn-primary me-3">Explorar Sistema</a>
                    <a href="#demo" class="btn btn-outline-light">Ver Demostración</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="footer-text mb-0">
                © 2024 LUNA STUDIOS - Sistema de Gestión Audiovisual Profesional<br>
                Proyecto Académico - Carrera de Sistemas Informáticos - INCOS El Alto
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    
    <script>
        // Efecto de navbar al hacer scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNav');
            if (window.scrollY > 100) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Animación suave para los elementos
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.feature-card, .project-card');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.6s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });
    </script>
</body>

</html>