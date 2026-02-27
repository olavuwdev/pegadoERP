<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'erp_clientes';
    protected $primaryKey = 'cliente_id';

    protected $fillable = [
        'tipo',
        'tipo_pessoa',
        'cnpj_cpf',
        'razao_social',
        'nome_fantasia',
        'email',
        'telefone',
    ];
}
