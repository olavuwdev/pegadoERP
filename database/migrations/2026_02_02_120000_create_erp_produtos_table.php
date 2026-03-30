<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erp_produto', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo', ['PRODUTO', 'SERVICO'])->default('PRODUTO')->index();
            $table->string('nome', 255)->index();
            $table->string('codigo_sku', 100)->unique();
            $table->string('codigo_barras', 50)->nullable()->index();
            $table->text('descricao')->nullable();
            $table->string('marca', 150)->nullable();
            $table->unsignedBigInteger('categoria_id')->nullable()->index();

            $table->decimal('preco_custo', 15, 4)->default(0);
            $table->decimal('preco_venda', 15, 4)->default(0);
            $table->decimal('quantidade_estoque', 15, 4)->default(0);
            $table->decimal('estoque_minimo', 15, 4)->default(0);
            $table->string('unidade_medida', 10)->default('UN');
            $table->decimal('peso', 10, 4)->nullable();
            $table->decimal('largura', 10, 4)->nullable();
            $table->decimal('altura', 10, 4)->nullable();
            $table->decimal('comprimento', 10, 4)->nullable();

            $table->boolean('ativo')->default(true);
            $table->char('ncm', 8)->nullable()->index();
            $table->char('cest', 7)->nullable();
            $table->string('codigo_anp', 20)->nullable();
            $table->unsignedTinyInteger('origem_mercadoria')->default(0);
            $table->string('ex_tipi', 5)->nullable();
            $table->string('codigo_beneficio_fiscal', 20)->nullable();

            $table->string('cst_icms', 3)->nullable();
            $table->string('csosn', 3)->nullable();
            $table->string('modalidade_bc_icms', 2)->nullable();
            $table->decimal('aliquota_icms', 5, 2)->nullable();
            $table->decimal('reducao_bc_icms', 5, 2)->nullable();
            $table->decimal('mva_icms', 5, 2)->nullable();
            $table->decimal('aliquota_icms_st', 5, 2)->nullable();
            $table->decimal('aliquota_fcp', 5, 2)->nullable();
            $table->decimal('aliquota_fcp_st', 5, 2)->nullable();

            $table->string('cst_pis', 3)->nullable();
            $table->decimal('aliquota_pis', 5, 2)->nullable();
            $table->decimal('base_calculo_pis', 15, 4)->nullable();
            $table->string('cst_cofins', 3)->nullable();
            $table->decimal('aliquota_cofins', 5, 2)->nullable();
            $table->decimal('base_calculo_cofins', 15, 4)->nullable();
            $table->string('cst_ipi', 3)->nullable();
            $table->string('codigo_enquadramento_ipi', 5)->nullable();
            $table->decimal('aliquota_ipi', 5, 2)->nullable();

            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['ativo', 'tipo', 'ncm'], 'idx_produto_filtros');
            $table->index(['nome', 'codigo_sku', 'codigo_barras'], 'idx_produto_busca');
        });

        Schema::create('erp_produto_imagem', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('erp_produto')->cascadeOnDelete();
            $table->string('caminho_arquivo', 255);
            $table->string('nome_original', 255)->nullable();
            $table->string('mime_type', 100);
            $table->unsignedInteger('tamanho_bytes')->default(0);
            $table->unsignedSmallInteger('ordem')->default(0)->index();
            $table->boolean('imagem_principal')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_produto_imagem');
        Schema::dropIfExists('erp_produto');
    }
};
