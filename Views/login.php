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
                <p class="text-muted">Entre com suas credenciais aqui</p>
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

            <!-- ACESSO DE TESTE INÍCIO -->
            <div class="mt-4 p-3 bg-light rounded border border-dashed">
                
                <h6 class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-circle-info me-1 text-primary"></i> 
                    Credenciais de Teste do Login xxxxx:
                </h6>

                <!--
                <div class="small text-muted mb-1">
                    <strong>Administrador (Total):</strong><br>
                    Email: <code class="text-dark">admin@admin.com</code> | Senha: <code class="text-dark">admin123</code>
                </div> -->
                
                <div class="small text-muted">
                    <strong>Visitante (Limitado):</strong><br>
                    Email: <code class="text-dark">visitante@teste.com</code> | Senha: <code class="text-dark">visitante123</code>
                </div>

                                        <div class="mt-3 fade-in delay-3">
                                            <div class="text-center mb-2">
                                                <small class="text-muted font-weight-bold">Acesso de demonstração</small>
                                            </div>
                                            <div class="bg-light rounded p-2 text-center" style="border: 1px dashed #d1d3e2; cursor: pointer;" onclick="document.querySelector('[name=email]').value='hsrbsistemas@gmail.com'; document.querySelector('[name=senha]').value='123Mudar@';">
                                                <small class="d-block text-gray-600"><i class="fas fa-envelope mr-1"></i> teste@teste.com</small>
                                                <small class="d-block text-gray-600"><i class="fas fa-key mr-1"></i> 123Mudar@</small>
                                            </div>
                                        </div>                

            </div>

            <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none small text-muted">← Voltar para a Vitrine</a>
            </div>

        </div>
            <!-- ACESSO DE TESTE FIM  -->

        <!--    
            <div class="text-center mt-4">
                <a href="index.php" class="text-decoration-none small text-muted">← Voltar para a Vitrine</a>
            </div>

        </div> -->

    </div>

</body>
</html>