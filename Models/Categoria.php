<?php
class Categoria {
    private $db;
    public function __construct() { global $pdo; $this->db = $pdo; }
    public function all($busca = "") {
        if ($busca !== "") { $stmt = $this->db->prepare("SELECT * FROM categorias WHERE nome LIKE ? ORDER BY id DESC"); $stmt->execute(["%$busca%"]); return $stmt->fetchAll(PDO::FETCH_ASSOC); }
        return $this->db->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($nome) { $stmt = $this->db->prepare("INSERT INTO categorias (nome) VALUES (?)"); return $stmt->execute([$nome]); }
    public function delete($id) { $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = ?"); return $stmt->execute([$id]); }
    public function countAll() { return (int)$this->db->query("SELECT COUNT(*) FROM categorias")->fetchColumn(); }
}