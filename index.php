<?php 
// Inicia a sessão global para que o PHP consiga lembrar se o usuário está logado
session_start(); 

// Carrega o banco de dados e as configurações de URL
require_once "config/conexao.php"; 

// Captura a rota da URL. Se não houver nenhuma (ex: apenas entrar no site), o padrão é a 'galeria'
$rota = isset($_GET["rota"]) ? $_GET["rota"] : "galeria"; 

switch ($rota) { 
    
    // ==========================================
    // ROTAS PÚBLICAS (Qualquer visitante acessa)
    // ==========================================
    
    case "galeria": 
        require_once "Controllers/ProdutoController.php"; 
        (new ProdutoController())->indexPublico(); 
        break; 
        
    case "detalhe": 
        require_once "Controllers/ProdutoController.php"; 
        (new ProdutoController())->detalhePublico(); 
        break; 
        
    case "login":
        require_once "Controllers/AuthController.php";
        (new AuthController())->index();
        break;

    case "registrar":
        require_once "Views/registrar.php";
        break;        

    case "logout":
        require_once "Controllers/AuthController.php";
        (new AuthController())->logout();
        break;
        
    // ==========================================
    // ROTAS ADMINISTRATIVAS (Requerem Login)
    // ==========================================
    
    case "admin/dashboard": 
        require_once "Controllers/AuthController.php";
        AuthController::check(); // 🛡️ Barreira de segurança: Se não estiver logado, vai para o login
        
        require_once "Controllers/DashboardController.php"; 
        (new DashboardController())->index(); 
        break; 
        
    case "admin/categorias": 
        require_once "Controllers/AuthController.php";
        AuthController::check(); // 🛡️ Protegido
        
        require_once "Controllers/CategoriaController.php"; 
        (new CategoriaController())->index(); 
        break; 
        
    case "admin/produtos": 
        require_once "Controllers/AuthController.php";
        AuthController::check(); // 🛡️ Protegido
        
        require_once "Controllers/ProdutoController.php"; 
        (new ProdutoController())->indexAdmin(); 
        break; 
        
    case "admin/produtos/editar": 
        require_once "Controllers/AuthController.php";
        AuthController::check(); // 🛡️ Protegido
        
        require_once "Controllers/ProdutoController.php"; 
        (new ProdutoController())->editAdmin(); 
        break;
        
    case "admin/usuarios": 
        require_once "Controllers/AuthController.php";
        AuthController::check();
        
        // Bloqueio estrito: Se NÃO for o ID 1 (Admin Master), barra o acesso
        if ($_SESSION['usuario_id'] != 1) {
            $_SESSION['msg'] = ["Acesso negado: Apenas o administrador tem permissão para esta área."];
            header("Location: index.php?rota=admin/dashboard");
            exit;
        }
        
        require_once "Controllers/UsuarioController.php"; 
        (new UsuarioController())->indexAdmin(); 
        break;
        
    case "admin/moderacao":
        require_once "Controllers/AuthController.php";
        AuthController::check();
        
        // Segurança: Barra qualquer um que não seja o Admin Master
        if ($_SESSION['usuario_id'] != 1) {
            $_SESSION['msg'] = ["Acesso negado."];
            header("Location: index.php?rota=admin/dashboard");
            exit;
        }

        // INCLUSÃO DO MODEL (Isso corrige o erro Fatal!)
        require_once "Models/Produto.php";

        // --- LÓGICA DE PAGINAÇÃO ---
        $itens_por_pagina = 10;
        $pagina_atual = isset($_GET['pagina']) ? filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) : 1;
        if (!$pagina_atual || $pagina_atual < 1) { $pagina_atual = 1; }
        
        $offset = ($pagina_atual - 1) * $itens_por_pagina;

        // Conta quantos produtos existem no total bruto do sistema
        $total_registros = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
        $total_paginas = ceil($total_registros / $itens_por_pagina);

        // Busca os produtos da página atual unindo com categorias e autores (usuarios)
        $stmtMod = $pdo->prepare("
            SELECT p.*, c.nome as categoria_nome, u.nome as autor_nome 
            FROM produtos p 
            JOIN categorias c ON p.categoria_id = c.id
            JOIN usuarios u ON p.usuario_id = u.id
            ORDER BY p.id DESC 
            LIMIT ? OFFSET ?
        ");
        // No PDO, para usar LIMIT/OFFSET com prepare, precisamos forçar o tipo como INTEGER
        $stmtMod->bindValue(1, $itens_por_pagina, PDO::PARAM_INT);
        $stmtMod->bindValue(2, $offset, PDO::PARAM_INT);
        $stmtMod->execute();
        $produtos_moderacao = $stmtMod->fetchAll(PDO::FETCH_ASSOC);

        // Carrega a nova View especializada
        require_once "Views/admin/moderacao.php";
    break;        
        
    // ==========================================
    // ROTA PADRÃO (Página 404)
    // ==========================================
    default: 
        http_response_code(404); 
        echo "Página não encontrada!"; 
        break; 
}