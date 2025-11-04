<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <!-- ENCABEZADO PRINCIPAL -->
                <x-nav.heading>Inicio</x-nav.heading>
                <x-nav.nav-link content='Panel'
                    icon='fas fa-tachometer-alt'
                    :href="route('panel')" />

                <!-- EQUIPOS E INSUMOS -->
                <x-nav.heading>Adquisiciones</x-nav.heading>
                
                @can('ver-presentacione')
                <x-nav.nav-link content='Paquetes'
                    icon='fa-solid fa-box-archive'
                    :href="route('presentaciones.index')" />
                @endcan

                <!-- COLLAPSIBLE CARACTERÍSTICAS -->
                @canany(['ver-categoria', 'ver-marca'])
                <x-nav.link-collapsed
                    id="collapseCaracteristicas"
                    icon="fa-solid fa-list"
                    content="Características">
                    @can('ver-categoria')
                    <x-nav.link-collapsed-item :href="route('categorias.index')" content="Categorías" />
                    @endcan
                    @can('ver-marca')
                    <x-nav.link-collapsed-item :href="route('marcas.index')" content="Marcas" />
                    @endcan
                </x-nav.link-collapsed>
                @endcanany

                @can('ver-proveedore')
                <x-nav.nav-link content='Proveedores'
                    icon='fa-solid fa-user-group'
                    :href="route('proveedores.index')" />
                @endcan

                @can('ver-compra')
                <x-nav.link-collapsed
                    id="collapseCompras"
                    icon="fa-solid fa-store"
                    content="Compras">
                    @can('ver-compra')
                    <x-nav.link-collapsed-item :href="route('compras.index')" content="Ver" />
                    @endcan
                    @can('crear-compra')
                    <x-nav.link-collapsed-item :href="route('compras.create')" content="Registrar" />
                    @endcan
                </x-nav.link-collapsed>
                @endcan

                <!-- ENTREGAS Y PROYECTOS -->
                <x-nav.heading>Entregas y Proyectos</x-nav.heading>
                
                @can('ver-caja')
                <x-nav.nav-link content='Cajas'
                    icon='fa-solid fa-money-bill'
                    :href="route('cajas.index')" />
                @endcan

                @can('ver-venta')
                <x-nav.link-collapsed
                    id="collapseVentas"
                    icon="fa-solid fa-cart-shopping"
                    content="Entregas">
                    @can('ver-venta')
                    <x-nav.link-collapsed-item :href="route('ventas.index')" content="Ver" />
                    @endcan
                    @can('crear-venta')
                    <x-nav.link-collapsed-item :href="route('ventas.create')" content="Registrar" />
                    @endcan
                </x-nav.link-collapsed>
                @endcan

                <!-- GESTIÓN DE SERVICIOS -->
                <x-nav.heading>Gestión de Servicios</x-nav.heading>
                
                @can('ver-producto')
                <x-nav.nav-link content='Servicios'
                    icon='fa-brands fa-shopify'
                    :href="route('productos.index')" />
                @endcan

                @can('ver-cliente')
                <x-nav.nav-link content='Clientes'
                    icon='fa-solid fa-users'
                    :href="route('clientes.index')" />
                @endcan

                <!-- GESTIÓN DE EQUIPOS -->
                <x-nav.heading>Gestión de Equipos</x-nav.heading>
                
                @can('ver-kardex')
                <x-nav.nav-link content='Control de Equipos'
                    icon='fa-solid fa-file'
                    :href="route('kardex.index')" />
                @endcan

                @can('ver-inventario')
                <x-nav.nav-link content='Existencias'
                    icon='fa-solid fa-book'
                    :href="route('inventario.index')" />
                @endcan

                <!-- GESTIÓN TÉCNICA -->
                <x-nav.heading>Gestión Técnica</x-nav.heading>
                
                @can('ver-empleado')
                <x-nav.nav-link content='Empleados'
                    icon='fa-solid fa-users'
                    :href="route('empleados.index')" />
                @endcan

                <!-- ADMINISTRACIÓN EMPRESARIAL -->
                <x-nav.heading>Administración</x-nav.heading>
                
                @can('ver-empresa')
                <x-nav.nav-link content='Empresa'
                    icon='fa-solid fa-city'
                    :href="route('empresa.index')" />
                @endcan

                @can('ver-user')
                <x-nav.nav-link content='Usuarios'
                    icon='fa-solid fa-user'
                    :href="route('users.index')" />
                @endcan

                @can('ver-role')
                <x-nav.nav-link content='Roles'
                    icon='fa-solid fa-person-circle-plus'
                    :href="route('roles.index')" />
                @endcan

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Bienvenido:</div>
            {{ auth()->user()->name }}
        </div>
    </nav>
</div>