<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id('id_proveedor');
            $table->string('razon_social', 150);
            $table->string('ruc', 20)->unique();
            $table->string('telefono', 30)->nullable();
            $table->string('correo', 100)->nullable();
            $table->text('direccion')->nullable();
            $table->string('estado', 20)->default('ACTIVO');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
