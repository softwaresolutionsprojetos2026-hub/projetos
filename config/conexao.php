<?php
$host = "galeria-mysql"; 
$user = "galeria"; 
$pass = "senha@1234@2026"; 
$dbname = "sistema_galeria";

// Define dinamicamente a URL base do projeto para evitar imagens quebradas
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $dir = dirname($scriptName);
    $baseDir = ($dir === '/' || $dir === '\\') ? '' : $dir;
    // Remove subpastas de rotas se o script atual for o index.php
    $baseDir = preg_replace('/\/Views.*/', '', $baseDir);
    define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . $baseDir . '/');
}

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

    $pdo->exec("USE `$dbname`;");

    // TABELA USUÁRIOS
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                senha VARCHAR(255) NOT NULL,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB;"
              );
              
    // Verifica se existe usuário, se não, cria o Admin padrão
    $checkUser = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

    if ($checkUser == 0) {

        // 1. Criar Administrador Raiz    
        $senhaPadrao = password_hash('admin123', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        
        $stmt->execute(['Administrador', 'admin@admin.com', $senhaPadrao]);

        // 2. Criar Visitante para Testes (NOVO)
        $senhaVisitante = password_hash('visitante123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");        
        $stmt->execute(['Visitante de Teste', 'visitante@teste.com', $senhaVisitante]);        
    }              

    // TABELA CATEGORIAS
    $pdo->exec("CREATE TABLE IF NOT EXISTS categorias (
                id INT AUTO_INCREMENT PRIMARY KEY, 
                nome VARCHAR(100) NOT NULL, 
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB;"
              );
    
    // TABELA PRODUTOS          
    $pdo->exec("CREATE TABLE IF NOT EXISTS produtos (id INT AUTO_INCREMENT PRIMARY KEY, categoria_id INT NOT NULL, nome VARCHAR(150) NOT NULL, preco DECIMAL(10,2) NOT NULL, criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE) ENGINE=InnoDB;");

    // TABELA IMAGENS
    $pdo->exec("CREATE TABLE IF NOT EXISTS imagens (id INT AUTO_INCREMENT PRIMARY KEY, produto_id INT NOT NULL, caminho VARCHAR(255) NOT NULL, criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE) ENGINE=InnoDB;");
    
} catch (PDOException $e) { 
    die("Erro de Conexão: " . $e->getMessage()); 
}