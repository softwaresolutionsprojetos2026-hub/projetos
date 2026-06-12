<?php
class Usuario {
    
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM usuarios ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($nome, $email, $senha) {
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        return $stmt->execute([$nome, $email, $hash]);
    }

    public function delete($id) {
        // Impede que o usuário logado se delete ou delete o ID 1 (Admin Raiz)
        if ($id == 1 || $id == $_SESSION['usuario_id']) return false;
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countAll() { 
        return (int)$this->db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn(); 
    }

}