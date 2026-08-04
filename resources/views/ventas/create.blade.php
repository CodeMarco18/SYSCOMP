@extends('layouts.app')

@section('title', 'Nueva Venta - SysComp')
@section('page_title', 'Punto de Venta (POS) - Tienda de Cómputo')

@section('content')
<div class="row g-4">
    <!-- Formulario / Carrito de Venta -->
    <div class="col-lg-12">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-cash-register text-primary me-2"></i> Emitir Comprobante de Venta</h5>

            <form action="{{ route('ventas.store') }}" method="POST" id="formVenta">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label for="id_cliente" class="form-label fw-semibold">Cliente (*)</label>
                        <select name="id_cliente" id="id_cliente" class="form-select @error('id_cliente') is-invalid @enderror" required>
                            <option value="">-- Seleccionar Cliente --</option>
                            @foreach($clientes as $cli)
                                <option value="{{ $cli->id_cliente }}" {{ old('id_cliente') == $cli->id_cliente ? 'selected' : '' }}>
                                    {{ $cli->nombre_completo }} (Doc: {{ $cli->documento }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_cliente') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="tipo_comprobante" class="form-label fw-semibold">Tipo Comprobante (*)</label>
                        <select name="tipo_comprobante" id="tipo_comprobante" class="form-select" required>
                            <option value="BOLETA" {{ old('tipo_comprobante') == 'BOLETA' ? 'selected' : '' }}>BOLETA</option>
                            <option value="FACTURA" {{ old('tipo_comprobante') == 'FACTURA' ? 'selected' : '' }}>FACTURA</option>
                            <option value="TICKET" {{ old('tipo_comprobante') == 'TICKET' ? 'selected' : '' }}>TICKET</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="serie" class="form-label fw-semibold">Serie (*)</label>
                        <input type="text" name="serie" id="serie" class="form-control" value="{{ old('serie', 'F001') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="numero" class="form-label fw-semibold">Número Correlativo (*)</label>
                        <input type="text" name="numero" id="numero" class="form-control" value="{{ old('numero', $siguienteNumero) }}" required>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Selección de Producto -->
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-cart-plus text-success me-2"></i> Seleccionar Productos para la Venta</h6>
                <div class="row g-3 align-items-end mb-4 bg-light p-3 rounded-3 border">
                    <div class="col-md-6">
                        <label for="selectProductoVenta" class="form-label fw-semibold">Producto disponible</label>
                        <select id="selectProductoVenta" class="form-select">
                            <option value="">-- Buscar Producto por Código o Nombre --</option>
                            @foreach($productos as $prod)
                                <option value="{{ $prod->id_producto }}" 
                                        data-codigo="{{ $prod->codigo_producto }}" 
                                        data-nombre="{{ $prod->nombre_producto }}" 
                                        data-precio="{{ $prod->precio_venta }}" 
                                        data-stock="{{ $prod->stock_actual }}">
                                    [{{ $prod->codigo_producto }}] {{ $prod->nombre_producto }} - S/ {{ number_format($prod->precio_venta, 2) }} (Stock: {{ $prod->stock_actual }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="inputCantidadVenta" class="form-label fw-semibold">Cantidad</label>
                        <input type="number" id="inputCantidadVenta" class="form-control" min="1" value="1">
                    </div>

                    <div class="col-md-2">
                        <label for="inputPrecioVenta" class="form-label fw-semibold">Precio Venta (S/)</label>
                        <input type="number" step="0.01" min="0.01" id="inputPrecioVenta" class="form-control" readonly>
                    </div>

                    <div class="col-md-2">
                        <button type="button" id="btnAgregarVenta" class="btn btn-success w-100">
                            <i class="fa-solid fa-plus me-1"></i> Agregar
                        </button>
                    </div>
                </div>

                <!-- Tabla de Detalle Venta -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle" id="tablaVenta">
                        <thead class="table-dark">
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th style="width: 120px;">Cantidad</th>
                                <th style="width: 160px;">Precio Unit. (S/)</th>
                                <th style="width: 160px;">Subtotal (S/)</th>
                                <th style="width: 80px;" class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyVenta">
                            <tr id="rowVaciaVenta">
                                <td colspan="6" class="text-center text-muted py-4">El carrito de venta está vacío. Seleccione productos arriba.</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end fs-4">TOTAL A PAGAR:</th>
                                <th class="fs-4 text-success fw-bold" id="lblTotalVenta">S/ 0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-lg px-4" id="btnConfirmarVenta" disabled>
                        <i class="fa-solid fa-circle-check me-1"></i> Confirmar y Procesar Venta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const selectProducto = document.getElementById("selectProductoVenta");
        const inputCantidad = document.getElementById("inputCantidadVenta");
        const inputPrecio = document.getElementById("inputPrecioVenta");
        const btnAgregar = document.getElementById("btnAgregarVenta");
        const tbodyVenta = document.getElementById("tbodyVenta");
        const rowVacia = document.getElementById("rowVaciaVenta");
        const lblTotal = document.getElementById("lblTotalVenta");
        const btnConfirmar = document.getElementById("btnConfirmarVenta");

        let itemsVenta = [];

        selectProducto.addEventListener("change", function () {
            const opt = selectProducto.options[selectProducto.selectedIndex];
            if (opt.value) {
                inputPrecio.value = parseFloat(opt.dataset.precio || 0).toFixed(2);
            } else {
                inputPrecio.value = "";
            }
        });

        btnAgregar.addEventListener("click", function () {
            const prodId = selectProducto.value;
            const opt = selectProducto.options[selectProducto.selectedIndex];

            if (!prodId) {
                alert("Por favor seleccione un producto.");
                return;
            }

            const cant = parseInt(inputCantidad.value);
            const precio = parseFloat(inputPrecio.value);
            const stockDisponible = parseInt(opt.dataset.stock || 0);

            if (isNaN(cant) || cant <= 0) {
                alert("Ingrese una cantidad válida mayor a 0.");
                return;
            }

            if (cant > stockDisponible) {
                alert(`Stock insuficiente. El producto cuenta con ${stockDisponible} unidades disponibles.`);
                return;
            }

            // Verificar si ya fue agregado
            const indexExistente = itemsVenta.findIndex(item => item.id_producto == prodId);
            if (indexExistente !== -1) {
                const nuevaCant = itemsVenta[indexExistente].cantidad + cant;
                if (nuevaCant > stockDisponible) {
                    alert(`Stock insuficiente. Máximo disponible: ${stockDisponible}`);
                    return;
                }
                itemsVenta[indexExistente].cantidad = nuevaCant;
            } else {
                itemsVenta.push({
                    id_producto: prodId,
                    codigo: opt.dataset.codigo,
                    nombre: opt.dataset.nombre,
                    cantidad: cant,
                    precio_unitario: precio,
                    stock: stockDisponible
                });
            }

            renderTablaVenta();
        });

        function renderTablaVenta() {
            if (itemsVenta.length === 0) {
                rowVacia.style.display = "";
                btnConfirmar.disabled = true;
                lblTotal.innerText = "S/ 0.00";
                tbodyVenta.innerHTML = "";
                tbodyVenta.appendChild(rowVacia);
                return;
            }

            rowVacia.style.display = "none";
            btnConfirmar.disabled = false;
            tbodyVenta.innerHTML = "";

            let total = 0;

            itemsVenta.forEach((item, index) => {
                const subtotal = item.cantidad * item.precio_unitario;
                total += subtotal;

                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td><code>${item.codigo}</code></td>
                    <td class="fw-semibold">${item.nombre}</td>
                    <td>
                        <input type="hidden" name="productos[${index}][id_producto]" value="${item.id_producto}">
                        <input type="number" min="1" max="${item.stock}" class="form-control form-control-sm text-center" name="productos[${index}][cantidad]" value="${item.cantidad}" onchange="actualizarCantVenta(${index}, this.value)">
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm text-end" name="productos[${index}][precio_unitario]" value="${item.precio_unitario.toFixed(2)}" readonly>
                    </td>
                    <td class="fw-bold text-end text-success">S/ ${subtotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarItemVenta(${index})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                `;
                tbodyVenta.appendChild(tr);
            });

            lblTotal.innerText = "S/ " + total.toFixed(2);
        }

        window.actualizarCantVenta = function(idx, val) {
            const cant = parseInt(val);
            if (!isNaN(cant) && cant > 0) {
                if (cant > itemsVenta[idx].stock) {
                    alert(`Stock máximo disponible: ${itemsVenta[idx].stock}`);
                    renderTablaVenta();
                    return;
                }
                itemsVenta[idx].cantidad = cant;
                renderTablaVenta();
            }
        };

        window.eliminarItemVenta = function(idx) {
            itemsVenta.splice(idx, 1);
            renderTablaVenta();
        };
    });
</script>
@endpush
