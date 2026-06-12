<?php require_once __DIR__ . "/../includes/header.php"; ?>
<?php require_once __DIR__ . "/../includes/sidebar.php"; ?>

<div class="content">

    <div class="mb-4">
        <a href="index.php?rota=admin/produtos" class="btn btn-outline-secondary">
            ← Voltar para Lista
        </a>
    </div>

    <div class="card border-0 shadow-sm p-4">

        <h4 class="fw-bold mb-4 text-dark">
            Editar Produto: <?= htmlspecialchars($produto["nome"]) ?>
        </h4>

        <form action="router.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="acao" value="editar_produto">

            <input type="hidden" name="id" value="<?= $produto["id"] ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold">Nome do Produto</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($produto["nome"]) ?>" required>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Preço (R$)</label>
                        <input type="number" step="0.01" name="preco" class="form-control" value="<?= $produto["preco"] ?>" required>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">Categoria</label>

                        <select name="categoria_id" class="form-select" required>

                            <?php foreach($categorias as $c): ?>
                                <option value="<?= $c["id"] ?>" <?= $c["id"] == $produto["categoria_id"] ? "selected":"" ?>><?= htmlspecialchars($c["nome"]) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>

                    </div>

                </div>

            </div>

            <div class="mb-2">
                
                <label class="form-label fw-semibold text-primary">
                    Imagens Cadastradas Atualmente
                </label>
            </div>

            <div class="d-flex gap-3 flex-wrap mb-4 bg-light p-3 rounded border">

                <?php foreach($imagens as $img): ?>

                    <div class="position-relative bloco-img-salva" id="img-salva-<?= $img["id"] ?>" style="width:100px; height:80px;">

                        <img src="<?= BASE_URL . htmlspecialchars($img["caminho"]) ?>" class="rounded border shadow-sm w-100 h-100" style="object-fit:cover;">

                        <button type="button" class="btn btn-danger btn-sm btn-deletar-salva-js" data-id="<?= $img["id"] ?>" style="position: absolute; top: -5px; right: -5px; padding: 2px 6px; font-size: 0.7rem; border-radius: 50%;" title="Excluir imagem definitivamente">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>

                    </div>

                <?php endforeach; ?>
                
            </div>

            <div class="mb-3">

                <label class="form-label fw-semibold">
                    Substituir Imagens (Deixe vazio para manter as atuais)
                </label>

                <input type="file" name="imagens[]" class="form-control input-preview-js" multiple accept="image/*">
            </div>

            <div class="d-flex gap-2 flex-wrap mb-4 container-preview-js"></div>

            <button type="submit" class="btn btn-primary w-100">Atualizar Produto</button>

        </form>

    </div>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>