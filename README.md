# Projeto Galeria MVC v3

Aplicacao web em PHP (padrao MVC) para vitrine/galeria de produtos com painel administrativo, controle de usuarios e upload de imagens por produto.

## Visao Geral

A aplicacao possui dois contextos principais:

- Publico: vitrine de produtos, busca, filtro por categoria e pagina de detalhe.
- Administrativo: dashboard, categorias, produtos, moderacao e usuarios (com regras por perfil).

Arquitetura principal:

- `index.php`: roteamento de paginas (`rota`).
- `router.php`: processamento de formularios e acoes (`acao`).
- `Controllers/`: regras de fluxo.
- `Models/`: acesso a dados.
- `Views/`: interface publica e administrativa.
- `config/conexao.php`: conexao PDO + bootstrap inicial de banco/tabelas.

## Funcionalidades

- Cadastro, edicao e exclusao de categorias.
- Cadastro, edicao e exclusao de produtos.
- Upload de multiplas imagens por produto.
- Armazenamento de imagens em pasta por usuario (`uploads/{usuario_id}/`).
- Busca de produtos por nome e filtro por categoria.
- Pagina de detalhe com dados do autor (nome, email, telefone).
- Login, logout e registro de visitante.
- Gestao de usuarios (somente admin master).
- Moderacao com paginacao (somente admin master).

## Regras de Acesso

- Qualquer usuario autenticado acessa painel admin basico.
- Apenas usuario com `id = 1` acessa:
  - `admin/usuarios`
  - `admin/moderacao`
  - operacoes sensiveis de usuarios (criar, editar, excluir, alternar limite)

## Requisitos

- PHP 8.1+ (recomendado 8.3)
- Extensao PDO MySQL habilitada
- MySQL 8+
- Permissao de escrita na pasta `uploads/`

## Estrutura de Banco

Banco padrao: `sistema_galeria`

Tabelas principais:

- `usuarios`
- `categorias`
- `produtos`
- `imagens`

Observacao:

- O arquivo `config/conexao.php` cria o banco e tabelas automaticamente se nao existirem.
- Tambem cria usuarios iniciais quando a tabela `usuarios` esta vazia.

## Credenciais Iniciais

Criadas automaticamente no primeiro boot (quando nao existem usuarios):

- Administrador
  - Email: `admin@admin.com`
  - Senha: `admin123`
- Visitante de teste
  - Email: `visitante@teste.com`
  - Senha: `visitante123`

Importante:

- Altere as credenciais padrao em ambiente real.

## Como Executar Localmente

1. Ajuste a conexao em `config/conexao.php`:
   - `host`
   - `user`
   - `pass`
   - `dbname`
2. Garanta permissao de escrita em `uploads/`.
3. Inicie um servidor PHP na raiz do projeto:

```bash
cd /srv/sistemas/projeto_galeria_mvc_v3
php -S 0.0.0.0:8080
```

4. Acesse:

- Publico: `http://localhost:8080/index.php`
- Login: `http://localhost:8080/index.php?rota=login`

## Rotas de Pagina (`index.php`)

Publicas:

- `index.php?rota=galeria`
- `index.php?rota=detalhe&id={id}`
- `index.php?rota=login`
- `index.php?rota=registrar`
- `index.php?rota=logout`

Administrativas (requer login):

- `index.php?rota=admin/dashboard`
- `index.php?rota=admin/categorias`
- `index.php?rota=admin/produtos`
- `index.php?rota=admin/produtos/editar&id={id}`
- `index.php?rota=admin/usuarios` (apenas `id=1`)
- `index.php?rota=admin/moderacao` (apenas `id=1`)

## Acoes de Formulario (`router.php`)

- `fazer_login`
- `registrar_visitante`
- `cadastrar_categoria`
- `excluir_categoria`
- `cadastrar_produto`
- `editar_produto`
- `excluir_produto`
- `excluir_imagem_avulsa` (retorna JSON)
- `salvar_usuario` (admin master)
- `editar_usuario` (admin master)
- `excluir_usuario` (admin master)
- `alternar_limite` (admin master)

## Deploy em Docker Swarm

Arquivos de referencia no workspace:

- `/srv/swarm/galeria-fred.yml`
- `/srv/swarm/galeria-fred.env`

Passos basicos:

1. Defina variaveis de ambiente no arquivo `.env` da stack:

```env
MYSQL_ROOT_PASSWORD=troque_esta_senha
MYSQL_DATABASE=sistema_galeria
MYSQL_USER=galeria
MYSQL_PASSWORD=troque_esta_senha
```

2. Carregue variaveis e aplique a stack:

```bash
set -a && . /srv/swarm/galeria-fred.env && set +a
docker stack deploy -c /srv/swarm/galeria-fred.yml galeria-fred
```

3. Acompanhe logs:

```bash
docker service logs galeria-fred_app -f
docker service logs galeria-fred_mysql -f
```

Observacoes de infraestrutura:

- Banco MySQL nao publica porta externamente.
- Aplicacao publica via Traefik com HTTPS.
- Uploads persistidos em volume/pasta dedicada.

## Seguranca e Boas Praticas

- Nunca versione senhas reais em arquivos `.env`.
- Troque imediatamente credenciais padrao.
- Restrinja permissao de escrita apenas para `uploads/`.
- Mantenha `PHP_DISPLAY_ERRORS=0` em producao.

## Problemas Comuns

- Erro de conexao com banco:
  - Verifique host e credenciais em `config/conexao.php`.
- Imagem nao aparece:
  - Verifique permissao da pasta `uploads/`.
  - Confirme se o caminho foi salvo na tabela `imagens`.
- Redirecionamento para login:
  - Sessao expirada ou usuario sem permissao para a rota.

## Licenca

Defina a licenca do projeto (ex.: MIT) conforme a politica da equipe.
