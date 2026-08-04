<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('id_empleado');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('documento', 20)->unique();
            $table->string('cargo', 50);
            $table->string('telefono', 30)->nullable();
            $table->string('correo', 100)->nullable();
            $table->string('estado', 20)->default('ACTIVO');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
