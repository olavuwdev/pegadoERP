<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Cliente;

class ClienteTest extends TestCase
{
    public function test_table_name()
    {
        $cliente = new Cliente();
        $this->assertEquals('erp_clientes', $cliente->getTable());
    }

    public function test_fillable()
    {
        $cliente = new Cliente();
        $this->assertEquals([
            'tipo_pessoa',
            'cnpj_cpf',
            'razao_social',
            'nome_fantasia',
            'email',
            'telefone',
        ], $cliente->getFillable());
    }
}
