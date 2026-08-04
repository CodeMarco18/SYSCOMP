<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\GarantiaProductoController;
use App\Http\Controllers\ReporteController;

// Rutas de Autenticación (Invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Rutas Autenticadas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Módulo Almacén & Compras (Administrador y Almacenero)
    Route::middleware('role:Almacenero')->group(function () {
        Route::resource('categorias', CategoriaController::class);
        Route::resource('productos', ProductoController::class);
        Route::patch('/productos/{producto}/estado', [ProductoController::class, 'toggleEstado'])->name('productos.toggle-estado');
        Route::resource('proveedores', ProveedorController::class);
        Route::resource('compras', CompraController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('/movimientos', [MovimientoInventarioController::class, 'index'])->name('movimientos.index');
    });

    // Módulo Ventas & Clientes (Administrador y Vendedor)
    Route::middleware('role:Vendedor')->group(function () {
        Route::resource('clientes', ClienteController::class);
        Route::resource('ventas', VentaController::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('garantias', GarantiaProductoController::class)->only(['index', 'show']);
    });

    // Módulo Administración y Reportes (Administrador)
    Route::middleware('role:Administrador')->group(function () {
        Route::resource('empleados', EmpleadoController::class);
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    });
});
