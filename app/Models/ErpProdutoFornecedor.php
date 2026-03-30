<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ErpProdutoFornecedor extends Pivot
{
    protected $table = 'erp_produto_fornecedor';

    public $incrementing = true;

    protected $fillable = [
        'produto_id',
        'fornecedor_id',
        'codigo_fornecedor',
        'preco_fornecedor',
        'prazo_entrega_dias',
        'principal',
    ];


    protected function casts(): array
    {
        return [
            'preco_fornecedor' => 'decimal:4',
            'prazo_entrega_dias' => 'integer',
            'principal' => 'boolean',
        ];
    }
}
