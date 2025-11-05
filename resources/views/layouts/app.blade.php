<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Luna Studios" />
    <meta name="author" content="SakCode" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Luna Studios - @yield('title')</title>
    
    @stack('css-datatable')
    
    <!-- Bootstrap CSS -->
    <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous"-->
    
    <!-- Estilos principales -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    
    <!-- Estilos personalizados para el menú -->
    <link href="{{ asset('css/adminlte-custom.css') }}" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @stack('css')
</head>

<body class="sb-nav-fixed">

    @include('layouts.include.navigation-header')

    <div id="layoutSidenav">

        @include('layouts.include.navigation-menu')

        <div id="layoutSidenav_content">

            @include('layouts.partials.alert')

            <main>
                @yield('content')
            </main>

            @include('layouts.include.footer')

        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    
    <!-- Scripts principales -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    
    <!-- Script para notificaciones -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationIcon = document.getElementById('notificationsDropdown');
            
            if (notificationIcon) {
                notificationIcon.addEventListener('click', function() {
                    fetch("{{ route('notifications.markAsRead') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const badge = notificationIcon.querySelector('.badge');
                                if (badge) badge.remove();
                            }
                        })
                        .catch(error => console.error('Error al marcar notificaciones como leídas:', error));
                });
            }
        });
    </script>
    
    @stack('js')

</body>
</html>