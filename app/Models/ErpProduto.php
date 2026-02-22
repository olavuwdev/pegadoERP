<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpProduto extends Model
{
    use HasFactory;

    protected $table = 'erp_produto';

    protected $fillable = [
        'tipo',
        'nome',
        'codigo_sku',
        'codigo_barras',
        'descricao',
        'marca',
        'categoria_id',
        'preco_custo',
        'preco_venda',
        'quantidade_estoque',
        'estoque_minimo',
        'unidade_medida',
        'peso',
        'largura',
        'altura',
        'comprimento',
        'ativo',
        'ncm',
        'cest',
        'codigo_anp',
        'origem_mercadoria',
        'ex_tipi',
        'codigo_beneficio_fiscal',
        'cst_icms',
        'csosn',
        'modalidade_bc_icms',
        'aliquota_icms',
        'reducao_bc_icms',
        'mva_icms',
        'aliquota_icms_st',
        'aliquota_fcp',
        'aliquota_fcp_st',
        'cst_pis',
        'aliquota_pis',
        'base_calculo_pis',
        'cst_cofins',
        'aliquota_cofins',
        'base_calculo_cofins',
        'cst_ipi',
        'codigo_enquadramento_ipi',
        'aliquota_ipi',
        'observacoes',
    ];

    protected function casts(): array
    {
        return [
            'preco_custo' => 'decimal:4',
            'preco_venda' => 'decimal:4',
            'quantidade_estoque' => 'decimal:4',
            'estoque_minimo' => 'decimal:4',
            'peso' => 'decimal:4',
            'largura' => 'decimal:4',
            'altura' => 'decimal:4',
            'comprimento' => 'decimal:4',
            'aliquota_icms' => 'decimal:2',
            'reducao_bc_icms' => 'decimal:2',
            'mva_icms' => 'decimal:2',
            'aliquota_icms_st' => 'decimal:2',
            'aliquota_fcp' => 'decimal:2',
            'aliquota_fcp_st' => 'decimal:2',
            'aliquota_pis' => 'decimal:2',
            'base_calculo_pis' => 'decimal:4',
            'aliquota_cofins' => 'decimal:2',
            'base_calculo_cofins' => 'decimal:4',
            'aliquota_ipi' => 'decimal:2',
            'ativo' => 'boolean',
            'origem_mercadoria' => 'integer',
        ];
    }

    public function imagens()
    {
        return $this->hasMany(ErpProdutoImagem::class, 'produto_id');
    }

    public function imagemPrincipal()
    {
        return $this->hasOne(ErpProdutoImagem::class, 'produto_id')->where('imagem_principal', true);
    }

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeDisponivelParaVenda($query)
    {
        return $query->where('ativo', true)->where('tipo', 'PRODUTO');
    }
}
