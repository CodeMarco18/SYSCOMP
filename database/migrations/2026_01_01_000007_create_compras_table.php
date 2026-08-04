<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id('id_compra');
            $table->foreignId('id_proveedor')->constrained('proveedores', 'id_proveedor')->onDelete('restrict');
            $table->foreignId('id_empleado')->constrained('empleados', 'id_empleado')->onDelete('restrict');
            $table->dateTime('fecha');
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->string('estado', 20)->default('COMPLETADA');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
