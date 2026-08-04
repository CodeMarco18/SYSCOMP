<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimiento_inventarios', function (Blueprint $table) {
            $table->id('id_movimiento');
            $table->foreignId('id_producto')->constrained('productos', 'id_producto')->onDelete('cascade');
            $table->enum('tipo_movimiento', ['ENTRADA', 'SALIDA']);
            $table->integer('cantidad');
            $table->string('referencia', 100)->nullable();
            $table->string('tipo_referencia', 50)->nullable();
            $table->integer('stock_resultante');
            $table->dateTime('fecha')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimiento_inventarios');
    }
};
