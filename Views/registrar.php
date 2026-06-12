<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta - Galeria MVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-register { width: 100%; max-width: 450px; border: none; border-radius: 15px; }
    </style>
</head>
<body>

<div class="card card-register shadow-sm p-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-dark m-0">Criar Nova Conta</h3>
        <p class="text-muted small">Cadastre-se para testar o sistema com limitações</p>
    </div>

    <form action="router.php" method="POST">
        <input type="hidden" name="acao" value="registrar_visitante">
        
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted">Nome Completo</label>
            <input type="text" name="nome" class="form-control" placeholder="Seu nome" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted">E-mail</label>
            <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label text-dark fw-medium">Telefone / WhatsApp</label>
            <div class="input-group">
                <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-phone"></i></span>
                <input type="text" 
                    name="telefone" 
                    id="telefone" 
                    class="form-control" 
                    placeholder="(11) 99999-9999"
                    maxlength="15" 
                    onkeyup="mascaraTelefone(this)" 
                    required>
            </div>
            <div class="form-text text-muted">Insira com o DDD. Os compradores entrarão em contato por este número.</div>
        </div>        
        
        <div class="mb-4">
            <label class="form-label small fw-bold text-muted">Senha</label>
            <input type="password" name="senha" class="form-control" placeholder="Mínimo 6 caracteres" required>
        </div>
        
        <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
            <i class="fa-solid fa-user-plus me-1"></i> Finalizar Cadastro
        </button>
    </form>
</div>

</body>
</html>

<script>
function mascaraTelefone(input) {
    let v = input.value.replace(/\D/g, ""); // Remove tudo que não for número
    
    if (v.length > 0) {
        v = "(" + v;
    }
    if (v.length > 3) {
        v = v.substring(0, 3) + ") " + v.substring(3);
    }
    if (v.length > 10) {
        v = v.substring(0, 10) + "-" + v.substring(10, 14);
    }
    
    input.value = v;
}
</script>