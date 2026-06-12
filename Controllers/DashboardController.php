<?php
require_once __DIR__ . "/../Models/Categoria.php"; 
require_once __DIR__ . "/../Models/Produto.php";
require_once __DIR__ . "/../Models/Usuario.php";
class DashboardController {
    public function index() {

        // Traz a conexão do banco de dados para o escopo do método
        global $pdo;        

        $totalCategorias = (new Categoria())->countAll();

        $prod = new Produto();
        $totalProdutos = $prod->countAll();

        $totalImagens = $prod->countImagens();

        $totalUsuarios = (new Usuario($pdo))->countAll();

        require_once __DIR__ . "/../Views/admin/dashboard.php";
    }
}