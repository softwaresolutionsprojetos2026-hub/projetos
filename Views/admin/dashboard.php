<?php require_once __DIR__ . "/../includes/header.php"; ?>
<?php require_once __DIR__ . "/../includes/sidebar.php"; ?>

<!-- DIV CLASS CONTENT INÍCIO -->
<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark m-0">Dashboard Geral</h2>
        <span class="badge bg-dark p-2">Padrão MVC</span>
    </div>

    <!-- DIV CARDS INÍCIO -->
    <div class="row g-4 mb-4 mx-auto" style="max-width: 900px">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm p-4 text-white h-100 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">

                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <h6 class="text-uppercase fw-bold m-0" style="opacity: 0.8; font-size: 0.8rem;">Categorias</h6>
                        <h1 class="fw-bold display-6 my-2"><?= $totalCategorias ?></h1>
                    </div>

                    <div class="fs-1" style="opacity: 0.3;"><i class="fa-solid fa-folder-open"></i></div>

                </div>

                <div class="mt-3">
                    <a href="index.php?rota=admin/categorias" class="text-white text-decoration-none" style="font-size: 0.9rem;">Gerenciar <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
            </div>

        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 text-white h-100 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold m-0" style="opacity: 0.8; font-size: 0.8rem;">Produtos Cadastrados</h6>
                        <h1 class="fw-bold display-6 my-2"><?= $totalProdutos ?></h1>
                    </div>
                    <div class="fs-1" style="opacity: 0.3;"><i class="fa-solid fa-box"></i></div>
                </div>
                <div class="mt-3">
                    <a href="index.php?rota=admin/produtos" class="text-white text-decoration-none" style="font-size: 0.9rem;">Gerenciar <i class="fa-solid fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 text-white h-100 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold m-0" style="opacity: 0.8; font-size: 0.8rem;">Total de Imagens</h6>
                        <h1 class="fw-bold display-6 my-2"><?= $totalImagens ?></h1>
                    </div>
                    <div class="fs-1" style="opacity: 0.3;"><i class="fa-solid fa-images"></i></div>
                </div>
                <div class="mt-3">
                    <span class="text-white" style="font-size: 0.9rem; opacity: 0.9;"><i class="fa-solid fa-circle-check me-1"></i> Armazenamento ativo</span>
                </div>
            </div>
        </div>

        <!-- CARD USUÁRIOS INÍCIO -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 text-white h-100 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #f5af19 0%, #f12711 100%); border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-uppercase fw-bold m-0" style="opacity: 0.8; font-size: 0.8rem; letter-spacing: 0.5px;">Usuários</h6>
                        <h1 class="fw-bold display-6 my-2"><?= $totalUsuarios ?></h1>
                    </div>
                    <div class="fs-2" style="opacity: 0.3;"><i class="fa-solid fa-users-gear"></i></div>
                </div>
                <div class="mt-3">
                    <?php if (isset($_SESSION['usuario_email']) && $_SESSION['usuario_email'] !== 'visitante@teste.com'): ?>
                        <a href="index.php?rota=admin/usuarios" class="text-white text-decoration-none small" style="font-size: 0.85rem;">Gerenciar <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    <?php else: ?>
                        <span class="text-white small" style="font-size: 0.85rem; opacity: 0.9;"><i class="fa-solid fa-lock me-1"></i> Restrito</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>         
        <!-- CARD USUÁRIOS FIM -->        

    </div>
    <!-- DIV CARDS FIM -->

</div> <!-- DIV CLASS CONTENT FIM -->

<?php require_once __DIR__ . "/../includes/footer.php"; ?>