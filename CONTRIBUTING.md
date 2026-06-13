# Contribuição

Este projeto segue o projeto0 como fonte normativa. Antes de abrir PR:

- valide sintaxe PHP com `composer run lint`;
- documente impacto funcional ou operacional em `docs/`;
- registre desvios arquiteturais em `docs/adr/`;
- não versione segredos, dumps com dados reais ou credenciais bootstrap efetivas.

Regras de revisão:

- mutações administrativas devem validar autorização no servidor;
- mudanças de schema precisam manter compatibilidade com o bootstrap legado;
- alterações de deploy devem preservar banco interno e publicação apenas da borda HTTP.