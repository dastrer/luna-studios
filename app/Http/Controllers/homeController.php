<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Compra;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Presentacione;
use App\Models\Empleado;
use App\Models\Proveedore;
use App\Models\Caja;
use App\Models\User;

class HomeController extends Controller
{
    public function index(): View
    {
        if (!Auth::check()) {
            return view('welcome');
        }

        // DATOS EXISTENTES (los mantienes para compatibilidad)
        $totalVentasPorDia = DB::table('ventas')
            ->selectRaw('DATE(created_at) as fecha, SUM(total) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha', 'asc')
            ->get()->toArray();

        $productosStockBajo = DB::table('productos')
            ->join('inventario', 'productos.id', '=', 'inventario.producto_id')
            ->where('inventario.cantidad', '>', 0)
            ->orderBy('inventario.cantidad', 'asc')
            ->select('productos.nombre', 'inventario.cantidad')
            ->limit(5)
            ->get();

        // NUEVOS KPIs FINANCIEROS - Usando saldo_inicial de cajas abiertas
        $saldoCajas = Caja::where('estado', 1)->sum('saldo_inicial');
        
        $ventasMesActual = Venta::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
            
        $comprasMesActual = Compra::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // MÉTRICAS DE CONTEO ESPECÍFICAS PARA PRODUCTORA AUDIOVISUAL
        $totalClientes = Cliente::count();
        $totalServicios = Producto::where('codigo', 'LIKE', 'S%')->count();
        $totalEquipos = Producto::where('codigo', 'LIKE', 'E%')->count();
        $totalPaquetes = Presentacione::count();
        $totalEmpleados = Empleado::count();
        $totalProveedores = Proveedore::count();
        $totalUsuarios = User::count();

        // ALERTAS DEL SISTEMA
        $stockCriticoCount = DB::table('inventario')
            ->whereColumn('cantidad', '<=', 'cantidad_minima')
            ->count();
            
        $ventasPendientesCount = 0; // No hay campo estado en ventas según tu estructura
        $comprasPendientesCount = 0; // No hay campo estado en compras según tu estructura

        // GRÁFICO DE VENTAS MENSUALES (últimos 6 meses)
        $ventasMensuales = Venta::select(
                DB::raw('MONTH(created_at) as mes_num'),
                DB::raw('MONTHNAME(created_at) as mes'),
                DB::raw('SUM(total) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes_num', 'mes')
            ->orderBy('mes_num')
            ->get();

        // STOCK CRÍTICO (productos con inventario bajo)
        $stockCritico = DB::table('productos')
            ->join('inventario', 'productos.id', '=', 'inventario.producto_id')
            ->whereColumn('inventario.cantidad', '<=', 'inventario.cantidad_minima')
            ->orWhere('inventario.cantidad', '<=', 5) // Umbral para stock crítico
            ->select('productos.nombre', 'inventario.cantidad')
            ->orderBy('inventario.cantidad', 'asc')
            ->limit(10)
            ->get();

        // ÚLTIMAS VENTAS (entregas de servicios)
        $ultimasVentas = Venta::with('cliente')
            ->latest()
            ->limit(5)
            ->get();

        // SERVICIOS MÁS POPULARES (basado en ventas)
        $serviciosPopulares = DB::table('productos')
            ->join('producto_venta', 'productos.id', '=', 'producto_venta.producto_id')
            ->where('productos.codigo', 'LIKE', 'S%')
            ->select('productos.nombre', DB::raw('SUM(producto_venta.cantidad) as total_vendido'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(5)
            ->get();

        // EQUIPOS MÁS UTILIZADOS (basado en compras)
        $equiposPopulares = DB::table('productos')
            ->join('compra_producto', 'productos.id', '=', 'compra_producto.producto_id')
            ->where('productos.codigo', 'LIKE', 'E%')
            ->select('productos.nombre', DB::raw('SUM(compra_producto.cantidad) as total_comprado'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_comprado', 'desc')
            ->limit(5)
            ->get();

        return view('panel.index', compact(
            // Datos existentes
            'totalVentasPorDia',
            'productosStockBajo',
            
            // Nuevos KPIs financieros
            'saldoCajas',
            'ventasMesActual',
            'comprasMesActual',
            
            // Métricas de conteo específicas
            'totalClientes',
            'totalServicios',
            'totalEquipos',
            'totalPaquetes',
            'totalEmpleados',
            'totalProveedores',
            'totalUsuarios',
            
            // Alertas
            'stockCriticoCount',
            'ventasPendientesCount',
            'comprasPendientesCount',
            
            // Datos para gráficos
            'ventasMensuales',
            'stockCritico',
            'ultimasVentas',
            'serviciosPopulares',
            'equiposPopulares'
        ));
    }
}