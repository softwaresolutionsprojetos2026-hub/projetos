# Projeto Galeria MVC v3

Aplicação web em PHP MVC para vitrine de produtos com painel administrativo, upload de imagens e moderação. Este repositório foi ajustado para seguir o mínimo operacional e documental exigido pelo projeto0, com endurecimento de autenticação, autorização por recurso e configuração externa por variáveis de ambiente.

## Visão geral

O projeto possui dois contextos principais:

- Público: galeria, detalhe, busca e filtro por categoria.
- Administrativo: dashboard, categorias, produtos, moderação e gestão de usuários.

Estrutura principal:

- `index.php`: roteamento das páginas.
- `router.php`: processamento de ações e formulários.
- `Controllers/`: orquestração de fluxo.
- `Models/`: acesso a dados via PDO.
- `Views/`: interface pública e administrativa.
- `config/conexao.php`: conexão, criação de schema e compatibilidade de colunas.
- `docs/`: documentação mínima alinhada ao projeto0.

## Regras de acesso

- Todo usuário autenticado pode acessar dashboard, categorias e produtos.
- Apenas o administrador master (`usuarios.id = 1`) pode acessar usuários e moderação global.
- Edição, exclusão e remoção de imagens de produto agora exigem posse do recurso ou perfil master no servidor.

## Requisitos

- PHP 8.1+.
- Extensão PDO MySQL habilitada.
- MySQL 8+.
- Permissão de escrita em `uploads/`.

## Configuração por ambiente

O projeto não depende mais de credenciais fixas em código. Use as variáveis abaixo em desenvolvimento ou produção:

```env
APP_ENV=local
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=sistema_galeria
DB_USER=galeria
DB_PASSWORD=troque_esta_senha
APP_BOOTSTRAP_SEED_USERS=true
APP_BOOTSTRAP_ADMIN_EMAIL=admin@example.com
APP_BOOTSTRAP_ADMIN_PASSWORD=troque_esta_senha
APP_BOOTSTRAP_VISITOR_EMAIL=visitante@example.com
APP_BOOTSTRAP_VISITOR_PASSWORD=troque_esta_senha
```

O bootstrap de usuários só acontece quando `APP_BOOTSTRAP_SEED_USERS=true` e as credenciais iniciais são fornecidas por ambiente.

## Execução local

1. Exporte as variáveis de ambiente usando o modelo em `.env.example`.
2. Garanta permissão de escrita em `uploads/`.
3. Inicie o servidor embutido:

```bash
cd /srv/sistemas/projetos
set -a && . ./.env.example && set +a
php -S 0.0.0.0:8080
```

4. Acesse:

- Público: `http://localhost:8080/index.php`
- Login: `http://localhost:8080/index.php?rota=login`

## Testes

- sem Composer local: `php tests/run.php`
- com Composer local e dependências instaladas: `composer run test` ou `composer run test:phpunit`

O runner em `tests/run.php` usa PHPUnit automaticamente quando `vendor/bin/phpunit` estiver disponível.

## Banco de dados

Schema principal:

- `usuarios`
- `categorias`
- `produtos`
- `imagens`

O arquivo `config/conexao.php` cria tabelas ausentes e adiciona colunas/relacionamentos necessários quando detecta schema legado.

## Rotas principais

Páginas públicas:

- `index.php?rota=galeria`
- `index.php?rota=detalhe&id={id}`
- `index.php?rota=login`
- `index.php?rota=registrar`
- `index.php?rota=logout`

Páginas administrativas:

- `index.php?rota=admin/dashboard`
- `index.php?rota=admin/categorias`
- `index.php?rota=admin/produtos`
- `index.php?rota=admin/produtos/editar&id={id}`
- `index.php?rota=admin/usuarios`
- `index.php?rota=admin/moderacao`

Ações em `router.php`:

- `fazer_login`
- `registrar_visitante`
- `cadastrar_categoria`
- `excluir_categoria`
- `cadastrar_produto`
- `editar_produto`
- `excluir_produto`
- `excluir_imagem_avulsa`
- `salvar_usuario`
- `editar_usuario`
- `excluir_usuario`
- `alternar_limite`

## Docker Swarm

Arquivos de referência:

- `/srv/swarm/galeria-fred.yml`
- `/srv/swarm/galeria-fred.env.example`

Passos:

```bash
cp /srv/swarm/galeria-fred.env.example /srv/swarm/galeria-fred.env
set -a && . /srv/swarm/galeria-fred.env && set +a
docker stack deploy -c /srv/swarm/galeria-fred.yml galeria-fred
```

Observações:

- O MySQL permanece apenas na rede interna da stack.
- A aplicação é publicada via Traefik com HTTPS.
- Uploads persistem em volume dedicado.

## Documentação associada

- `PADROES-WORKSPACE-AGNOSTICOS.md`: espelho curto das normas adotadas.
- `docs/README.md`: índice da documentação do produto.
- `docs/adr/`: decisões de arquitetura.
- `docs/slo/README.md`: SLO inicial e justificativa.
- `docs/sbom/README.md`: plano de SBOM.

## Segurança operacional

- Não versione segredos reais.
- Use `PHP_DISPLAY_ERRORS=0` em produção.
- Restrinja escrita ao diretório `uploads/`.
- Revise credenciais bootstrap após o primeiro acesso.
