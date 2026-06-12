<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<div class="content d-flex flex-column" style="height: calc(100vh - 56px); overflow: hidden;">
    
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show py-2 mb-3 small shadow-sm" role="alert">
            <?php foreach($_SESSION['msg'] as $m) echo htmlspecialchars($m); unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <h3 class="fw-bold text-dark m-0">Painel de Moderação Global</h3>
        <p class="text-muted small m-0">Visualize e remova postagens de qualquer usuário do sistema.</p>
    </div>

    <div class="card border-0 shadow-sm flex-grow-1 d-flex flex-column mb-3" style="border-radius: 12px; overflow: hidden;">
        <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Categoria</th>
                        <th>Postado por</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($produtos_moderacao)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Nenhum produto cadastrado no sistema.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($produtos_moderacao as $p): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?= $p['id'] ?></td>
                                <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                                <td class="text-success fw-semibold">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['categoria_nome']) ?></span></td>
                                <td>
                                    <span class="text-primary fw-medium">
                                        <i class="fa-solid fa-user small me-1"></i> <?= htmlspecialchars($p['autor_nome']) ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="router.php?acao=excluir_produto&id=<?= $p['id'] ?>&origem=moderacao" 
                                       class="btn btn-sm btn-outline-danger border-0" 
                                       onclick="return confirm('Tem certeza que deseja remover esta postagem do sistema de forma permanente?');">
                                        <i class="fa-solid fa-trash-can me-1"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_paginas > 1): ?>
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                <nav aria-label="Navegação de páginas">
                    <ul class="pagination pagination-sm m-0 gap-1">
                        
                        <li class="page-item <?= ($pagina_atual <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link border-0 rounded" href="index.php?rota=admin/moderacao&pagina=<?= $pagina_atual - 1 ?>"><i class="fa-solid fa-chevron-left"></i></a>
                        </li>

                        <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                            <li class="page-item <?= ($pagina_atual == $i) ? 'active' : '' ?>">
                                <a class="page-link border-0 rounded px-3" href="index.php?rota=admin/moderacao&pagina=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($pagina_atual >= $total_paginas) ? 'disabled' : '' ?>">
                            <a class="page-link border-0 rounded" href="index.php?rota=admin/moderacao&pagina=<?= $pagina_atual + 1 ?>"><i class="fa-solid fa-chevron-right"></i></a>
                        </li>

                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>