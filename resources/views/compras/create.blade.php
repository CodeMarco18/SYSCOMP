@extends('layouts.app')

@section('title', 'Nueva Compra - SysComp')
@section('page_title', 'Registrar Compra de Mercadería')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-cart-flatbed text-primary me-2"></i> Ingreso de Mercadería por Compra</h5>

            <form action="{{ route('compras.store') }}" method="POST" id="formCompra">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="id_proveedor" class="form-label fw-semibold">Proveedor (*)</label>
                        <select name="id_proveedor" id="id_proveedor" class="form-select @error('id_proveedor') is-invalid @enderror" required>
                            <option value="">-- Seleccionar Proveedor --</option>
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->id_proveedor }}" {{ old('id_proveedor') == $prov->id_proveedor ? 'selected' : '' }}>
                                    {{ $prov->razon_social }} (RUC: {{ $prov->ruc }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_proveedor') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha de Registro</label>
                        <input type="text" class="form-control bg-light" value="{{ date('d/m/Y H:i') }}" readonly>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Selección de Producto para agregar -->
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-box text-secondary me-2"></i> Agregar Productos a la Compra</h6>
                <div class="row g-3 align-items-end mb-4 bg-light p-3 rounded-3 border">
                    <div class="col-md-5">
                        <label for="selectProducto" class="form-label fw-semibold">Producto</label>
                        <select id="selectProducto" class="form-select">
                            <option value="">-- Seleccionar Producto --</option>
                            @foreach($productos as $prod)
                                <option value="{{ $prod->id_producto }}" 
                                        data-codigo="{{ $prod->codigo_producto }}" 
                                        data-nombre="{{ $prod->nombre_producto }}" 
                                        data-precio="{{ $prod->precio_compra }}">
                                    [{{ $prod->codigo_producto }}] {{ $prod->nombre_producto }} (Stock actual: {{ $prod->stock_actual }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="inputCantidad" class="form-label fw-semibold">Cantidad</label>
                        <input type="number" id="inputCantidad" class="form-control" min="1" value="1">
                    </div>

                    <div class="col-md-3">
                        <label for="inputPrecio" class="form-label fw-semibold">Precio Unit. Compra (S/)</label>
                        <input type="number" step="0.01" min="0.01" id="inputPrecio" class="form-control" placeholder="0.00">
                    </div>

                    <div class="col-md-2">
                        <button type="button" id="btnAgregarProducto" class="btn btn-success w-100">
                            <i class="fa-solid fa-plus me-1"></i> Agregar
                        </button>
                    </div>
                </div>

                <!-- Tabla de Detalle -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle" id="tablaDetalle">
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
                        <tbody id="tbodyDetalle">
                            <tr id="rowVacia">
                                <td colspan="6" class="text-center text-muted py-4">No se han agregado productos a esta compra.</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end fs-5">TOTAL COMPRA:</th>
                                <th class="fs-5 text-success fw-bold" id="lblTotal">S/ 0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('compras.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary" id="btnGuardarCompra" disabled>
                        <i class="fa-solid fa-check me-1"></i> Finalizar y Confirmar Compra
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
        const selectProducto = document.getElementById("selectProducto");
        const inputCantidad = document.getElementById("inputCantidad");
        const inputPrecio = document.getElementById("inputPrecio");
        const btnAgregar = document.getElementById("btnAgregarProducto");
        const tbodyDetalle = document.getElementById("tbodyDetalle");
        const rowVacia = document.getElementById("rowVacia");
        const lblTotal = document.getElementById("lblTotal");
        const btnGuardar = document.getElementById("btnGuardarCompra");

        let itemsCart = [];

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

            if (isNaN(cant) || cant <= 0) {
                alert("Ingrese una cantidad válida mayor a 0.");
                return;
            }

            if (isNaN(precio) || precio <= 0) {
                alert("Ingrese un precio unitario válido.");
                return;
            }

            // Verificar si el producto ya está en la lista
            const indexExistente = itemsCart.findIndex(item => item.id_producto == prodId);
            if (indexExistente !== -1) {
                itemsCart[indexExistente].cantidad += cant;
                itemsCart[indexExistente].precio_unitario = precio;
            } else {
                itemsCart.push({
                    id_producto: prodId,
                    codigo: opt.dataset.codigo,
                    nombre: opt.dataset.nombre,
                    cantidad: cant,
                    precio_unitario: precio
                });
            }

            renderTabla();
        });

        function renderTabla() {
            if (itemsCart.length === 0) {
                rowVacia.style.display = "";
                btnGuardar.disabled = true;
                lblTotal.innerText = "S/ 0.00";
                tbodyDetalle.innerHTML = "";
                tbodyDetalle.appendChild(rowVacia);
                return;
            }

            rowVacia.style.display = "none";
            btnGuardar.disabled = false;
            tbodyDetalle.innerHTML = "";

            let total = 0;

            itemsCart.forEach((item, index) => {
                const subtotal = item.cantidad * item.precio_unitario;
                total += subtotal;

                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td><code>${item.codigo}</code></td>
                    <td class="fw-semibold">${item.nombre}</td>
                    <td>
                        <input type="hidden" name="productos[${index}][id_producto]" value="${item.id_producto}">
                        <input type="number" min="1" class="form-control form-control-sm text-center" name="productos[${index}][cantidad]" value="${item.cantidad}" onchange="actualizarCantidad(${index}, this.value)">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0.01" class="form-control form-control-sm text-end" name="productos[${index}][precio_unitario]" value="${item.precio_unitario.toFixed(2)}" onchange="actualizarPrecio(${index}, this.value)">
                    </td>
                    <td class="fw-bold text-end">S/ ${subtotal.toFixed(2)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarItem(${index})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                `;
                tbodyDetalle.appendChild(tr);
            });

            lblTotal.innerText = "S/ " + total.toFixed(2);
        }

        window.actualizarCantidad = function(idx, val) {
            const cant = parseInt(val);
            if (!isNaN(cant) && cant > 0) {
                itemsCart[idx].cantidad = cant;
                renderTabla();
            }
        };

        window.actualizarPrecio = function(idx, val) {
            const p = parseFloat(val);
            if (!isNaN(p) && p > 0) {
                itemsCart[idx].precio_unitario = p;
                renderTabla();
            }
        };

        window.eliminarItem = function(idx) {
            itemsCart.splice(idx, 1);
            renderTabla();
        };
    });
</script>
@endpush
