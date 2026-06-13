# Onboarding

## Primeiros passos

1. Leia `PADROES-WORKSPACE-AGNOSTICOS.md`.
2. Exporte variáveis de ambiente a partir de `.env.example`.
3. Inicie a aplicação local com `php -S 0.0.0.0:8080`.
4. Se precisar de usuários iniciais, habilite `APP_BOOTSTRAP_SEED_USERS=true` com credenciais próprias.

## Pontos de atenção

- `router.php` processa mutações e deve continuar protegido por sessão.
- `config/conexao.php` mantém compatibilidade de schema legado.
- `uploads/` é o único diretório com necessidade de escrita.