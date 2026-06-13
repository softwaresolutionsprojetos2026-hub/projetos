# Padrões Workspace Agnósticos

Alinhado ao projeto0 `padroes_engenharia_v2.5.md`.

## Princípios aplicados neste repositório

- validação e autorização acontecem no servidor;
- segredos e credenciais ficam fora do código fonte;
- documentação operacional mínima vive em `docs/`;
- mudanças de deploy preservam isolamento do banco e rastreabilidade.

## Stack efetiva do produto

- PHP 8.1+ com PDO MySQL;
- aplicação MVC legada sem framework;
- Docker Swarm com Traefik na borda;
- MySQL 8 interno à stack.

## Desvios documentados

- o produto atual não usa Laravel/PostgreSQL, portanto as normas do projeto0 são aplicadas por equivalência arquitetural;
- autorização por recurso foi reforçada no código legado em vez de migrar a arquitetura de aplicação.

## Referências do projeto0

- arquitetura e separação de camadas: seção 2;
- validação e autorização no servidor: seção 3;
- segurança e segredos: seção 9;
- documentação obrigatória do produto: seção 22 e `docs/ESTRUTURA-DOCUMENTACAO-OBRIGATORIA.md`.