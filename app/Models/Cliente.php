<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'erp_clientes';

    protected $fillable = [
        'tipo_pessoa',
        'cnpj_cpf',
        'razao_social',
        'nome_fantasia',
        'email',
        'telefone',
    ];
}
