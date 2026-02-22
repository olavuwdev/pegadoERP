# Reescrita do Spec `001` para Produto V2 (Laravel + Blade + jQuery)

## Resumo

Reescrever integralmente `specs/001-spec-produto.md` para virar uma especificação executável de Produto V2, alinhada ao projeto atual, mas com as decisões fechadas:

1. Reescrever o módulo de Produto do zero.
2. Migrar para novo modelo de dados (`erp_produto` e `erp_produto_imagem`), sem compatibilidade com `erp_produtos`.
3. Não permitir exclusão de produto (sem rota DELETE); usar `ativo` para inativação.
4. Listagem obrigatória com DataTables AJAX usando jQuery.

## Estrutura final do spec (seções que o arquivo deve conter)

1. Contexto do projeto e stack obrigatória.
2. Objetivo do módulo e escopo funcional.
3. Decisões de arquitetura já fixadas (sem alternativas).
4. Modelo de dados completo (DDL funcional).
5. Contratos HTTP (rotas web + endpoints JSON para DataTables e salvar).
6. Regras de negócio e validações (frontend e backend).
7. Fluxo de interface em wizard (jQuery) com comportamento por etapa.
8. Upload de múltiplas imagens e gestão de imagem principal.
9. Estratégia de inativação (sem exclusão).
10. Tratamento de erros e mensagens padrão.
11. Segurança e autorização mínima.
12. Critérios de aceite (DoD).
13. Plano de testes (Feature/Unit).
14. Fora de escopo e backlog fiscal futuro.

## Mudanças importantes em APIs/interfaces/tipos

1. Rotas obrigatórias no spec:
    - `GET /produto`
    - `GET /produto/novo`
    - `GET /produto/{produto}/editar`
    - `POST /produto`
    - `PUT /produto/{produto}`
    - `GET /produto/dados` (DataTables AJAX)
    - Sem `DELETE /produto/{produto}`.
2. Tabelas obrigatórias:
    - `erp_produto` (campos de cadastro, estoque, fiscal e `ativo`).
    - `erp_produto_imagem` (N:1 com produto, `imagem_principal`, ordenação e timestamps).
3. Interface JSON obrigatória:
    - `POST/PUT` retornam `{ message, id? }` e erros em formato de validação Laravel.
    - `GET /produto/dados` retorna `{ data: [...] }` compatível com DataTables.
4. Política de estado:
    - “Excluir” vira “Inativar”.
    - Produto inativo permanece listável via filtro e não deve aparecer como disponível para venda (regra de domínio descrita no spec).

## Detalhamento técnico que deve ser acrescentado no spec

1. Convenções de frontend:
    - JavaScript exclusivamente em jQuery.
    - Máscaras/validações client-side para NCM, CEST, GTIN, percentuais e decimais BR.
    - Wizard com 5 etapas e validação por etapa antes de avançar.
2. Listagem:
    - DataTables com paginação, busca global e filtros por `ativo`, `tipo`, `ncm`.
    - Ações por linha: editar e inativar/reativar.
3. Upload de imagens:
    - Múltiplos arquivos (`jpg`, `png`, `webp`), limite por arquivo e limite por produto.
    - Definição explícita de pasta de armazenamento e nomeação por UUID.
    - Regra de “uma única imagem principal por produto”.
4. Validação backend:
    - Regras Laravel detalhadas campo a campo.
    - Sanitização e normalização de decimais BR.
    - Transação envolvendo produto + imagens.
5. Observabilidade mínima:
    - Mensagens padronizadas para sucesso/erro.
    - Registro de falhas de upload/validação no log.

## Casos de teste e cenários obrigatórios no spec

1. Cadastro válido de produto com múltiplas imagens e imagem principal.
2. Falha de validação para NCM inválido, percentuais fora de faixa e preço negativo.
3. Edição de produto mantendo dados fiscais e alterando galeria.
4. Listagem DataTables com filtros e paginação retornando JSON no formato esperado.
5. Inativação e reativação de produto (sem remoção física).
6. Bloqueio de acesso sem autenticação (`auth` middleware).
7. Teste de integridade para garantir apenas uma imagem principal por produto.

## Assumptions e defaults explícitos

1. O spec deve assumir ruptura com a tabela antiga `erp_produtos` e não incluir migração de dados legados.
2. O módulo usa rotas em `/produto` (singular), para manter padrão de navegação atual.
3. Sem ACL por perfil nesta entrega; apenas autenticação `auth`.
4. Sem integração real com NF-e nesta fase; apenas campos e regras preparatórias.
5. Sem exclusão física e sem soft delete nesta versão; status é controlado por `ativo`.

## 3. Estrutura de Banco de Dados (Produto V2)

### 3.1 Tabela `erp_produto`

| Campo                    | Tipo                      | Nulo | Default   | Regra/Índice |
| ------------------------ | ------------------------- | ---- | --------- | ------------ |
| id                       | bigint unsigned PK        | Não  | auto      | PK           |
| tipo                     | enum('PRODUTO','SERVICO') | Não  | 'PRODUTO' | index        |
| nome                     | varchar(255)              | Não  | -         | index        |
| codigo_sku               | varchar(100)              | Não  | -         | unique       |
| codigo_barras            | varchar(50)               | Sim  | null      | index        |
| descricao                | text                      | Sim  | null      | -            |
| marca                    | varchar(150)              | Sim  | null      | -            |
| categoria_id             | bigint unsigned           | Sim  | null      | index        |
| preco_custo              | decimal(15,4)             | Não  | 0.0000    | check >= 0   |
| preco_venda              | decimal(15,4)             | Não  | 0.0000    | check >= 0   |
| quantidade_estoque       | decimal(15,4)             | Não  | 0.0000    | check >= 0   |
| estoque_minimo           | decimal(15,4)             | Não  | 0.0000    | check >= 0   |
| unidade_medida           | varchar(10)               | Não  | 'UN'      | -            |
| peso                     | decimal(10,4)             | Sim  | null      | check >= 0   |
| largura                  | decimal(10,4)             | Sim  | null      | check >= 0   |
| altura                   | decimal(10,4)             | Sim  | null      | check >= 0   |
| comprimento              | decimal(10,4)             | Sim  | null      | check >= 0   |
| ativo                    | boolean                   | Não  | true      | index        |
| ncm                      | char(8)                   | Sim  | null      | index        |
| cest                     | char(7)                   | Sim  | null      | -            |
| codigo_anp               | varchar(20)               | Sim  | null      | -            |
| origem_mercadoria        | tinyint unsigned          | Não  | 0         | -            |
| ex_tipi                  | varchar(5)                | Sim  | null      | -            |
| codigo_beneficio_fiscal  | varchar(20)               | Sim  | null      | -            |
| cst_icms                 | varchar(3)                | Sim  | null      | -            |
| csosn                    | varchar(3)                | Sim  | null      | -            |
| modalidade_bc_icms       | varchar(2)                | Sim  | null      | -            |
| aliquota_icms            | decimal(5,2)              | Sim  | null      | check 0..100 |
| reducao_bc_icms          | decimal(5,2)              | Sim  | null      | check 0..100 |
| mva_icms                 | decimal(5,2)              | Sim  | null      | check 0..100 |
| aliquota_icms_st         | decimal(5,2)              | Sim  | null      | check 0..100 |
| aliquota_fcp             | decimal(5,2)              | Sim  | null      | check 0..100 |
| aliquota_fcp_st          | decimal(5,2)              | Sim  | null      | check 0..100 |
| cst_pis                  | varchar(3)                | Sim  | null      | -            |
| aliquota_pis             | decimal(5,2)              | Sim  | null      | check 0..100 |
| base_calculo_pis         | decimal(15,4)             | Sim  | null      | check >= 0   |
| cst_cofins               | varchar(3)                | Sim  | null      | -            |
| aliquota_cofins          | decimal(5,2)              | Sim  | null      | check 0..100 |
| base_calculo_cofins      | decimal(15,4)             | Sim  | null      | check >= 0   |
| cst_ipi                  | varchar(3)                | Sim  | null      | -            |
| codigo_enquadramento_ipi | varchar(5)                | Sim  | null      | -            |
| aliquota_ipi             | decimal(5,2)              | Sim  | null      | check 0..100 |
| created_at               | timestamp                 | Não  | current   | -            |
| updated_at               | timestamp                 | Não  | current   | -            |

Índices adicionais:

- `idx_produto_filtros (ativo, tipo, ncm)`
- `idx_produto_busca (nome, codigo_sku, codigo_barras)`

### 3.2 Tabela `erp_produto_imagem`

| Campo            | Tipo               | Nulo | Default | Regra/Índice |
| ---------------- | ------------------ | ---- | ------- | ------------ |
| id               | bigint unsigned PK | Não  | auto    | PK           |
| produto_id       | bigint unsigned    | Não  | -       | FK + index   |
| caminho_arquivo  | varchar(255)       | Não  | -       | -            |
| nome_original    | varchar(255)       | Sim  | null    | -            |
| mime_type        | varchar(100)       | Não  | -       | -            |
| tamanho_bytes    | int unsigned       | Não  | 0       | -            |
| ordem            | smallint unsigned  | Não  | 0       | index        |
| imagem_principal | boolean            | Não  | false   | index        |
| created_at       | timestamp          | Não  | current | -            |
| updated_at       | timestamp          | Não  | current | -            |

Chaves e integridade:

- `foreign key (produto_id) references erp_produto(id) on delete cascade`
- Regra de domínio: somente 1 imagem principal por produto (garantir na camada de serviço ao salvar/editar).

### 3.3 Regras de persistência

- Não existe exclusão física/lógica de produto nesta versão.
- Controle de disponibilidade por `erp_produto.ativo`.
- Toda gravação de produto + imagens deve ocorrer em transação única.
