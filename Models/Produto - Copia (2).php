<?php

class Produto {

    private $db;

    public function __construct() {
        global $pdo; 
        $this->db = $pdo;
    }
    
    /**
     * LISTAGEM ADMINISTRATIVA (Com Isolamento de Dados)
     * Se não for o Admin Master (ID 1), o usuário só vê os seus próprios produtos.
     */
    public function allAdmin($busca = "", $cat_id = "", $usuario_id = null) {
        $sql = "SELECT p.*, c.nome as categoria_nome FROM produtos p 
                JOIN categorias c ON p.categoria_id = c.id 
                WHERE 1=1"; 
        
        $params = [];

        // Regra do Marketplace: Se não for o Admin Raiz, filtra pelo ID do logado
        if ($usuario_id !== null && $usuario_id != 1) {
            $sql .= " AND p.usuario_id = ?";
            $params[] = $usuario_id;
        }

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
    
    /**
     * LISTAGEM PÚBLICA (Vitrine/Galeria)
     * Utiliza LEFT JOIN para que produtos SEM imagem também apareçam na loja.
     */
    public function allPublic($busca = '', $cat_id = '') {
        $sql = "SELECT i.caminho, p.id as produto_id, p.nome as produto_nome, p.preco, c.nome as categoria_nome 
                FROM produtos p
                LEFT JOIN imagens i ON i.produto_id = p.id AND i.id = (SELECT MIN(id) FROM imagens WHERE produto_id = p.id)
                JOIN categorias c ON p.categoria_id = c.id 
                WHERE 1=1";
        
        $params = [];
        if ($busca !== '') { 
            $sql .= " AND (p.nome LIKE ? OR c.nome LIKE ?)"; 
            $params[] = "%$busca%"; 
            $params[] = "%$busca%"; 
        }
        if ($cat_id !== '') { 
            $sql .= " AND p.categoria_id = ?"; 
            $params[] = $cat_id; 
        }
        
        $sql .= " ORDER BY p.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function find($id) { 
        $stmt = $this->db->prepare("SELECT p.*, c.nome as categoria_nome FROM produtos p JOIN categorias c ON p.categoria_id = c.id WHERE p.id = ?"); 
        $stmt->execute([$id]); 
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    public function getImagens($produto_id) { 
        $stmt = $this->db->prepare("SELECT * FROM imagens WHERE produto_id = ? ORDER BY id ASC"); 
        $stmt->execute([$produto_id]); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    /**
     * CADASTRAR PRODUTO (Salva quem é o dono/criador)
     */
    public function create($categoria_id, $nome, $preco, $usuario_id = 1) { 
        $stmt = $this->db->prepare("INSERT INTO produtos (categoria_id, nome, preco, usuario_id) VALUES (?, ?, ?, ?)"); 
        $stmt->execute([$categoria_id, $nome, $preco, $usuario_id]); 
        return $this->db->lastInsertId(); 
    }

    public function addImagem($produto_id, $caminho) { 
        $stmt = $this->db->prepare("INSERT INTO imagens (produto_id, caminho) VALUES (?, ?)"); 
        return $stmt->execute([$produto_id, $caminho]); 
    }

    public function update($id, $categoria_id, $nome, $preco) { 
        $stmt = $this->db->prepare("UPDATE produtos SET categoria_id = ?, nome = ?, preco = ? WHERE id = ?"); 
        return $stmt->execute([$categoria_id, $nome, $preco, $id]); 
    }

    public function deleteImagens($produto_id) { 
        $stmt = $this->db->prepare("DELETE FROM imagens WHERE produto_id = ?"); 
        return $stmt->execute([$produto_id]); 
    }

    public function delete($id) { 
        $stmt = $this->db->prepare("DELETE FROM produtos WHERE id = ?"); 
        return $stmt->execute([$id]); 
    }

    /**
     * CONTAGEM DE PRODUTOS PARA A DASHBOARD (Com Isolamento)
     */
    public function countAll($usuario_id = null) { 
        $sql = "SELECT COUNT(*) FROM produtos";
        $params = [];

        if ($usuario_id !== null && $usuario_id != 1) {
            $sql .= " WHERE usuario_id = ?";
            $params[] = $usuario_id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn(); 
    }

    /**
     * CONTAGEM DE IMAGENS PARA A DASHBOARD (Com Isolamento)
     */
    public function countImagens($usuario_id = null) { 
        if ($usuario_id !== null && $usuario_id != 1) {
            $sql = "SELECT COUNT(i.id) FROM imagens i 
                    JOIN produtos p ON i.produto_id = p.id 
                    WHERE p.usuario_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
            return (int)$stmt->fetchColumn();
        }
        
        return (int)$this->db->query("SELECT COUNT(*) FROM imagens")->fetchColumn(); 
    }
}