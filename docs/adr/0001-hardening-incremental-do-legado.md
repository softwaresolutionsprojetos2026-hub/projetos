# ADR 0001 - Hardening incremental do legado MVC

## Status

Aceito

## Contexto

O produto opera em PHP MVC legado, sem framework moderno, e precisava atender os controles mínimos do projeto0 sem uma reescrita imediata.

## Decisão

- manter a estrutura MVC atual no curto prazo;
- reforçar autenticação e autorização diretamente no backend existente;
- mover configuração sensível para variáveis de ambiente;
- complementar o repositório com documentação mínima obrigatória.

## Consequências

- menor risco operacional imediato;
- dívida técnica ainda existe e continua elegível para ADR futuro de migração;
- controles de segurança passam a depender menos de convenções de view e mais do servidor.