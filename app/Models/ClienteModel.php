<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteModel extends Model
{
    //modelar o model com os campos da tabela erp_clientes

    /* Coluna	Tipo	Nulo	Padrão	Links para 	Comentários
cliente_id (Primária) 	bigint(20)	Não 	  	  	 
tipo_pessoa 	enum('F', 'J')	Não 	  	  	 
cnpj_cpf 	varchar(18)	Não 	  	  	 
inscr_estadual 	varchar(20)	Sim 	NULL  	  	 
razao_social 	varchar(200)	Não 	  	  	 
nome_fantasia 	varchar(200)	Sim 	NULL  	  	 
email 	varchar(255)	Sim 	NULL  	  	 
telefone 	varchar(30)	Sim 	NULL  	  	 
logradouro 	varchar(200)	Sim 	NULL  	  	 
numero 	varchar(20)	Sim 	NULL  	  	 
complemento 	varchar(100)	Sim 	NULL  	  	 
bairro 	varchar(100)	Sim 	NULL  	  	 
cidade 	varchar(100)	Sim 	NULL  	  	 
uf 	char(2)	Sim 	NULL  	  	 
cep 	varchar(10)	Sim 	NULL  	  	 
pais 	char(2)	Não 	BR  	  	 
ativo 	tinyint(1)	Não 	1  	  	 
criado_em 	datetime	Não 	current_timestamp()  	  	 
atualizado_em 	datetime	Não 	current_timestamp() 
 */
    protected $table = 'erp_clientes';
    protected $primaryKey = 'cliente_id';
    public $timestamps = true;

    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';
    protected $fillable = [
        'tipo_pessoa',
        'cnpj_cpf',
        'inscr_estadual',
        'razao_social',
        'nome_fantasia',
        'email',
        'telefone',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'pais',
        'ativo'
    ];
    protected $dates = [
        'criado_em',
        'atualizado_em',
    ];


}
