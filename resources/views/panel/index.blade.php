@extends('layouts.app')

@section('title','Panel')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Panel - Luna Studios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Resumen</li>
    </ol>

    <!-- ALERTAS Y NOTIFICACIONES -->
    @if($stockCriticoCount > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Alertas del Sistema
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-exclamation-triangle"></i>
                                {{ $stockCriticoCount }} productos con stock crítico
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- FILA DE MÉTRICAS OPERATIVAS -->
    <div class="row">
        <!-- SERVICIOS DISPONIBLES -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <i class="fa-solid fa-photo-film"></i>
                            <span class="m-1">Servicios</span>
                        </div>
                        <div class="col-4">
                            <p class="text-center fw-bold fs-4">{{ $totalServicios }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('productos.index') }}">Ver Servicios</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- EQUIPOS EN INVENTARIO -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-dark text-white mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <i class="fa-solid fa-camera-retro"></i>
                            <span class="m-1">Equipos</span>
                        </div>
                        <div class="col-4">
                            <p class="text-center fw-bold fs-4">{{ $totalEquipos }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('equipos.index') }}">Ver Equipos</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- PAQUETES ACTIVOS -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <i class="fa-solid fa-box-archive"></i>
                            <span class="m-1">Paquetes</span>
                        </div>
                        <div class="col-4">
                            <p class="text-center fw-bold fs-4">{{ $totalPaquetes }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('presentaciones.index') }}">Ver Paquetes</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <!-- EMPLEADOS ACTIVOS -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <i class="fa-solid fa-users-gear"></i>
                            <span class="m-1">Técnicos</span>
                        </div>
                        <div class="col-4">
                            <p class="text-center fw-bold fs-4">{{ $totalEmpleados }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="{{ route('empleados.index') }}">Ver Empleados</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS PRINCIPALES -->
    <div class="row">
        <!-- GRÁFICO DE VENTAS MENSUALES -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-line me-1"></i>
                    Ingresos por Servicios - Últimos 6 Meses
                </div>
                <div class="card-body">
                    <canvas id="ventasMensualesChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- GRÁFICO DE STOCK CRÍTICO -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Productos con Stock Crítico
                </div>
                <div class="card-body">
                    <canvas id="stockCriticoChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS EXISTENTES -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    5 Productos con el stock más bajo
                </div>
                <div class="card-body"><canvas id="productosChart" width="100%" height="30"></canvas></div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Ventas en los últimos 7 días
                </div>
                <div class="card-body"><canvas id="ventasChart" width="100%" height="30"></canvas></div>
            </div>
        </div>
    </div>

    <!-- TABLAS INFORMATIVAS -->
    <div class="row">
        <!-- ÚLTIMAS ENTREGAS -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-truck me-1"></i>
                    Últimas Entregas de Servicios
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasVentas as $venta)
                                <tr>
                                    <td>{{ $venta->cliente->persona->razon_social ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y') }}</td>
                                    <td>Bs {{ number_format($venta->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- SERVICIOS MÁS SOLICITADOS -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-star me-1"></i>
                    Servicios Más Populares
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Total Vendido</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviciosPopulares as $servicio)
                                <tr>
                                    <td>{{ $servicio->nombre }}</td>
                                    <td>{{ $servicio->total_vendido }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>

<script>
    // Gráfico de Ventas Mensuales
    const ventasMensualesChart = document.getElementById('ventasMensualesChart');
    new Chart(ventasMensualesChart, {
        type: 'bar',
        data: {
            labels: @json($ventasMensuales->pluck('mes')),
            datasets: [{
                label: 'Ingresos (Bs)',
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                data: @json($ventasMensuales->pluck('total')),
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return 'Bs ' + value.toLocaleString();
                        }
                    }
                }]
            }
        }
    });

    // Gráfico de Stock Crítico
    const stockCriticoChart = document.getElementById('stockCriticoChart');
    new Chart(stockCriticoChart, {
        type: 'horizontalBar',
        data: {
            labels: @json($stockCritico->pluck('nombre')),
            datasets: [{
                label: 'Stock Actual',
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                borderColor: 'rgba(255, 99, 132, 1)',
                data: @json($stockCritico->pluck('cantidad')),
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Gráficos existentes (los mantienes)
    let datosVenta = @json($totalVentasPorDia);
    const fechas = datosVenta.map(venta => {
        const [year, month, day] = venta.fecha.split('-');
        return `${day}/${month}/${year}`;
    });
    const montos = datosVenta.map(venta => parseFloat(venta.total));

    const ventasChart = document.getElementById('ventasChart');
    new Chart(ventasChart, {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [{
                label: "Ventas",
                lineTension: 0.3,
                backgroundColor: "rgba(2,117,216,0.2)",
                borderColor: "rgba(2,117,216,1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(2,117,216,1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(2,117,216,1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: montos,
            }],
        },
        options: {
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false
                    },
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        callback: function(value) {
                            return 'Bs ' + value.toLocaleString();
                        }
                    },
                    gridLines: {
                        color: "rgba(0, 0, 0, .125)",
                    }
                }],
            },
            legend: {
                display: false
            }
        }
    });

    let datosProductos = @json($productosStockBajo);
    const nombres = datosProductos.map(obj => obj.nombre);
    const stock = datosProductos.map(i => i.cantidad);

    const productosChart = document.getElementById('productosChart');
    new Chart(productosChart, {
        type: 'horizontalBar',
        data: {
            labels: nombres,
            datasets: [{
                label: 'stock',
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "#fff",
                data: stock,
                borderWidth: 2,
                hoverBorderColor: '#aaa',
                base: 0
            }]
        },
        options: {
            legend: {
                display: false
            },
        }
    });
</script>
@endpush