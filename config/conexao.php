<?php
require_once __DIR__ . '/../Support/BootstrapConfig.php';

if (!function_exists('env_value')) {
    function env_value($key, $default = null) {
        return BootstrapConfig::envValue($key, $default);
    }
}

if (!function_exists('db_column_exists')) {
    function db_column_exists(PDO $pdo, $database, $table, $column) {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?"
        );
        $stmt->execute([$database, $table, $column]);
        return (int) $stmt->fetchColumn() > 0;
    }
}

if (!function_exists('db_index_exists')) {
    function db_index_exists(PDO $pdo, $database, $table, $index) {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND INDEX_NAME = ?"
        );
        $stmt->execute([$database, $table, $index]);
        return (int) $stmt->fetchColumn() > 0;
    }
}

if (!function_exists('db_foreign_key_exists')) {
    function db_foreign_key_exists(PDO $pdo, $database, $table, $constraint) {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'"
        );
        $stmt->execute([$database, $table, $constraint]);
        return (int) $stmt->fetchColumn() > 0;
    }
}

$databaseConfig = BootstrapConfig::databaseConfig();
$host = $databaseConfig['host'];
$port = $databaseConfig['port'];
$user = $databaseConfig['user'];
$pass = $databaseConfig['pass'];
$dbname = $databaseConfig['dbname'];
$appEnv = $databaseConfig['app_env'];

// Define dinamicamente a URL base do projeto para evitar imagens quebradas
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $hostName = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
    $dir = dirname($scriptName);
    $baseDir = ($dir === '/' || $dir === '\\') ? '' : $dir;
    // Remove subpastas de rotas se o script atual for o index.php
    $baseDir = preg_replace('/\/Views.*/', '', $baseDir);
    define('BASE_URL', $protocol . $hostName . $baseDir . '/');
}

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

    $pdo->exec("USE `$dbname`;");

    foreach (BootstrapConfig::createTableStatements() as $statement) {
        $pdo->exec($statement);
    }

    if (!db_column_exists($pdo, $dbname, 'usuarios', 'tipo_acesso')) {
        $pdo->exec(BootstrapConfig::compatibilityStatements()['usuarios.tipo_acesso']);
    }

    if (!db_column_exists($pdo, $dbname, 'usuarios', 'telefone')) {
        $pdo->exec(BootstrapConfig::compatibilityStatements()['usuarios.telefone']);
    }

    if (!db_column_exists($pdo, $dbname, 'produtos', 'usuario_id')) {
        $pdo->exec(BootstrapConfig::compatibilityStatements()['produtos.usuario_id']);
    }

    if (!db_index_exists($pdo, $dbname, 'produtos', 'idx_produtos_usuario')) {
        $pdo->exec(BootstrapConfig::compatibilityStatements()['produtos.idx_produtos_usuario']);
    }

    if (!db_foreign_key_exists($pdo, $dbname, 'produtos', 'fk_produtos_usuarios')) {
        $pdo->exec(BootstrapConfig::compatibilityStatements()['produtos.fk_produtos_usuarios']);
    }

    $checkUser = (int) $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    $seedUsers = BootstrapConfig::shouldSeedUsers();

    if ($checkUser === 0 && $seedUsers) {
        $stmt = $pdo->prepare(
            "INSERT INTO usuarios (nome, email, senha, tipo_acesso, telefone) VALUES (?, ?, ?, ?, ?)"
        );

        foreach (BootstrapConfig::bootstrapUsers() as $bootstrapUser) {
            $stmt->execute([
                $bootstrapUser['nome'],
                $bootstrapUser['email'],
                password_hash($bootstrapUser['senha'], PASSWORD_DEFAULT),
                $bootstrapUser['tipo_acesso'],
                $bootstrapUser['telefone'],
            ]);
        }
    }
    
} catch (PDOException $e) { 
    error_log('Erro de conexão/configuração do banco: ' . $e->getMessage());
    if ($appEnv === 'production') {
        die('Erro interno ao inicializar a aplicação.');
    }

    die("Erro de Conexão: " . $e->getMessage()); 
}