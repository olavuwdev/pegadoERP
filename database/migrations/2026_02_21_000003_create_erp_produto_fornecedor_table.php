<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erp_produto_fornecedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('erp_produto')->cascadeOnDelete();
            $table->foreignId('fornecedor_id')->constrained('erp_clientes')->cascadeOnDelete();
            $table->string('codigo_fornecedor', 100)->nullable();
            $table->decimal('preco_fornecedor', 15, 4)->nullable()->default(0);
            $table->unsignedInteger('prazo_entrega_dias')->nullable();
            $table->boolean('principal')->default(false);
            $table->timestamps();
            $table->unique(['produto_id', 'fornecedor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_produto_fornecedor');
    }
};
