<?php

namespace Tests\Feature;

use App\Models\ErpProduto;
use App\Models\ErpProdutoImagem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProdutoV2Test extends TestCase
{
    use RefreshDatabase;

    public function test_cadastra_produto_valido_com_multiplas_imagens_e_principal(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $payload = $this->payload([
            'imagens' => [
                UploadedFile::fake()->image('a.jpg'),
                UploadedFile::fake()->image('b.png'),
            ],
            'imagem_principal' => 'new:1',
            'ordem_nova' => [2, 1],
        ]);

        $response = $this->post('/produto', $payload, ['Accept' => 'application/json']);
        $response->assertOk()->assertJsonStructure(['message', 'id']);

        $produtoId = (int) $response->json('id');
        $this->assertDatabaseHas('erp_produto', ['id' => $produtoId, 'codigo_sku' => 'SKU-BASE']);
        $this->assertDatabaseCount('erp_produto_imagem', 2);
        $this->assertSame(1, ErpProdutoImagem::query()->where('produto_id', $produtoId)->where('imagem_principal', true)->count());
    }

    public function test_retorna_validacao_para_ncm_percentual_e_preco_invalidos(): void
    {
        $this->actingAs(User::factory()->create());

        $payload = $this->payload([
            'ncm' => '123',
            'aliquota_icms' => '150',
            'preco_venda' => '-10',
        ]);

        $response = $this->post('/produto', $payload, ['Accept' => 'application/json']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['ncm', 'aliquota_icms', 'preco_venda']);
    }

    public function test_edita_produto_mantendo_fiscal_e_alterando_galeria(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $produto = ErpProduto::query()->create($this->payload([
            'nome' => 'Produto antigo',
            'codigo_sku' => 'SKU-EDIT',
            'cst_icms' => '00',
            'ncm' => '84713012',
        ]));

        $img1 = ErpProdutoImagem::query()->create([
            'produto_id' => $produto->id,
            'caminho_arquivo' => 'produtos/imagens/' . $produto->id . '/img1.jpg',
            'nome_original' => 'img1.jpg',
            'mime_type' => 'image/jpeg',
            'tamanho_bytes' => 10,
            'ordem' => 1,
            'imagem_principal' => true,
        ]);

        ErpProdutoImagem::query()->create([
            'produto_id' => $produto->id,
            'caminho_arquivo' => 'produtos/imagens/' . $produto->id . '/img2.jpg',
            'nome_original' => 'img2.jpg',
            'mime_type' => 'image/jpeg',
            'tamanho_bytes' => 10,
            'ordem' => 2,
            'imagem_principal' => false,
        ]);

        $payload = $this->payload([
            'nome' => 'Produto editado',
            'codigo_sku' => 'SKU-EDIT',
            'cst_icms' => '00',
            'ncm' => '84713012',
            'imagens_remover' => [$img1->id],
            'imagens' => [UploadedFile::fake()->image('nova.webp')],
            'imagem_principal' => 'new:0',
            'ordem_nova' => [0 => 1],
        ]);

        $response = $this->put('/produto/' . $produto->id, $payload, ['Accept' => 'application/json']);
        $response->assertOk()->assertJson(['message' => 'Produto atualizado com sucesso!']);

        $this->assertDatabaseHas('erp_produto', [
            'id' => $produto->id,
            'nome' => 'Produto editado',
            'ncm' => '84713012',
            'cst_icms' => '00',
        ]);
        $this->assertDatabaseMissing('erp_produto_imagem', ['id' => $img1->id]);
        $this->assertSame(1, ErpProdutoImagem::query()->where('produto_id', $produto->id)->where('imagem_principal', true)->count());
    }

    public function test_listagem_datatables_retorna_json_com_filtro_e_paginacao(): void
    {
        $this->actingAs(User::factory()->create());

        ErpProduto::query()->create($this->payload([
            'codigo_sku' => 'SKU-A',
            'nome' => 'A',
            'ncm' => '84713012',
            'tipo' => 'PRODUTO',
            'ativo' => true,
        ]));

        ErpProduto::query()->create($this->payload([
            'codigo_sku' => 'SKU-B',
            'nome' => 'B',
            'ncm' => '84713013',
            'tipo' => 'PRODUTO',
            'ativo' => true,
        ]));

        ErpProduto::query()->create($this->payload([
            'codigo_sku' => 'SKU-C',
            'nome' => 'C',
            'ncm' => '00000000',
            'tipo' => 'SERVICO',
            'ativo' => false,
        ]));

        $response = $this->getJson('/produto/dados?ativo=1&tipo=PRODUTO&ncm=8471&start=0&length=1&draw=1');

        $response->assertOk()
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data'])
            ->assertJsonCount(1, 'data');
    }

    public function test_inativa_e_reativa_produto_sem_excluir(): void
    {
        $this->actingAs(User::factory()->create());

        $produto = ErpProduto::query()->create($this->payload(['codigo_sku' => 'SKU-STATUS']));

        $this->putJson('/produto/' . $produto->id, ['_status_only' => 1, 'ativo' => 0])
            ->assertOk();

        $this->assertDatabaseHas('erp_produto', ['id' => $produto->id, 'ativo' => 0]);

        $this->putJson('/produto/' . $produto->id, ['_status_only' => 1, 'ativo' => 1])
            ->assertOk();

        $this->assertDatabaseHas('erp_produto', ['id' => $produto->id, 'ativo' => 1]);
    }

    public function test_bloqueia_acesso_sem_autenticacao(): void
    {
        $this->get('/produto')->assertRedirect('/login');
        $this->get('/produto/dados')->assertRedirect('/login');
    }

    public function test_garante_apenas_uma_imagem_principal_por_produto(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $response = $this->post('/produto', $this->payload([
            'codigo_sku' => 'SKU-PRINCIPAL',
            'imagens' => [
                UploadedFile::fake()->image('1.jpg'),
                UploadedFile::fake()->image('2.jpg'),
                UploadedFile::fake()->image('3.jpg'),
            ],
            'imagem_principal' => 'new:2',
        ]), ['Accept' => 'application/json']);

        $response->assertOk();
        $produtoId = (int) $response->json('id');

        $this->assertSame(1, ErpProdutoImagem::query()
            ->where('produto_id', $produtoId)
            ->where('imagem_principal', true)
            ->count());
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'tipo' => 'PRODUTO',
            'nome' => 'Produto Base',
            'codigo_sku' => 'SKU-BASE',
            'codigo_barras' => '7891234567890',
            'descricao' => 'Descricao base',
            'marca' => 'Marca X',
            'categoria_id' => 1,
            'preco_custo' => '10,00',
            'preco_venda' => '15,00',
            'quantidade_estoque' => '5,0000',
            'estoque_minimo' => '1,0000',
            'unidade_medida' => 'UN',
            'peso' => '1,0000',
            'largura' => '2,0000',
            'altura' => '3,0000',
            'comprimento' => '4,0000',
            'ativo' => 1,
            'ncm' => '84713012',
            'cest' => '1234567',
            'codigo_anp' => null,
            'origem_mercadoria' => 0,
            'ex_tipi' => null,
            'codigo_beneficio_fiscal' => null,
            'cst_icms' => '00',
            'csosn' => null,
            'modalidade_bc_icms' => null,
            'aliquota_icms' => '18,00',
            'reducao_bc_icms' => '0,00',
            'mva_icms' => '0,00',
            'aliquota_icms_st' => '0,00',
            'aliquota_fcp' => '0,00',
            'aliquota_fcp_st' => '0,00',
            'cst_pis' => '01',
            'aliquota_pis' => '1,65',
            'base_calculo_pis' => '0,0000',
            'cst_cofins' => '01',
            'aliquota_cofins' => '7,60',
            'base_calculo_cofins' => '0,0000',
            'cst_ipi' => '50',
            'codigo_enquadramento_ipi' => null,
            'aliquota_ipi' => '0,00',
            'observacoes' => 'Obs',
        ], $override);
    }
}
