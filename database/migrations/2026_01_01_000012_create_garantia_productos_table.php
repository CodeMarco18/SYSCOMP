<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garantia_productos', function (Blueprint $table) {
            $table->id('id_garantia');
            $table->foreignId('id_detalle_venta')->unique()->constrained('detalle_ventas', 'id_detalle_venta')->onDelete('cascade');
            $table->string('codigo_garantia', 50)->unique();
            $table->date('fecha_inicio');
            $table->date('fecha_vencimiento');
            $table->integer('periodo_meses')->default(12);
            $table->enum('estado', ['VIGENTE', 'VENCIDA'])->default('VIGENTE');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garantia_productos');
    }
};
