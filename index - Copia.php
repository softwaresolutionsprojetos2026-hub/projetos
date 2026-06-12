<?php session_start(); require_once "config/conexao.php"; $rota = isset($_GET["rota"]) ? $_GET["rota"] : "galeria"; switch ($rota) { 
    case "galeria": require_once "Controllers/ProdutoController.php"; (new ProdutoController())->indexPublico(); break; 
    case "detalhe": require_once "Controllers/ProdutoController.php"; (new ProdutoController())->getImagens(); (new ProdutoController())->detalhePublico(); break; 
    case "admin/dashboard": require_once "Controllers/DashboardController.php"; (new DashboardController())->index(); break; 
    case "admin/categorias": require_once "Controllers/CategoriaController.php"; (new CategoriaController())->index(); break; 
    case "admin/produtos": require_once "Controllers/ProdutoController.php"; (new ProdutoController())->indexAdmin(); break; 
    case "admin/produtos/editar": require_once "Controllers/ProdutoController.php"; (new ProdutoController())->editAdmin(); break; 
    default: http_response_code(404); echo "Página não encontrada!"; break; 
}