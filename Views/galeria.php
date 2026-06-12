<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitrine de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-brand fw-bold { letter-spacing: -0.5px; }
        /* Efeito de elevação suave nos cards ao passar o mouse */
        .card-produto { transition: transform 0.3s ease, box-shadow 0.3s ease; border: none; border-radius: 16px; overflow: hidden; }
        .card-produto:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important; }
        .img-wrapper { position: relative; width: 100%; height: 240px; overflow: hidden; background-color: #eaeaea; }
        .img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .card-produto:hover .img-wrapper img { transform: scale(1.05); }
        /* Filtro de categorias em formato de pílulas */
        .btn-filtro { border-radius: 30px; padding: 8px 20px; font-weight: 500; transition: all 0.2s ease; border: 1px solid #dee2e6; background: #fff; color: #495057; text-decoration: none; display: inline-block; }
        .btn-filtro:hover, .btn-filtro.active { background-color: #0d6efd; color: #fff; border-color: #0d6efd; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2); }
        .search-box { border-radius: 30px; padding-left: 20px; }
        .btn-search { border-radius: 30px; padding-left: 25px; padding-right: 25px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom sticky-top py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center text-dark" href="index.php">
                <i class="fa-solid fa-bag-shopping text-primary me-2 fs-3"></i>
                <span class="fw-bold fs-4">Vitrine<span class="text-primary">Media</span></span>
            </a>
            <div class="ms-auto">
                <a href="index.php?rota=admin/dashboard" class="btn btn-outline-dark px-4 rounded-pill fw-medium btn-sm">
                    <i class="fa-solid fa-lock me-2"></i>Painel Admin
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold text-dark mb-3">Explore Nossos Produtos</h2>
                <p class="text-muted mb-4">Utilize o campo de busca ou navegue pelas categorias para filtrar os itens em tempo real.</p>
                
                <form action="index.php" method="GET" class="d-flex shadow-sm rounded-pill p-1 bg-white mb-4 border">
                    <input type="hidden" name="rota" value="galeria">
                    <?php if(!empty($categoriaAtiva)): ?>
                        <input type="hidden" name="categoria" value="<?= $categoriaAtiva ?>">
                    <?php endif; ?>
                    
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="busca" class="form-control border-0 search-box shadow-none" placeholder="O que você está procurando hoje?..." value="<?= htmlspecialchars($busca) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-search fw-medium">Buscar</button>
                </form>

                <div class="d-flex gap-2 flex-wrap justify-content-center pt-2">
                    <a href="index.php?rota=galeria<?= !empty($busca) ? '&busca='.urlencode($busca) : '' ?>" class="btn-filtro <?= empty($categoriaAtiva) ? 'active' : '' ?>">
                        <i class="fa-solid fa-border-all me-1"></i> Todos
                    </a>
                    
                    <?php foreach($categorias as $cat): ?>
                        <?php 
                            // Monta o link preservando o termo que já foi buscado no input de texto
                            $link = "index.php?rota=galeria&categoria=" . $cat['id'];
                            if(!empty($busca)) $link .= "&busca=" . urlencode($busca);
                            $isActive = ($categoriaAtiva == $cat['id']) ? 'active' : '';
                        ?>
                        <a href="<?= $link ?>" class="btn-filtro <?= $isActive ?>">
                            <?= htmlspecialchars($cat['nome']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php if(!empty($busca) || !empty($categoriaAtiva)): ?>
            <div class="d-flex justify-content-between align-items-center mb-4 bg-light p-3 rounded-3 border">
                <span class="text-muted small">
                    Filtrando por: 
                    <?= !empty($busca) ? 'Texto: "<strong>'.htmlspecialchars($busca).'</strong>"' : '' ?>
                    <?= !empty($busca) && !empty($categoriaAtiva) ? ' + ' : '' ?>
                    <?= !empty($categoriaAtiva) ? 'Categoria Selecionada' : '' ?>
                </span>
                <a href="index.php?rota=galeria" class="btn btn-sm btn-link text-danger text-decoration-none p-0"><i class="fa-solid fa-circle-xmark me-1"></i>Limpar Filtros</a>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if(!empty($galeria)): ?>
                <?php foreach($galeria as $g): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 card-produto shadow-sm bg-white">

                            <div class="img-wrapper">
                                <?php if (!empty($g['caminho'])): ?>
                                    <img src="<?= BASE_URL . htmlspecialchars($g['caminho']) ?>" alt="<?= htmlspecialchars($g['produto_nome']) ?>" loading="lazy">
                                <?php else: ?>
                                    <img src="https://placehold.co/600x400/ececec/999999?text=Sem+Imagem" alt="Produto sem foto" loading="lazy">
                                <?php endif; ?>
                            </div>

                            <div class="card-body d-flex flex-column p-4">

                                <span class="badge bg-light text-primary border border-primary-subtle rounded-pill align-self-start px-2 py-1 small mb-2 fw-semibold">
                                    <?= htmlspecialchars($g['categoria_nome']) ?>
                                </span>

                                <h5 class="fw-bold text-dark fs-6 text-truncate mb-2" title="<?= htmlspecialchars($g['produto_nome']) ?>">
                                    <?= htmlspecialchars($g['produto_nome']) ?>
                                </h5>

                                <div class="mt-auto pt-3 border-top border-light d-flex justify-content-between align-items-center">

                                    <div>
                                        <span class="text-muted d-block small" style="font-size: 0.75rem;">Preço</span>
                                        <span class="text-success fw-bold fs-5">R$ <?= number_format($g['preco'], 2, ",", ".") ?></span>
                                    </div>

                                    <a href="index.php?rota=detalhe&id=<?= $g['produto_id'] ?>" class="btn btn-dark btn-sm rounded-pill px-3" title="Ver Detalhes">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="col-12 my-5 text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
                    <div class="text-muted display-4 mb-3"><i class="fa-regular fa-folder-open"></i></div>
                    <h4 class="fw-bold text-dark">Nenhum produto encontrado</h4>
                    <p class="text-muted">Tente mudar os termos da busca ou selecione outra categoria.</p>
                    <a href="index.php?rota=galeria" class="btn btn-primary rounded-pill px-4 mt-2">Ver Todos os Produtos</a>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>