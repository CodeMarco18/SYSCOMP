<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id('id_venta');
            $table->foreignId('id_cliente')->constrained('clientes', 'id_cliente')->onDelete('restrict');
            $table->foreignId('id_empleado')->constrained('empleados', 'id_empleado')->onDelete('restrict');
            $table->dateTime('fecha');
            $table->string('tipo_comprobante', 30)->default('BOLETA');
            $table->string('serie', 10);
            $table->string('numero', 20);
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->string('estado', 20)->default('COMPLETADA');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
