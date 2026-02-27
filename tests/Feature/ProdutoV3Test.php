<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ErpProduto;
use App\Models\ErpCategoria;
use App\Models\ErpMarca;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProdutoV3Test extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // ===========================================
    // TESTES DE LISTAGEM
    // ===========================================

    /** @test */
    public function test_listagem_retorna_html(): void
    {
        $response = $this->actingAs($this->user)->get('/produto');
        $response->assertStatus(200);
        $response->assertViewIs('pages.produto.index');
    }

    /** @test */
    public function test_dados_retorna_json_datatable(): void
    {
        ErpProduto::factory()->count(5)->create();

        $response = $this->actingAs($this->user)
            ->getJson('/produto/dados?draw=1&start=0&length=10');

        $response->assertStatus(200)
            ->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    }

    /** @test */
    public function test_dados_filtra_por_ativo(): void
    {
        ErpProduto::factory()->create(['ativo' => true, 'nome' => 'Produto Ativo']);
        ErpProduto::factory()->create(['ativo' => false, 'nome' => 'Produto Inativo']);

        $response = $this->actingAs($this->user)
            ->getJson('/produto/dados?draw=1&start=0&length=10&ativo=1');

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertCount(1, $data);
        $this->assertEquals('Produto Ativo', $data[0]['nome']);
    }

    // ===========================================
    // TESTES DE FORMULÁRIO
    // ===========================================

    /** @test */
    public function test_create_retorna_form(): void
    {
        $response = $this->actingAs($this->user)->get('/produto/novo');

        $response->assertStatus(200);
        $response->assertViewIs('pages.produto.form');
        $response->assertSee('Cadastrar Produto');
    }

    // ===========================================
    // TESTES DE CRIAÇÃO (STORE)
    // ===========================================

    /** @test */
    public function test_store_produto_valido(): void
    {
        $payload = $this->getValidPayload();

        $response = $this->actingAs($this->user)
            ->postJson('/produto', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'id']);

        $this->assertDatabaseHas('erp_produto', [
            'nome' => $payload['nome'],
            'codigo_sku' => $payload['codigo_sku'],
        ]);
    }

    /** @test */
    public function test_store_falha_sem_nome(): void
    {
        $payload = $this->getValidPayload();
        unset($payload['nome']);

        $response = $this->actingAs($this->user)
            ->postJson('/produto', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }

    /** @test */
    public function test_store_com_imagens(): void
    {
        Storage::fake('public');

        $payload = $this->getValidPayload();
        $payload['imagens'] = [
            UploadedFile::fake()->image('produto1.jpg', 800, 600),
            UploadedFile::fake()->image('produto2.png', 800, 600),
        ];
        $payload['imagem_principal'] = 'new:0';

        $response = $this->actingAs($this->user)
            ->postJson('/produto', $payload);

        $response->assertStatus(200);

        $produto = ErpProduto::where('codigo_sku', $payload['codigo_sku'])->first();
        $this->assertNotNull($produto);
        $this->assertCount(2, $produto->imagens);
    }

    /** @test */
    public function test_store_com_fornecedores(): void
    {
        $fornecedor = Cliente::factory()->create(['tipo' => 'FORNECEDOR']);

        $payload = $this->getValidPayload();
        $payload['fornecedores'] = [
            [
                'fornecedor_id' => $fornecedor->id,
                'codigo_fornecedor' => 'COD-001',
                'preco_fornecedor' => '150,00',
                'prazo_entrega_dias' => 7,
                'principal' => 1,
            ]
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/produto', $payload);

        $response->assertStatus(200);

        $produto = ErpProduto::where('codigo_sku', $payload['codigo_sku'])->first();
        $this->assertNotNull($produto);
        $this->assertCount(1, $produto->fornecedores);
        $this->assertEquals('COD-001', $produto->fornecedores->first()->pivot->codigo_fornecedor);
    }

    // ===========================================
    // TESTES DE ATUALIZAÇÃO
    // ===========================================

    /** @test */
    public function test_update_produto(): void
    {
        $produto = ErpProduto::factory()->create();

        $payload = $this->getValidPayload();
        $payload['nome'] = 'Nome Atualizado';
        $payload['_method'] = 'PUT';

        $response = $this->actingAs($this->user)
            ->putJson("/produto/{$produto->id}", $payload);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Produto atualizado com sucesso!']);

        $this->assertDatabaseHas('erp_produto', [
            'id' => $produto->id,
            'nome' => 'Nome Atualizado',
        ]);
    }

    /** @test */
    public function test_inativar_produto(): void
    {
        $produto = ErpProduto::factory()->create(['ativo' => true]);

        $response = $this->actingAs($this->user)
            ->putJson("/produto/{$produto->id}", [
                '_status_only' => 1,
                'ativo' => 0,
            ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Produto inativado com sucesso!']);

        $this->assertDatabaseHas('erp_produto', [
            'id' => $produto->id,
            'ativo' => false,
        ]);
    }

    /** @test */
    public function test_reativar_produto(): void
    {
        $produto = ErpProduto::factory()->create(['ativo' => false]);

        $response = $this->actingAs($this->user)
            ->putJson("/produto/{$produto->id}", [
                '_status_only' => 1,
                'ativo' => 1,
            ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Produto reativado com sucesso!']);

        $this->assertDatabaseHas('erp_produto', [
            'id' => $produto->id,
            'ativo' => true,
        ]);
    }

    // ===========================================
    // TESTES DE ROTA DELETE
    // ===========================================

    /** @test */
    public function test_sem_rota_delete(): void
    {
        $produto = ErpProduto::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson("/produto/{$produto->id}");

        $response->assertStatus(405);
    }

    // ===========================================
    // TESTES DA REFORMA TRIBUTÁRIA
    // ===========================================

    /** @test */
    public function test_campos_reforma_tributaria(): void
    {
        $payload = $this->getValidPayload();
        $payload['aliquota_ibs'] = '17,50';
        $payload['reducao_bc_ibs'] = '10,00';
        $payload['aliquota_cbs'] = '8,80';
        $payload['reducao_bc_cbs'] = '5,00';
        $payload['sujeito_imposto_seletivo'] = 1;
        $payload['aliquota_imposto_seletivo'] = '25,00';
        $payload['regime_tributario'] = 'LUCRO_REAL';

        $response = $this->actingAs($this->user)
            ->postJson('/produto', $payload);

        $response->assertStatus(200);

        $produto = ErpProduto::where('codigo_sku', $payload['codigo_sku'])->first();
        $this->assertNotNull($produto);
        $this->assertEquals(17.50, $produto->aliquota_ibs);
        $this->assertEquals(10.00, $produto->reducao_bc_ibs);
        $this->assertEquals(8.80, $produto->aliquota_cbs);
        $this->assertEquals(5.00, $produto->reducao_bc_cbs);
        $this->assertTrue((bool) $produto->sujeito_imposto_seletivo);
        $this->assertEquals(25.00, $produto->aliquota_imposto_seletivo);
        $this->assertEquals('LUCRO_REAL', $produto->regime_tributario);
    }

    // ===========================================
    // TESTES DE API - CATEGORIAS
    // ===========================================

    /** @test */
    public function test_listar_categorias(): void
    {
        ErpCategoria::factory()->count(3)->create(['ativo' => true]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/categorias');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function test_criar_categoria(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/categorias', [
                'nome' => 'Nova Categoria',
                'descricao' => 'Descrição da categoria',
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Categoria criada com sucesso!']);

        $this->assertDatabaseHas('erp_categorias', [
            'nome' => 'Nova Categoria',
        ]);
    }

    /** @test */
    public function test_criar_categoria_duplicada(): void
    {
        ErpCategoria::factory()->create(['nome' => 'Categoria Existente']);

        $response = $this->actingAs($this->user)
            ->postJson('/api/categorias', [
                'nome' => 'Categoria Existente',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }

    // ===========================================
    // TESTES DE API - MARCAS
    // ===========================================

    /** @test */
    public function test_listar_marcas(): void
    {
        ErpMarca::factory()->count(3)->create(['ativo' => true]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/marcas');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function test_criar_marca(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/marcas', [
                'nome' => 'Nova Marca',
            ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Marca criada com sucesso!']);

        $this->assertDatabaseHas('erp_marcas', [
            'nome' => 'Nova Marca',
        ]);
    }

    // ===========================================
    // TESTES DE API - FORNECEDORES
    // ===========================================

    /** @test */
    public function test_buscar_fornecedores(): void
    {
        Cliente::factory()->create([
            'tipo' => 'FORNECEDOR',
            'razao_social' => 'Fornecedor Teste ABC',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/fornecedores/buscar?q=Teste');

        $response->assertStatus(200)
            ->assertJsonStructure(['results']);
    }

    // ===========================================
    // HELPERS
    // ===========================================

    protected function getValidPayload(): array
    {
        return [
            'tipo' => 'PRODUTO',
            'nome' => 'Produto Teste ' . uniqid(),
            'codigo_sku' => 'SKU-' . uniqid(),
            'unidade_medida' => 'UN',
            'preco_custo' => '100,0000',
            'preco_venda' => '150,0000',
            'quantidade_estoque' => '50,0000',
            'estoque_minimo' => '10,0000',
            'origem_mercadoria' => 0,
            'ativo' => 1,
        ];
    }
}
