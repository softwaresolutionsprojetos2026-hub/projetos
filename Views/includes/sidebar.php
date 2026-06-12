<div class="sidebar">

    <div class="p-3 border-bottom border-secondary text-center">
        <h4 class="text-white fw-bold m-0">Admin MVC</h4>
    </div>
    
    <nav class="mt-3">
        
        <a href="index.php?rota=admin/dashboard" class="nav-link">
            <i class="fa-solid fa-chart-pie me-2"></i> 
            Dashboard
        </a>
        
        <a href="index.php?rota=admin/categorias" class="nav-link">
            <i class="fa-solid fa-folder me-2"></i> 
            Categorias
        </a>
        
        <a href="index.php?rota=admin/produtos" class="nav-link">
            <i class="fa-solid fa-box me-2"></i> 
            Produtos
        </a>
        
        <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == 1): ?>
            <a href="index.php?rota=admin/usuarios" class="nav-link <?= ($rota == 'admin/usuarios') ? 'active' : '' ?>">
                <i class="fa-solid fa-users-gear me-2"></i> Usuários
            </a>
        <?php endif; ?>

        <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == 1): ?>
            <a href="index.php?rota=admin/moderacao" class="nav-link <?= ($rota == 'admin/moderacao') ? 'active' : '' ?>">
                <i class="fa-solid fa-shield-halved me-2"></i> Moderação de Itens
            </a>
        <?php endif; ?>        

        <a href="index.php" class="nav-link text-info">
            <i class="fa-solid fa-globe me-2"></i> 
            Ver Site Público
        </a>

    </nav>

</div>