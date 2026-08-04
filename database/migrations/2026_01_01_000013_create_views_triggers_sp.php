<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. VISTAS SQL
        DB::statement("DROP VIEW IF EXISTS vw_stock_actual;");
        DB::statement("
            CREATE VIEW vw_stock_actual AS
            SELECT 
                p.id_producto,
                p.codigo_producto, 
                p.nombre_producto, 
                c.nombre_categoria, 
                p.stock_actual, 
                p.stock_minimo, 
                CASE 
                    WHEN p.stock_actual <= p.stock_minimo THEN 'REPONER' 
                    ELSE 'OK' 
                END AS estado_stock
            FROM productos p
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria;
        ");

        DB::statement("DROP VIEW IF EXISTS vw_baja_rotacion;");
        DB::statement("
            CREATE VIEW vw_baja_rotacion AS
            SELECT 
                p.id_producto,
                p.codigo_producto, 
                p.nombre_producto, 
                c.nombre_categoria, 
                p.stock_actual,
                p.precio_venta
            FROM productos p
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE p.id_producto NOT IN (
                SELECT DISTINCT dv.id_producto 
                FROM detalle_ventas dv
                INNER JOIN ventas v ON dv.id_venta = v.id_venta
                WHERE v.fecha >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            );
        ");

        DB::statement("DROP VIEW IF EXISTS vw_ventas_diarias;");
        DB::statement("
            CREATE VIEW vw_ventas_diarias AS
            SELECT 
                DATE(v.fecha) AS fecha, 
                COUNT(v.id_venta) AS numero_ventas, 
                SUM(v.total) AS monto_total
            FROM ventas v
            WHERE v.estado = 'COMPLETADA'
            GROUP BY DATE(v.fecha)
            ORDER BY fecha DESC;
        ");

        // 2. TRIGGERS SQL
        DB::statement("DROP TRIGGER IF EXISTS tg_after_insert_detalle_venta;");
        DB::statement("
            CREATE TRIGGER tg_after_insert_detalle_venta
            AFTER INSERT ON detalle_ventas
            FOR EACH ROW
            BEGIN
                DECLARE v_stock_actual INT;
                DECLARE v_ref VARCHAR(100);
                
                -- Descontar stock
                UPDATE productos 
                SET stock_actual = stock_actual - NEW.cantidad 
                WHERE id_producto = NEW.id_producto;
                
                -- Obtener nuevo stock y referencia
                SELECT stock_actual INTO v_stock_actual FROM productos WHERE id_producto = NEW.id_producto;
                SELECT CONCAT(tipo_comprobante, ' ', serie, '-', numero) INTO v_ref FROM ventas WHERE id_venta = NEW.id_venta;
                
                -- Registrar movimiento SALIDA
                INSERT INTO movimiento_inventarios (id_producto, tipo_movimiento, cantidad, referencia, tipo_referencia, stock_resultante, fecha, created_at, updated_at)
                VALUES (NEW.id_producto, 'SALIDA', NEW.cantidad, COALESCE(v_ref, CONCAT('Venta #', NEW.id_venta)), 'VENTA', v_stock_actual, NOW(), NOW(), NOW());
            END;
        ");

        DB::statement("DROP TRIGGER IF EXISTS tg_after_insert_detalle_compra;");
        DB::statement("
            CREATE TRIGGER tg_after_insert_detalle_compra
            AFTER INSERT ON detalle_compras
            FOR EACH ROW
            BEGIN
                DECLARE v_stock_actual INT;
                
                -- Aumentar stock
                UPDATE productos 
                SET stock_actual = stock_actual + NEW.cantidad 
                WHERE id_producto = NEW.id_producto;
                
                -- Obtener nuevo stock
                SELECT stock_actual INTO v_stock_actual FROM productos WHERE id_producto = NEW.id_producto;
                
                -- Registrar movimiento ENTRADA
                INSERT INTO movimiento_inventarios (id_producto, tipo_movimiento, cantidad, referencia, tipo_referencia, stock_resultante, fecha, created_at, updated_at)
                VALUES (NEW.id_producto, 'ENTRADA', NEW.cantidad, CONCAT('Compra #', NEW.id_compra), 'COMPRA', v_stock_actual, NOW(), NOW(), NOW());
            END;
        ");

        // 3. PROCEDIMIENTO ALMACENADO sp_registrar_venta
        DB::statement("DROP PROCEDURE IF EXISTS sp_registrar_venta;");
        DB::statement("
            CREATE PROCEDURE sp_registrar_venta(
                IN p_id_cliente BIGINT,
                IN p_id_empleado BIGINT,
                IN p_tipo_comprobante VARCHAR(30),
                IN p_serie VARCHAR(10),
                IN p_numero VARCHAR(20),
                IN p_id_producto BIGINT,
                IN p_cantidad INT,
                IN p_precio_unitario DECIMAL(10,2),
                OUT p_resultado INT,
                OUT p_mensaje VARCHAR(255)
            )
            sp_main: BEGIN
                DECLARE v_stock INT;
                DECLARE v_subtotal DECIMAL(10,2);
                DECLARE v_id_venta BIGINT;
                
                -- Manejador de errores
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                    SET p_resultado = 0;
                    SET p_mensaje = 'Error interno en la transacción. Venta cancelada.';
                END;

                START TRANSACTION;
                
                -- Bloquear el producto con FOR UPDATE
                SELECT stock_actual INTO v_stock 
                FROM productos 
                WHERE id_producto = p_id_producto 
                FOR UPDATE;
                
                -- Verificar stock suficiente
                IF v_stock IS NULL OR v_stock < p_cantidad THEN
                    ROLLBACK;
                    SET p_resultado = 0;
                    SET p_mensaje = 'Stock insuficiente para completar la venta';
                    LEAVE sp_main;
                END IF;
                
                -- Calcular subtotal y total
                SET v_subtotal = p_cantidad * p_precio_unitario;
                
                -- Registrar Venta
                INSERT INTO ventas (id_cliente, id_empleado, fecha, tipo_comprobante, serie, numero, subtotal, total, estado, created_at, updated_at)
                VALUES (p_id_cliente, p_id_empleado, NOW(), p_tipo_comprobante, p_serie, p_numero, v_subtotal, v_subtotal, 'COMPLETADA', NOW(), NOW());
                
                SET v_id_venta = LAST_INSERT_ID();
                
                -- Registrar Detalle de Venta (Trigger descontará stock y creará movimiento)
                INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, precio_unitario, subtotal, created_at, updated_at)
                VALUES (v_id_venta, p_id_producto, p_cantidad, p_precio_unitario, v_subtotal, NOW(), NOW());
                
                -- Registrar Garantía automática de 12 meses para computo
                INSERT INTO garantia_productos (id_detalle_venta, codigo_garantia, fecha_inicio, fecha_vencimiento, periodo_meses, estado, observaciones, created_at, updated_at)
                VALUES (LAST_INSERT_ID(), CONCAT('GAR-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', v_id_venta), CURDATE(), DATE_ADD(CURDATE(), INTERVAL 12 MONTH), 12, 'VIGENTE', 'Garantía oficial de tienda por 12 meses', NOW(), NOW());

                COMMIT;
                
                SET p_resultado = 1;
                SET p_mensaje = 'Venta registrada correctamente';
            END;
        ");
    }

    public function down(): void
    {
        DB::statement("DROP PROCEDURE IF EXISTS sp_registrar_venta;");
        DB::statement("DROP TRIGGER IF EXISTS tg_after_insert_detalle_compra;");
        DB::statement("DROP TRIGGER IF EXISTS tg_after_insert_detalle_venta;");
        DB::statement("DROP VIEW IF EXISTS vw_ventas_diarias;");
        DB::statement("DROP VIEW IF EXISTS vw_baja_rotacion;");
        DB::statement("DROP VIEW IF EXISTS vw_stock_actual;");
    }
};
