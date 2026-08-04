<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\GarantiaProducto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categorías de Tienda de Cómputo
        $catLaptops = Categoria::create(['nombre_categoria' => 'Laptops y Portátiles', 'descripcion' => 'Laptops Gamer, Ultrabooks y Portátiles de Trabajo']);
        $catProcesadores = Categoria::create(['nombre_categoria' => 'Procesadores', 'descripcion' => 'Procesadores Intel Core y AMD Ryzen']);
        $catTarjetasVideo = Categoria::create(['nombre_categoria' => 'Tarjetas de Video', 'descripcion' => 'GPUs NVIDIA GeForce RTX y AMD Radeon']);
        $catMemorias = Categoria::create(['nombre_categoria' => 'Memorias RAM y Almacenamiento', 'descripcion' => 'Módulos RAM DDR4/DDR5 y Discos SSD NVMe']);
        $catPerifericos = Categoria::create(['nombre_categoria' => 'Periféricos y Monitores', 'descripcion' => 'Teclados mecánicos, Mouse Gamer y Monitores 144Hz']);
        $catFuentesChasis = Categoria::create(['nombre_categoria' => 'Fuentes y Gabinetes', 'descripcion' => 'Fuentes Certificadas 80 Plus y Cases Gamer ATX']);

        // 2. Proveedores
        $provDeltron = Proveedor::create([
            'razon_social' => 'Grupo Deltron S.A.C.',
            'ruc' => '20100123456',
            'telefono' => '01-7108000',
            'correo' => 'ventas@deltron.com.pe',
            'direccion' => 'Av. Manuel Olguín 211, Surco, Lima',
            'estado' => 'ACTIVO',
        ]);

        $provIngram = Proveedor::create([
            'razon_social' => 'Ingram Micro Perú S.A.',
            'ruc' => '20251123457',
            'telefono' => '01-6167000',
            'correo' => 'contacto@ingrammicro.pe',
            'direccion' => 'Av. Rivera Navarrete 501, San Isidro, Lima',
            'estado' => 'ACTIVO',
        ]);

        $provTechData = Proveedor::create([
            'razon_social' => 'Tech Data Latin America S.A.C.',
            'ruc' => '20509876543',
            'telefono' => '01-5123900',
            'correo' => 'info@techdata.com.pe',
            'direccion' => 'Av. República de Panamá 3505, San Isidro',
            'estado' => 'ACTIVO',
        ]);

        // 3. Empleados
        $empAdmin = Empleado::create([
            'nombres' => 'Marco Antonio',
            'apellidos' => 'Gutiérrez Silva',
            'documento' => '70123456',
            'cargo' => 'Administrador',
            'telefono' => '987654321',
            'correo' => 'admin@syscomp.com',
            'estado' => 'ACTIVO',
        ]);

        $empVendedor = Empleado::create([
            'nombres' => 'Sofía Elena',
            'apellidos' => 'Ramírez Morales',
            'documento' => '71987654',
            'cargo' => 'Vendedor',
            'telefono' => '912345678',
            'correo' => 'vendedor@syscomp.com',
            'estado' => 'ACTIVO',
        ]);

        $empAlmacenero = Empleado::create([
            'nombres' => 'Carlos Alberto',
            'apellidos' => 'Vargas Quispe',
            'documento' => '72555444',
            'cargo' => 'Almacenero',
            'telefono' => '955443322',
            'correo' => 'almacenero@syscomp.com',
            'estado' => 'ACTIVO',
        ]);

        // 4. Usuarios de Acceso al Sistema
        User::create([
            'name' => 'Marco (Administrador)',
            'email' => 'admin@syscomp.com',
            'password' => Hash::make('password'),
            'role' => 'Administrador',
            'id_empleado' => $empAdmin->id_empleado,
        ]);

        User::create([
            'name' => 'Sofía (Ventas)',
            'email' => 'vendedor@syscomp.com',
            'password' => Hash::make('password'),
            'role' => 'Vendedor',
            'id_empleado' => $empVendedor->id_empleado,
        ]);

        User::create([
            'name' => 'Carlos (Almacén)',
            'email' => 'almacenero@syscomp.com',
            'password' => Hash::make('password'),
            'role' => 'Almacenero',
            'id_empleado' => $empAlmacenero->id_empleado,
        ]);

        // 5. Clientes
        $cli1 = Cliente::create([
            'nombres' => 'Juan Pedro',
            'apellidos' => 'Flores Castro',
            'documento' => '45891234',
            'telefono' => '978123456',
            'correo' => 'jflores@gmail.com',
            'direccion' => 'Av. Javier Prado Este 2450, San Borja',
            'estado' => 'ACTIVO',
        ]);

        $cli2 = Cliente::create([
            'nombres' => 'Empresa Innovatech',
            'apellidos' => 'S.A.C.',
            'documento' => '20609988771',
            'telefono' => '01-4455667',
            'correo' => 'compras@innovatech.pe',
            'direccion' => 'Calle Los Pinos 140, Miraflores',
            'estado' => 'ACTIVO',
        ]);

        $cli3 = Cliente::create([
            'nombres' => 'Lucía María',
            'apellidos' => 'Benavides Torres',
            'documento' => '73214569',
            'telefono' => '966332211',
            'correo' => 'lbenavides@outlook.com',
            'direccion' => 'Av. Universitaria 1020, Los Olivos',
            'estado' => 'ACTIVO',
        ]);

        // 6. Productos
        $prod1 = Producto::create([
            'codigo_producto' => 'LAP-ASUS-FX507',
            'nombre_producto' => 'Laptop Gamer ASUS TUF Gaming F15 Core i7-13620H 16GB DDR5 SSD 512GB RTX 4060 8GB 15.6" 144Hz',
            'id_categoria' => $catLaptops->id_categoria,
            'marca' => 'ASUS',
            'modelo' => 'TUF FX507VV',
            'numero_serie' => 'SN-ASUS-9910',
            'precio_compra' => 4150.00,
            'precio_venta' => 4899.00,
            'stock_actual' => 12,
            'stock_minimo' => 3,
            'estado' => 'ACTIVO',
        ]);

        $prod2 = Producto::create([
            'codigo_producto' => 'CPU-INTEL-I714700K',
            'nombre_producto' => 'Procesador Intel Core i7-14700K 20 Núcleos LGA1700 Hasta 5.6GHz',
            'id_categoria' => $catProcesadores->id_categoria,
            'marca' => 'Intel',
            'modelo' => 'BX8071514700K',
            'numero_serie' => 'SN-INTEL-7721',
            'precio_compra' => 1620.00,
            'precio_venta' => 1899.00,
            'stock_actual' => 8,
            'stock_minimo' => 2,
            'estado' => 'ACTIVO',
        ]);

        $prod3 = Producto::create([
            'codigo_producto' => 'GPU-MSI-RTX4070S',
            'nombre_producto' => 'Tarjeta de Video MSI NVIDIA GeForce RTX 4070 SUPER Ventus 2X 12GB GDDR6X',
            'id_categoria' => $catTarjetasVideo->id_categoria,
            'marca' => 'MSI',
            'modelo' => 'RTX 4070 SUPER 12G',
            'numero_serie' => 'SN-MSI-4070S',
            'precio_compra' => 2850.00,
            'precio_venta' => 3299.00,
            'stock_actual' => 5,
            'stock_minimo' => 2,
            'estado' => 'ACTIVO',
        ]);

        $prod4 = Producto::create([
            'codigo_producto' => 'SSD-KINGSTON-2TB',
            'nombre_producto' => 'Disco Solido SSD NVMe M.2 Kingston KC3000 2TB PCIe 4.0 (7000MB/s)',
            'id_categoria' => $catMemorias->id_categoria,
            'marca' => 'Kingston',
            'modelo' => 'SKC3000D/2048G',
            'numero_serie' => 'SN-KNG-2000',
            'precio_compra' => 520.00,
            'precio_venta' => 649.00,
            'stock_actual' => 20,
            'stock_minimo' => 5,
            'estado' => 'ACTIVO',
        ]);

        $prod5 = Producto::create([
            'codigo_producto' => 'MON-LG-27GP850',
            'nombre_producto' => 'Monitor Gamer LG UltraGear 27" Nano IPS QHD 2K 165Hz 1ms G-Sync',
            'id_categoria' => $catPerifericos->id_categoria,
            'marca' => 'LG',
            'modelo' => '27GP850-B',
            'numero_serie' => 'SN-LG-27850',
            'precio_compra' => 1280.00,
            'precio_venta' => 1499.00,
            'stock_actual' => 2, // Alerta stock bajo
            'stock_minimo' => 4,
            'estado' => 'ACTIVO',
        ]);

        $prod6 = Producto::create([
            'codigo_producto' => 'PSU-CORSAIR-RM850X',
            'nombre_producto' => 'Fuente de Poder Corsair RM850x 850W 80 Plus Gold Modular ATX 3.0',
            'id_categoria' => $catFuentesChasis->id_categoria,
            'marca' => 'Corsair',
            'modelo' => 'CP-9020200-NA',
            'numero_serie' => 'SN-COR-850',
            'precio_compra' => 510.00,
            'precio_venta' => 599.00,
            'stock_actual' => 15,
            'stock_minimo' => 4,
            'estado' => 'ACTIVO',
        ]);

        // 7. Compra Inicial de Cómputo
        $compraInicial = Compra::create([
            'id_proveedor' => $provDeltron->id_proveedor,
            'id_empleado' => $empAlmacenero->id_empleado,
            'fecha' => now()->subDays(5),
            'subtotal' => 25800.00,
            'total' => 25800.00,
            'estado' => 'COMPLETADA',
        ]);

        DetalleCompra::create([
            'id_compra' => $compraInicial->id_compra,
            'id_producto' => $prod1->id_producto,
            'cantidad' => 5,
            'precio_unitario' => 4150.00,
            'subtotal' => 20750.00,
        ]);

        DetalleCompra::create([
            'id_compra' => $compraInicial->id_compra,
            'id_producto' => $prod6->id_producto,
            'cantidad' => 10,
            'precio_unitario' => 505.00,
            'subtotal' => 5050.00,
        ]);

        // 8. Ventas Iniciales de Ejemplo
        $venta1 = Venta::create([
            'id_cliente' => $cli1->id_cliente,
            'id_empleado' => $empVendedor->id_empleado,
            'fecha' => now()->subDays(2),
            'tipo_comprobante' => 'BOLETA',
            'serie' => 'B001',
            'numero' => '00000001',
            'subtotal' => 4899.00,
            'total' => 4899.00,
            'estado' => 'COMPLETADA',
        ]);

        $det1 = DetalleVenta::create([
            'id_venta' => $venta1->id_venta,
            'id_producto' => $prod1->id_producto,
            'cantidad' => 1,
            'precio_unitario' => 4899.00,
            'subtotal' => 4899.00,
        ]);

        GarantiaProducto::create([
            'id_detalle_venta' => $det1->id_detalle_venta,
            'codigo_garantia' => 'GAR-' . date('Ymd') . '-01',
            'fecha_inicio' => now()->subDays(2)->toDateString(),
            'fecha_vencimiento' => now()->addMonths(12)->toDateString(),
            'periodo_meses' => 12,
            'estado' => 'VIGENTE',
            'observaciones' => 'Garantía por laptop de juego ASUS. Cobre hardware y componentes internos.',
        ]);

        $venta2 = Venta::create([
            'id_cliente' => $cli2->id_cliente,
            'id_empleado' => $empVendedor->id_empleado,
            'fecha' => now(),
            'tipo_comprobante' => 'FACTURA',
            'serie' => 'F001',
            'numero' => '00000002',
            'subtotal' => 3898.00,
            'total' => 3898.00,
            'estado' => 'COMPLETADA',
        ]);

        $det2 = DetalleVenta::create([
            'id_venta' => $venta2->id_venta,
            'id_producto' => $prod2->id_producto,
            'cantidad' => 2,
            'precio_unitario' => 1899.00,
            'subtotal' => 3798.00,
        ]);

        GarantiaProducto::create([
            'id_detalle_venta' => $det2->id_detalle_venta,
            'codigo_garantia' => 'GAR-' . date('Ymd') . '-02',
            'fecha_inicio' => now()->toDateString(),
            'fecha_vencimiento' => now()->addMonths(36)->toDateString(),
            'periodo_meses' => 36,
            'estado' => 'VIGENTE',
            'observaciones' => 'Garantía extendida oficial de procesador Intel.',
        ]);
    }
}
