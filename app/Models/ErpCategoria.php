<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpCategoria extends Model
{
    use HasFactory;

    protected $table = 'erp_categorias';

    protected $fillable = ['nome', 'descricao', 'ativo'];

    protected function casts(): array
    {
        return ['ativo' => 'boolean'];
    }

    public function produtos()
    {
        return $this->hasMany(ErpProduto::class, 'categoria_id');
    }

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }
}
