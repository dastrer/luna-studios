@extends('layouts.app')

@section('title','Realizar compra')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Compra</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('compras.index')}}">Compras</a></li>
        <li class="breadcrumb-item active">Crear Compra</li>
    </ol>
</div>

<form action="{{ route('compras.store') }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="container-lg mt-4">
        <div class="row gy-4">

            <div class="col-12">
                <div class="text-white bg-success p-1 text-center">
                    Datos generales
                </div>
                <div class="p-3 border border-3 border-success">
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="proveedore_id" class="form-label">
                                Proveedor:</label>
                            <select name="proveedore_id"
                                id="proveedore_id" required
                                class="form-control selectpicker show-tick"
                                data-live-search="true"
                                title="Selecciona" data-size='2'>
                                @foreach ($proveedores as $item)
                                <option value="{{$item->id}}">{{$item->nombre_documento}}</option>
                                @endforeach
                            </select>
                            @error('proveedore_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="comprobante_id" class="form-label">
                                Comprobante:</label>
                            <select name="comprobante_id"
                                id="comprobante_id" required
                                class="form-control selectpicker"
                                title="Selecciona">
                                @foreach ($comprobantes as $item)
                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                @endforeach
                            </select>
                            @error('comprobante_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="numero_comprobante" class="form-label">
                                Numero de comprobante:</label>
                            <input type="text"
                                name="numero_comprobante"
                                id="numero_comprobante"
                                class="form-control">
                            @error('numero_comprobante')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="file_comprobante" class="form-label">
                                Archivo:</label>
                            <input type="file"
                                name="file_comprobante"
                                id="file_comprobante"
                                class="form-control"
                                accept=".pdf">
                            @error('file_comprobante')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="metodo_pago" class="form-label">
                                Método de pago:</label>
                            <select required name="metodo_pago"
                                id="metodo_pago"
                                class="form-control selectpicker"
                                title="Selecciona">
                                @foreach ($optionsMetodoPago as $item)
                                <option value="{{$item->value}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('metodo_pago')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="fecha_hora" class="form-label">
                                Fecha y hora:</label>
                            <input
                                required
                                type="datetime-local"
                                name="fecha_hora"
                                id="fecha_hora"
                                class="form-control"
                                value="">
                            @error('fecha_hora')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="text-white bg-primary p-1 text-center">
                    Detalles de la compra
                </div>
                <div class="p-3 border border-3 border-primary">
                    <div class="row g-4">
                        <div class="col-12">
                            <select id="producto_id"
                                class="form-control selectpicker"
                                data-live-search="true"
                                data-size="10"
                                data-live-search-placeholder="Buscar producto..."
                                title="Busque un insumo aquí">
                                @foreach ($productos as $item)
                                <option value="{{$item->id}}" 
                                    data-codigo="{{ $item->codigo }}"
                                    data-nombre="{{ $item->nombre }}"
                                    data-presentacion="{{ $item->presentacion }}">
                                    {{ $item->codigo }} - {{ $item->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="cantidad" class="form-label">
                                Cantidad:</label>
                            <input type="number" id="cantidad" class="form-control">
                        </div>

                        <div class="col-sm-6">
                            <label for="precio_compra" class="form-label">
                                Precio de compra:</label>
                            <input type="number" id="precio_compra" class="form-control" step="0.1">
                        </div>

                        <div class="col-12 my-4 text-end">
                            <button id="btn_agregar" class="btn btn-primary" type="button">
                                Agregar</button>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabla_detalle" class="table table-hover">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-white">Producto</th>
                                            <th class="text-white">Presentación</th>
                                            <th class="text-white">Cantidad</th>
                                            <th class="text-white">Precio</th>
                                            <th class="text-white">Subtotal</th>
                                            <th class="text-white">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Los productos se agregarán aquí dinámicamente -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">Total</th>
                                            <th colspan="2">
                                                <input type="hidden" name="subtotal" value="0" id="inputSubtotal">
                                                <input type="hidden" name="impuesto" value="0" id="inputImpuesto">
                                                <input type="hidden" name="total" value="0" id="inputTotal">
                                                <span id="total">0</span>
                                                <span>{{$empresa->moneda->simbolo}}</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <button id="cancelar"
                                type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Cancelar compra
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 mt-4 text-center">
                <button type="submit" class="btn btn-success" id="guardar">
                    Realizar compra</button>
            </div>

        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Advertencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Seguro que quieres cancelar la compra?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cerrar</button>
                    <button id="btnCancelarCompra" type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        Confirmar</button>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar todos los selectpickers
        $('.selectpicker').selectpicker();
        
        $('#producto_id').on('shown.bs.select', function () {
            var $searchbox = $(this).next().find('.bs-searchbox input');
            $searchbox.val('E');
            $searchbox.trigger('input');
        });

        $('#btn_agregar').click(function() {
            agregarProducto();
        });

        $('#btnCancelarCompra').click(function() {
            cancelarCompra();
        });

        disableButtons();
    });

    //Variables
    let cont = 0;
    let subtotal = [];
    let total = 0;
    let arrayIdProductos = [];

    function cancelarCompra() {
        // Limpiar completamente el tbody
        $('#tabla_detalle tbody').empty();
        
        // Reiniciar valores
        cont = 0;
        subtotal = [];
        total = 0;
        arrayIdProductos = [];

        // Actualizar display
        $('#total').html('0');
        $('#inputSubtotal').val('0');
        $('#inputImpuesto').val('0');
        $('#inputTotal').val('0');

        limpiarCampos();
        disableButtons();
    }

    function disableButtons() {
        if (total == 0) {
            $('#guardar').hide();
            $('#cancelar').hide();
        } else {
            $('#guardar').show();
            $('#cancelar').show();
        }
    }

    function agregarProducto() {
        // Obtener valores
        let idProducto = $('#producto_id').val();
        let selectedOption = $('#producto_id option:selected');
        let nombreProducto = selectedOption.data('nombre');
        let presentacionProducto = selectedOption.data('presentacion');
        let cantidad = $('#cantidad').val();
        let precioCompra = $('#precio_compra').val();

        console.log('Intentando agregar:', {idProducto, nombreProducto, cantidad, precioCompra});

        // Validaciones
        if (!idProducto || !cantidad || !precioCompra) {
            showModal('Todos los campos son obligatorios');
            return;
        }

        if (parseInt(cantidad) <= 0 || !Number.isInteger(parseFloat(cantidad))) {
            showModal('La cantidad debe ser un número entero positivo');
            return;
        }

        if (parseFloat(precioCompra) <= 0) {
            showModal('El precio debe ser mayor a 0');
            return;
        }

        // Verificar producto duplicado
        if (arrayIdProductos.includes(idProducto.toString())) {
            showModal('Este producto ya fue agregado');
            return;
        }

        // Calcular subtotal del producto
        let subtotalProducto = parseFloat(cantidad) * parseFloat(precioCompra);
        subtotal[cont] = subtotalProducto;
        total += subtotalProducto;

        console.log('Cálculos:', {subtotalProducto, total});

        // Crear fila
        let fila = '<tr id="fila' + cont + '">' +
            '<td>' +
                '<input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' +
                nombreProducto +
            '</td>' +
            '<td>' + (presentacionProducto || 'N/A') + '</td>' +
            '<td>' +
                '<input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' +
                cantidad +
            '</td>' +
            '<td>' +
                '<input type="hidden" name="arraypreciocompra[]" value="' + parseFloat(precioCompra).toFixed(2) + '">' +
                parseFloat(precioCompra).toFixed(2) +
            '</td>' +
            '<td>' + subtotalProducto.toFixed(2) + '</td>' +
            '<td>' +
                '<button class="btn btn-danger btn-sm" type="button" onclick="eliminarProducto(' + cont + ')">' +
                    '<i class="fa-solid fa-trash"></i>' +
                '</button>' +
            '</td>' +
            '</tr>';

        // Agregar al tbody
        $('#tabla_detalle tbody').append(fila);
        
        // Actualizar totales
        $('#total').html(total.toFixed(2));
        $('#inputSubtotal').val(total.toFixed(2));
        $('#inputTotal').val(total.toFixed(2));

        // Agregar a arrays de control
        arrayIdProductos.push(idProducto.toString());
        cont++;

        limpiarCampos();
        disableButtons();

        console.log('Producto agregado correctamente. Total:', total);
    }

    function eliminarProducto(indice) {
        console.log('Eliminando producto índice:', indice);
        
        // Obtener subtotal del producto a eliminar
        let subtotalEliminar = subtotal[indice] || 0;
        
        // Actualizar total
        total -= subtotalEliminar;
        if (total < 0) total = 0;

        // Eliminar fila visual
        $('#fila' + indice).remove();

        // Eliminar de arrays (marcar como eliminado en lugar de splice para no desincronizar índices)
        subtotal[indice] = 0;
        
        // Actualizar display
        $('#total').html(total.toFixed(2));
        $('#inputSubtotal').val(total.toFixed(2));
        $('#inputTotal').val(total.toFixed(2));

        disableButtons();
        
        console.log('Producto eliminado. Nuevo total:', total);
    }

    function limpiarCampos() {
        $('#producto_id').val('').selectpicker('refresh');
        $('#cantidad').val('');
        $('#precio_compra').val('');
    }

    function showModal(message, icon = 'error') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: icon,
            title: message
        })
    }
</script>
@endpush