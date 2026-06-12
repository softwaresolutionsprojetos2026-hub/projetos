<?php

class Produto {

    private $db;

    public function __construct() {

        global $pdo; 
        $this->db = $pdo;

    }
    
    public function allAdmin($busca = "", $cat_id = "") {

        $sql = "SELECT p.*, c.nome as categoria_nome FROM produtos p JOIN categorias c ON p.categoria_id = c.id WHERE 1=1"; 
        
        $params = [];

        if ($busca !== "") { 
            $sql .= " AND p.nome LIKE ?"; 
            $params[] = "%$busca%"; 
        }

        if ($cat_id !== "") { 
            $sql .= " AND p.categoria_id = ?"; 
            $params[] = $cat_id; 
        }

        $stmt = $this->db->prepare($sql . " ORDER BY p.id DESC"); 
        
        $stmt->execute($params); 
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    
    public function allPublic($busca = '', $cat_id = '') {
        // Mudamos de JOIN para LEFT JOIN na tabela de imagens
        $sql = "SELECT i.caminho, p.id as produto_id, p.nome as produto_nome, p.preco, c.nome as categoria_nome 
                FROM produtos p
                LEFT JOIN imagens i ON i.produto_id = p.id AND i.id = (SELECT MIN(id) FROM imagens WHERE produto_id = p.id)
                JOIN categorias c ON p.categoria_id = c.id 
                WHERE 1=1";
        
        $params = [];
        if ($busca !== '') { 
            $sql .= " AND (p.nome LIKE ? OR c.nome LIKE ?)"; 
            $params[] = "%$busca%"; $params[] = "%$busca%"; 
        }
        if ($cat_id !== '') { $sql .= " AND p.categoria_id = ?"; $params[] = $cat_id; }
        
        $sql .= " ORDER BY p.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function find($id) { $stmt = $this->db->prepare("SELECT p.*, c.nome as categoria_nome FROM produtos p JOIN categorias c ON p.categoria_id = c.id WHERE p.id = ?"); $stmt->execute([$id]); return $stmt->fetch(PDO::FETCH_ASSOC); }
    public function getImagens($produto_id) { $stmt = $this->db->prepare("SELECT * FROM imagens WHERE produto_id = ? ORDER BY id ASC"); $stmt->execute([$produto_id]); return $stmt->fetchAll(PDO::FETCH_ASSOC); }
    public function create($categoria_id, $nome, $preco) { $stmt = $this->db->prepare("INSERT INTO produtos (categoria_id, nome, preco) VALUES (?, ?, ?)"); $stmt->execute([$categoria_id, $nome, $preco]); return $this->db->lastInsertId(); }
    public function addImagem($produto_id, $caminho) { $stmt = $this->db->prepare("INSERT INTO imagens (produto_id, caminho) VALUES (?, ?)"); return $stmt->execute([$produto_id, $caminho]); }
    public function update($id, $categoria_id, $nome, $preco) { $stmt = $this->db->prepare("UPDATE produtos SET categoria_id = ?, nome = ?, preco = ? WHERE id = ?"); return $stmt->execute([$categoria_id, $nome, $preco, $id]); }
    public function deleteImagens($produto_id) { $stmt = $this->db->prepare("DELETE FROM imagens WHERE produto_id = ?"); return $stmt->execute([$produto_id]); }
    public function delete($id) { $stmt = $this->db->prepare("DELETE FROM produtos WHERE id = ?"); return $stmt->execute([$id]); }
    public function countAll() { return (int)$this->db->query("SELECT COUNT(*) FROM produtos")->fetchColumn(); }
    public function countImagens() { return (int)$this->db->query("SELECT COUNT(*) FROM imagens")->fetchColumn(); }
}