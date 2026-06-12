<?php
require_once __DIR__ . "/../Models/Categoria.php";
class CategoriaController {
    private $model;
    public function __construct() { $this->model = new Categoria(); }
    public function index() { $busca = isset($_GET["busca_cat"]) ? trim($_GET["busca_cat"]) : ""; $categorias = $this->model->all($busca); require_once __DIR__ . "/../Views/admin/categorias.php"; }
    public function store() { $nome = filter_input(INPUT_POST, "nome", FILTER_SANITIZE_SPECIAL_CHARS); if (!empty($nome)) { $this->model->create($nome); $_SESSION["msg"] = ["Sucesso: Categoria registrada!"]; } header("Location: index.php?rota=admin/categorias"); exit; }
    public function delete() { global $pdo; $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT); if ($id) { $stmt = $pdo->prepare("SELECT i.caminho FROM imagens i JOIN produtos p ON i.produto_id = p.id WHERE p.categoria_id = ?"); $stmt->execute([$id]); foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $img) { if (file_exists($img["caminho"])) { @unlink($img["caminho"]); } } $this->model->delete($id); $_SESSION["msg"] = ["Aviso: Categoria removida!"]; } header("Location: index.php?rota=admin/categorias"); exit; }
}