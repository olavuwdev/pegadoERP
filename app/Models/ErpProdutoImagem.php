<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpProdutoImagem extends Model
{
    use HasFactory;

    protected $table = 'erp_produto_imagem';

    protected $fillable = [
        'produto_id',
        'caminho_arquivo',
        'nome_original',
        'mime_type',
        'tamanho_bytes',
        'ordem',
        'imagem_principal',
    ];

    protected function casts(): array
    {
        return [
            'produto_id' => 'integer',
            'ordem' => 'integer',
            'tamanho_bytes' => 'integer',
            'imagem_principal' => 'boolean',
        ];
    }

    public function produto()
    {
        return $this->belongsTo(ErpProduto::class, 'produto_id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . ltrim($this->caminho_arquivo, '/'));
    }
}
