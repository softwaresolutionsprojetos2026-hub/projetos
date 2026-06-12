<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<div class="content d-flex flex-column" style="height: calc(100vh - 56px); overflow: hidden;">
    
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show py-2 mb-3 small shadow-sm" role="alert">
            <?php foreach($_SESSION['msg'] as $m) echo htmlspecialchars($m); unset($_SESSION['msg']); ?>
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold text-dark m-0">Painel de Moderação Global</h3>
            <p class="text-muted small m-0">Gerencie e analise detalhadamente as postagens do sistema.</p>
        </div>
        <ul class="nav nav-tabs d-none" id="modTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="lista-tab" data-bs-toggle="tab" data-bs-target="#panel-lista" type="button" role="tab"></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="visao-tab" data-bs-toggle="tab" data-bs-target="#panel-visao" type="button" role="tab"></button>
            </li>
        </ul>
    </div>

    <div class="tab-content flex-grow-1 d-flex flex-column" id="modTabContent" style="overflow: hidden;">
        
        <div class="tab-pane fade show active flex-grow-1 d-flex flex-column h-100" id="panel-lista" role="tabpanel" style="overflow: hidden;">
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
                                <?php foreach($produtos_moderacao as $p): 
                                    // 🔍 BUSCA AS IMAGENS DESTE PRODUTO ESPECÍFICO PARA PASSAR AO JAVASCRIPT
                                    // Instanciamos o model para pegar as imagens vinculadas a este ID
                                    $prodModel = new Produto();
                                    $imagensProd = $prodModel->getImagens($p['id']);
                                    
                                    // Extrai apenas os caminhos das fotos e transforma em uma string separada por vírgulas
                                    $caminhosImagens = array_map(function($img) { return $img['caminho']; }, $imagensProd);
                                    $listaImagensJson = htmlspecialchars(json_encode($caminhosImagens));
                                ?>
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
                                            <div class="d-inline-flex gap-1">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary border-0 btn-ver-detalhes"
                                                        data-id="<?= $p['id'] ?>"
                                                        data-nome="<?= htmlspecialchars($p['nome']) ?>"
                                                        data-preco="R$ <?= number_format($p['preco'], 2, ',', '.') ?>"
                                                        data-categoria="<?= htmlspecialchars($p['categoria_nome']) ?>"
                                                        data-autor="<?= htmlspecialchars($p['autor_nome']) ?>"
                                                        data-imagens="<?= $listaImagensJson ?>">
                                                    <i class="fa-solid fa-eye me-1"></i> Ver
                                                </button>

                                                <a href="router.php?acao=excluir_produto&id=<?= $p['id'] ?>&origem=moderacao" 
                                                   class="btn btn-sm btn-outline-danger border-0" 
                                                   onclick="return confirm('Tem certeza que deseja remover esta postagem do sistema?');">
                                                    <i class="fa-solid fa-trash-can me-1"></i> Excluir
                                                </a>
                                            </div>
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

        <div class="tab-pane fade flex-grow-1 d-flex flex-column h-100" id="panel-visao" role="tabpanel" style="overflow-y: auto;">
            <div class="card border-0 shadow-sm p-4 mb-3" style="border-radius: 12px;">
                
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <button type="button" class="btn btn-sm btn-secondary px-3" id="btn-voltar-lista">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar para Lista
                    </button>
                    
                    <a href="#" id="link-excluir-atalho" class="btn btn-sm btn-danger px-3" onclick="return confirm('Tem certeza que deseja remover esta postagem definitivamente?');">
                        <i class="fa-solid fa-trash-can me-1"></i> Excluir esta Postagem
                    </a>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div id="wrapper-imagens-dinamicas" class="row g-2">
                            </div>
                    </div>
                    <div class="col-md-6 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-dark mb-2" id="view-categoria">Categoria</span>
                            <h2 class="fw-bold text-dark mb-1" id="view-nome">Nome do Produto</h2>
                            <h4 class="text-success fw-bold mb-3" id="view-preco">R$ 0,00</h4>
                            
                            <div class="card bg-light border-0 p-3 mb-3" style="border-radius: 8px;">
                                <p class="m-0 small text-muted"><strong>Metadados do Registro:</strong></p>
                                <hr class="my-2 opacity-10">
                                <p class="m-0 small text-dark"><strong>ID do Sistema:</strong> #<span id="view-id">0</span></p>
                                <p class="m-0 small text-dark"><strong>Autor da Postagem:</strong> <span id="view-autor">Nome</span></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnVer = document.querySelectorAll('.btn-ver-detalhes');
    const btnVoltar = document.getElementById('btn-voltar-lista');
    
    // Elementos da Aba de Visualização
    const txtId = document.getElementById('view-id');
    const txtNome = document.getElementById('view-nome');
    const txtPreco = document.getElementById('view-preco');
    const txtCategoria = document.getElementById('view-categoria');
    const txtAutor = document.getElementById('view-autor');
    const linkExcluir = document.getElementById('link-excluir-atalho');
    const wrapperImagens = document.getElementById('wrapper-imagens-dinamicas');

    const triggerVisao = new bootstrap.Tab(document.getElementById('visao-tab'));
    const triggerLista = new bootstrap.Tab(document.getElementById('lista-tab'));

    btnVer.forEach(botao => {
        botao.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nome = this.getAttribute('data-nome');
            const preco = this.getAttribute('data-preco');
            const categoria = this.getAttribute('data-categoria');
            const autor = this.getAttribute('data-autor');
            
            // Realiza o Parse seguro da String JSON de imagens
            const imagens = JSON.parse(this.getAttribute('data-imagens') || '[]');

            // Alimenta os campos de texto
            txtId.textContent = id;
            txtNome.textContent = nome;
            txtPreco.textContent = preco;
            txtCategoria.textContent = categoria;
            txtAutor.textContent = autor;

            // Limpa o container de fotos antigas para preparar a nova renderização
            wrapperImagens.innerHTML = '';

            if (imagens.length === 0) {
                // Caso o produto não tenha nenhuma foto cadastrada
                wrapperImagens.innerHTML = `
                    <div class="col-12">
                        <div class="p-4 bg-light rounded text-center border text-muted">
                            <i class="fa-solid fa-image-slash fa-2x mb-2 text-secondary"></i>
                            <p class="small m-0">Nenhuma imagem cadastrada para este produto.</p>
                        </div>
                    </div>`;
            } else {
                // 🛠️ RENDERIZA AS FOTOS REAIS ENCONTRADAS NA PASTA DO USUÁRIO
                imagens.forEach(caminho => {
                    const col = document.createElement('div');
                    // Se houver apenas 1 imagem ela ocupa a largura toda (col-12), se houver mais, divide em col-6
                    col.className = imagens.length === 1 ? 'col-12' : 'col-6';
                    
                    col.innerHTML = `
                        <div class="card h-100 border shadow-sm p-1 bg-white" style="border-radius: 8px; overflow:hidden;">
                            <img src="${caminho}" class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 200px;" alt="Foto do Produto" onerror="this.src='https://placehold.co/400x300?text=Erro+ao+Carregar'">
                        </div>
                    `;
                    wrapperImagens.appendChild(col);
                });
            }

            linkExcluir.setAttribute('href', `router.php?acao=excluir_produto&id=${id}&origem=moderacao`);
            triggerVisao.show();
        });
    });

    if (btnVoltar) {
        btnVoltar.addEventListener('click', function() {
            triggerLista.show();
        });
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>