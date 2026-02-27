<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpMarca extends Model
{
    use HasFactory;

    protected $table = 'erp_marcas';

    protected $fillable = ['nome', 'ativo'];

    protected function casts(): array
    {
        return ['ativo' => 'boolean'];
    }

    public function produtos()
    {
        return $this->hasMany(ErpProduto::class, 'marca_id');
    }

    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }
}
