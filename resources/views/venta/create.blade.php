@extends('layouts.app')

@section('title','Realizar entrega')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Realizar Entrega</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ventas.index')}}">Entregas</a></li>
        <li class="breadcrumb-item active">Realizar Entrega</li>
    </ol>
</div>

<form action="{{ route('ventas.store') }}" method="post">
    @csrf
    <div class="container-lg mt-4">
        <div class="row gy-4">

            <!-----Venta---->
            <div class="col-12">
                <div class="text-white bg-success p-1 text-center">
                    Datos generales
                </div>
                <div class="p-3 border border-3 border-success">
                    <div class="row g-4">

                        <!--Cliente-->
                        <div class="col-12">
                            <label for="cliente_id" class="form-label">
                                Cliente:</label>
                            <select name="cliente_id" id="cliente_id"
                                class="form-control selectpicker show-tick"
                                data-live-search="true" title="Selecciona"
                                data-size='2'>
                                @foreach ($clientes as $item)
                                <option value="{{$item->id}}">{{$item->nombre_documento}}</option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <!--Tipo de comprobante-->
                        <div class="col-md-6">
                            <label for="comprobante_id" class="form-label">
                                Comprobante:</label>
                            <select name="comprobante_id" id="comprobante_id"
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

                        <!--Método de pago-->
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
                    </div>
                </div>
            </div>

            <!------venta producto---->
            <div class="col-12">
                <div class="text-white bg-primary p-1 text-center">
                    Detalles de la Entrega
                </div>
                <div class="p-3 border border-3 border-primary">
                    <div class="row gy-4">

                        <!-----Producto---->
                        <div class="col-12">
                            <select id="producto_id"
                                class="form-control selectpicker"
                                data-live-search="true" data-size="1"
                                title="Busque un producto aquí">
                                @foreach ($productos as $item)
                                <option value="{{$item->id}}-{{$item->cantidad}}-{{$item->precio}}-{{$item->nombre}}-{{$item->sigla}}">
                                    {{'Código: '. $item->codigo.' - '. $item->nombre.' - '.$item->sigla}}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-----Stock--->
                        <div class="d-flex justify-content-end">
                            <div class="col-12 col-sm-6">
                                <div class="row">
                                    <label for="stock" class="col-form-label col-4">
                                        En stock:</label>
                                    <div class="col-8">
                                        <input disabled id="stock"
                                            type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-----Precio -->
                        <div class="d-flex justify-content-end">
                            <div class="col-12 col-sm-6">
                                <div class="row">
                                    <label for="precio" class="col-form-label col-4">
                                        Precio:</label>
                                    <div class="col-8">
                                        <input disabled id="precio"
                                            type="number" class="form-control"
                                            step="any">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-----Cantidad---->
                        <div class="col-md-6">
                            <label for="cantidad" class="form-label">
                                Cantidad:</label>
                            <input type="number" id="cantidad"
                                class="form-control">
                        </div>

                        <!-----botón para agregar--->
                        <div class="col-12 text-end">
                            <button id="btn_agregar" class="btn btn-primary" type="button">
                                Agregar</button>
                        </div>

                        <!-----Tabla para el detalle de la venta--->
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

                        <!--Boton para cancelar venta--->
                        <div class="col-12">
                            <button id="cancelar" type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Cancelar entrega
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!----Finalizar venta-->
            <div class="col-12">
                <div class="text-white bg-primary p-1 text-center">
                    Finalizar entrega
                </div>

                <div class="p-3 border border-3 border-primary">

                    <div class="row gy-4">

                        <div class="col-md-6">
                            <label for="dinero_recibido" class="form-label">
                                Ingrese dinero recibido:</label>
                            <input type="number" id="dinero_recibido"
                                name="monto_recibido" class="form-control"
                                step="any">
                        </div>

                        <div class="col-md-6">
                            <label for="vuelto" class="form-label">
                                Vuelto:</label>
                            <input readonly type="number" name="vuelto_entregado"
                                id="vuelto" class="form-control" step="any">
                        </div>

                        <!--Botones--->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success" id="guardar">
                                Realizar entrega</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Modal para cancelar la venta -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Advertencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Seguro que quieres cancelar la entrega?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btnCancelarVenta" type="button" class="btn btn-danger" data-bs-dismiss="modal">Confirmar</button>
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

        $('#producto_id').change(mostrarValores);

        $('#btn_agregar').click(function() {
            agregarProducto();
        });

        $('#btnCancelarVenta').click(function() {
            cancelarVenta();
        });

        disableButtons();

        $('#dinero_recibido').on('input', function() {
            let dineroRecibido = parseFloat($(this).val());

            if (!isNaN(dineroRecibido) && dineroRecibido >= total && total > 0) {
                let vuelto = dineroRecibido - total;
                $('#vuelto').val(vuelto.toFixed(2));
            } else {
                $('#vuelto').val(''); 
            }
        });

    });

    //Variables
    let cont = 0;
    let subtotal = [];
    let total = 0;
    let arrayIdProductos = [];

    //Constantes (se mantiene para cálculos internos)
    const impuesto = @json($empresa->porcentaje_impuesto);

    function mostrarValores() {
        let dataProducto = document.getElementById('producto_id').value.split('-');
        $('#stock').val(dataProducto[1]);
        $('#precio').val(dataProducto[2]);
    }

    function agregarProducto() {
        let dataProducto = document.getElementById('producto_id').value.split('-');
        //Obtener valores de los campos
        let idProducto = dataProducto[0];
        let nameProducto = dataProducto[3];
        let presentacioneProducto = dataProducto[4];
        let cantidad = $('#cantidad').val();
        let precioVenta = $('#precio').val();
        let stock = $('#stock').val();

        //Validaciones 
        //1.Para que los campos no esten vacíos
        if (idProducto != '' && cantidad != '') {

            //2. Para que los valores ingresados sean los correctos
            if (parseInt(cantidad) > 0 && (cantidad % 1 == 0)) {

                //3. Para que la cantidad no supere el stock
                if (parseInt(cantidad) <= parseInt(stock)) {

                    //4.No permitir el ingreso del mismo producto 
                    if (!arrayIdProductos.includes(idProducto)) {

                        //Calcular valores (sin mostrar IVA visualmente)
                        let subtotalProducto = round(cantidad * precioVenta);
                        subtotal[cont] = subtotalProducto;
                        
                        // Cálculos internos (se mantienen para el backend)
                        let sumasInternas = 0;
                        for (let i = 0; i <= cont; i++) {
                            sumasInternas += subtotal[i] || 0;
                        }
                        let igvInterno = round(sumasInternas / 100 * impuesto);
                        total = round(sumasInternas + igvInterno);

                        //Crear la fila
                        let fila = '<tr id="fila' + cont + '">' +
                            '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                            '<td>' + presentacioneProducto + '</td>' +
                            '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                            '<td><input type="hidden" name="arrayprecioventa[]" value="' + precioVenta + '">' + precioVenta + '</td>' +
                            '<td>' + subtotalProducto + '</td>' +
                            '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + cont + ',' + idProducto + ')"><i class="fa-solid fa-trash"></i></button></td>' +
                            '</tr>';

                        //Acciones después de añadir la fila
                        $('#tabla_detalle tbody').append(fila);
                        limpiarCampos();
                        cont++;
                        disableButtons();

                        //Mostrar solo el total (sin IVA visible)
                        $('#total').html(total);
                        $('#inputImpuesto').val(igvInterno); // Mantener para backend
                        $('#inputTotal').val(total);         // Mantener para backend
                        $('#inputSubtotal').val(sumasInternas); // Mantener para backend

                        //Agregar el id del producto al arreglo
                        arrayIdProductos.push(idProducto);
                    } else {
                        showModal('Ya ha ingresado el producto');
                    }

                } else {
                    showModal('Cantidad incorrecta');
                }

            } else {
                showModal('Valores incorrectos');
            }

        } else {
            showModal('Le faltan campos por llenar');
        }

    }

    function eliminarProducto(indice, idProducto) {
        //Calcular valores (sin mostrar IVA visualmente)
        let subtotalEliminar = subtotal[indice] || 0;
        
        // Recalcular totales internos
        let nuevaSuma = 0;
        for (let i = 0; i < subtotal.length; i++) {
            if (i !== indice && subtotal[i] !== undefined) {
                nuevaSuma += subtotal[i];
            }
        }
        
        let nuevoIgv = round(nuevaSuma / 100 * impuesto);
        total = round(nuevaSuma + nuevoIgv);

        //Mostrar solo el total
        $('#total').html(total);
        $('#inputImpuesto').val(nuevoIgv);     // Mantener para backend
        $('#inputTotal').val(total);           // Mantener para backend
        $('#inputSubtotal').val(nuevaSuma);    // Mantener para backend

        //Eliminar el fila de la tabla
        $('#fila' + indice).remove();

        //Eliminar id del arreglo
        let index = arrayIdProductos.indexOf(idProducto.toString());
        arrayIdProductos.splice(index, 1);

        // Marcar como eliminado en el array de subtotales
        delete subtotal[indice];

        disableButtons();
    }

    function cancelarVenta() {
        //Elimar el tbody de la tabla
        $('#tabla_detalle tbody').empty();

        //Reiniciar valores de las variables
        cont = 0;
        subtotal = [];
        total = 0;
        arrayIdProductos = [];

        //Mostrar solo el total
        $('#total').html('0');
        $('#inputImpuesto').val('0');     // Mantener para backend
        $('#inputTotal').val('0');        // Mantener para backend
        $('#inputSubtotal').val('0');     // Mantener para backend

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

    function limpiarCampos() {
        let select = $('#producto_id');
        select.selectpicker('val', '');
        $('#cantidad').val('');
        $('#precio').val('');
        $('#stock').val('');
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

    function round(num, decimales = 2) {
        var signo = (num >= 0 ? 1 : -1);
        num = num * signo;
        if (decimales === 0) //con 0 decimales
            return signo * Math.round(num);
        // round(x * 10 ^ decimales)
        num = num.toString().split('e');
        num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
        // x * 10 ^ (-decimales)
        num = num.toString().split('e');
        return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
    }
</script>
@endpush