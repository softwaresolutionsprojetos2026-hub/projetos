<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<div class="content d-flex flex-column" style="height: calc(100vh - 56px); overflow: hidden;">
    
    <?php if (isset($_SESSION['msg'])): ?>

        <div class="alert alert-success alerta-sistema py-2 mb-3 shadow-sm">
            <?php foreach($_SESSION['msg'] as $m) 
                echo htmlspecialchars($m); 
                unset($_SESSION['msg']); 
            ?>
        </div>

    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="fw-bold text-dark m-0">Gerenciamento de Produtos</h3>

        <div class="d-flex gap-2">

            <button id="btn-aba-lista" class="btn btn-primary btn-sm px-3 fw-semibold active" data-bs-toggle="tab" data-bs-target="#list-p" disabled>
                <i class="fa-solid fa-list me-1"></i> Lista
            </button>

            <button id="btn-aba-cadastro" class="btn btn-success btn-sm px-3 fw-semibold" data-bs-toggle="tab" data-bs-target="#cad-p">
                <i class="fa-solid fa-plus me-1"></i> Novo Produto
            </button>

        </div>

    </div>

    <div class="tab-content flex-grow-1" style="overflow: hidden;">
        
        <div class="tab-pane fade show active h-100" id="list-p">

            <div class="card border-0 shadow-sm h-100 d-flex flex-column" style="border-radius: 12px;">

                <div class="table-responsive flex-grow-1" style="overflow-y: auto; max-height: 100%;">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light sticky-top">

                            <tr>

                                <th class="ps-4" style="width: 80px;">ID</th>
                                <th>Nome do Produto</th>
                                <th>Preço</th>
                                <th>Categoria</th>
                                <th class="text-end pe-4" style="width: 200px;">Ações</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if(!empty($produtos)): ?>

                                <?php foreach($produtos as $p): ?>

                                    <tr>

                                        <td class="ps-4 text-muted">#<?= $p['id'] ?></td>
                                        <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                                        <td class="text-success fw-bold">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                                        <td><span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded border border-secondary-subtle"><?= htmlspecialchars($p['categoria_nome']) ?></span></td>
                                        <td class="text-end pe-4">
                                            <a href="index.php?rota=admin/produtos/editar&id=<?= $p['id'] ?>" class="btn btn-sm btn-warning text-white px-2 me-1 shadow-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="router.php?acao=excluir_produto&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger px-2 shadow-sm" onclick="return confirm('Deseja excluir este produto?');"><i class="fa-solid fa-trash"></i></a>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>

                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-box-open d-block fs-2 mb-2 opacity-50"></i>
                                        Nenhum produto cadastrado no momento.
                                    </td>

                               <!-- endforeach; ?> -->

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="tab-pane fade h-100" id="cad-p" style="overflow-y: auto;">

            <div class="card border-0 shadow-sm p-4" style="border-radius: 12px;">

                <h5 class="fw-bold mb-3 text-secondary">
                    <i class="fa-solid fa-circle-plus text-success me-2"></i>
                    Inserir Informações do Produto
                </h5>

                <form action="router.php" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="acao" value="cadastrar_produto">

                    <div class="mb-3">

                        <label class="form-label small fw-bold text-muted">Nome do Produto</label>
                        <input type="text" name="nome" 
                               class="form-control" placeholder="Ex: Smartphone XYZ" required>
                    </div>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Preço (R$)</label>
                                <input type="number" step="0.01" name="preco" class="form-control" placeholder="0.00" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="mb-3">

                                <label class="form-label small fw-bold text-muted">Categoria Vinculada</label>

                                <select name="categoria_id" class="form-select" required>
                                    <option value="">Selecione uma categoria...</option>
                                    <?php foreach($categorias as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>

                        </div>

                    </div>

                    <!-- UPLOAD DAS IMAGENS -->
                    <div class="mb-3">

                        <!-- SE FOR VISITANTE, LIMITA O UPLOAD PARA 1 IMAGEM -->                    
                        <?php if ($_SESSION['usuario_email'] === 'visitante@teste.com'): ?>
                            
                            <label class="form-label small fw-bold text-danger">Imagem de Apresentação (Limite: 1 foto para teste)</label>
                            <input type="file" name="imagens[]" class="form-control input-preview-js" accept="image/*" required>

                        <?php else: ?>

                            <label class="form-label small fw-bold text-primary">Imagens de Apresentação</label>
                            <input type="file" name="imagens[]" class="form-control input-preview-js" multiple accept="image/*" required>

                        <?php endif; ?>

                    </div>

                    <!-- PREVIEW DAS IMAGENS -->
                    <div class="d-flex gap-2 flex-wrap mb-4 container-preview-js"></div>

                    <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                        <i class="fa-solid fa-floppy-disk me-1"></i> 
                        Gravar Novo Produto
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>