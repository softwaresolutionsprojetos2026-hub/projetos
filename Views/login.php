<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; height: 100vh; display: flex; align-items: center; }
        .card-login { width: 100%; max-width: 400px; border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card card-login mx-auto p-4 bg-white">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Acesso Restrito</h3>
                <p class="text-muted">Entre com suas credenciais<p>
            </div>
            
            <?php if(isset($_SESSION['erro_login'])): ?>
                <div class="alert alert-danger small"><?= $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?></div>
            <?php endif; ?>

            <form action="router.php" method="POST">
                <input type="hidden" name="acao" value="fazer_login">
                <div class="mb-3">
                    <label class="form-label small fw-bold">E-mail</label>
                    <input type="email" name="email" class="form-control" placeholder="exemplo@admin.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold">Senha</label>
                    <input type="password" name="senha" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Entrar no Painel</button>

                <!-- LINK DE CADASTRO -->
                <div class="text-center mt-3">
                    <span class="text-muted small">Não tem uma conta?</span>
                    <a href="index.php?rota=registrar" target="_blank" class="text-decoration-none small fw-bold text-primary ms-1">Cadastre-se aqui</a>
                </div>                

            </form>

            <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none small text-muted">← Voltar para a Vitrine</a>
            </div>

        </div>

    </div>

</body>
</html>