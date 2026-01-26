<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('erp_clientes', function (Blueprint $table) {
            $table->id();
            $table->char('tipo_pessoa', 1);
            $table->string('cnpj_cpf', 20)->unique();
            $table->string('razao_social');
            $table->string('nome_fantasia')->nullable();
            $table->string('email');
            $table->string('telefone', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_clientes');
    }
};
