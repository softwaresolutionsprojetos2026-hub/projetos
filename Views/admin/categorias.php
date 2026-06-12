<?php require_once __DIR__ . "/../includes/header.php"; ?>
<?php require_once __DIR__ . "/../includes/sidebar.php"; ?>
<div class="content">
    <?php if (isset($_SESSION["msg"])): ?><div class="alert alert-success alerta-sistema"><?php foreach($_SESSION["msg"] as $m) echo htmlspecialchars($m); unset($_SESSION["msg"]); ?></div><?php endif; ?>
    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-bold mb-3">Categorias Registradas</h4>
                <table class="table align-middle">
                    <thead><tr><th>ID</th><th>Nome</th><th>Ação</th></tr></thead>
                    <tbody>
                        <?php foreach($categorias as $c): ?>
                            <tr><td><?= $c["id"] ?></td><td><strong><?= htmlspecialchars($c["nome"]) ?></strong></td><td><a href="router.php?acao=excluir_categoria&id=<?= $c["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir apagará os produtos dela. Continuar?');"><i class="fa-solid fa-trash"></i></a></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4"><h5 class="fw-bold mb-3">Nova Categoria</h5><form action="router.php" method="POST"><input type="hidden" name="acao" value="cadastrar_categoria"><div class="mb-3"><input type="text" name="nome" class="form-control" placeholder="Nome" required></div><button type="submit" class="btn btn-primary w-100">Salvar</button></form></div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>