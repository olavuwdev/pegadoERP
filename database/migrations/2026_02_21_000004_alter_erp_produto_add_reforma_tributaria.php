<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('erp_produto', function (Blueprint $table) {
            // FK para marca (substitui varchar livre)
            $table->unsignedBigInteger('marca_id')->nullable()->after('marca');
            $table->foreign('marca_id')->references('id')->on('erp_marcas')->nullOnDelete();

            // === REFORMA TRIBUTÁRIA — Novos campos IBS/CBS (transição 2026–2033) ===
            // IBS = Imposto sobre Bens e Serviços (Estados + Municípios) — substitui ICMS + ISS
            $table->decimal('aliquota_ibs', 5, 2)->nullable()->after('aliquota_ipi')
                ->comment('Alíquota IBS (substitui ICMS+ISS na transição)');
            $table->decimal('reducao_bc_ibs', 5, 2)->nullable()->after('aliquota_ibs')
                ->comment('Redução de base de cálculo IBS (%)');

            // CBS = Contribuição sobre Bens e Serviços (União) — substitui PIS + COFINS
            $table->decimal('aliquota_cbs', 5, 2)->nullable()->after('reducao_bc_ibs')
                ->comment('Alíquota CBS (substitui PIS+COFINS na transição)');
            $table->decimal('reducao_bc_cbs', 5, 2)->nullable()->after('aliquota_cbs')
                ->comment('Redução de base de cálculo CBS (%)');

            // IS = Imposto Seletivo (Federal) — incide sobre produtos prejudiciais à saúde/meio ambiente
            $table->boolean('sujeito_imposto_seletivo')->default(false)->after('reducao_bc_cbs')
                ->comment('Produto sujeito ao Imposto Seletivo?');
            $table->decimal('aliquota_imposto_seletivo', 5, 2)->nullable()->after('sujeito_imposto_seletivo')
                ->comment('Alíquota do Imposto Seletivo (%)');

            // Regime tributário de referência para transição
            $table->enum('regime_tributario', ['SIMPLES', 'LUCRO_PRESUMIDO', 'LUCRO_REAL'])
                ->nullable()->after('aliquota_imposto_seletivo')
                ->comment('Regime tributário aplicável ao produto');
        });
    }

    public function down(): void
    {
        Schema::table('erp_produto', function (Blueprint $table) {
            $table->dropForeign(['marca_id']);
            $table->dropColumn([
                'marca_id',
                'aliquota_ibs',
                'reducao_bc_ibs',
                'aliquota_cbs',
                'reducao_bc_cbs',
                'sujeito_imposto_seletivo',
                'aliquota_imposto_seletivo',
                'regime_tributario',
            ]);
        });
    }
};
