<?php

class BootstrapConfig {

    public static function envValue($key, $default = null, $source = null) {
        if (is_array($source)) {
            $value = array_key_exists($key, $source) ? $source[$key] : false;
        } else {
            $value = getenv($key);
        }

        if ($value === false || $value === '') {
            return $default;
        }

        return $value;
    }

    public static function databaseConfig($source = null) {
        return [
            'host' => self::envValue('DB_HOST', '127.0.0.1', $source),
            'port' => (int) self::envValue('DB_PORT', '3306', $source),
            'user' => self::envValue('DB_USER', 'root', $source),
            'pass' => self::envValue('DB_PASSWORD', '', $source),
            'dbname' => self::envValue('DB_NAME', 'sistema_galeria', $source),
            'app_env' => self::envValue('APP_ENV', 'production', $source),
        ];
    }

    public static function shouldSeedUsers($source = null) {
        return filter_var(self::envValue('APP_BOOTSTRAP_SEED_USERS', '0', $source), FILTER_VALIDATE_BOOLEAN);
    }

    public static function bootstrapUsers($source = null) {
        if (!self::shouldSeedUsers($source)) {
            return [];
        }

        $users = [];
        $adminEmail = self::envValue('APP_BOOTSTRAP_ADMIN_EMAIL', null, $source);
        $adminPassword = self::envValue('APP_BOOTSTRAP_ADMIN_PASSWORD', null, $source);
        if ($adminEmail && $adminPassword) {
            $users[] = [
                'nome' => 'Administrador',
                'email' => $adminEmail,
                'senha' => $adminPassword,
                'tipo_acesso' => 'ilimitado',
                'telefone' => null,
            ];
        }

        $visitorEmail = self::envValue('APP_BOOTSTRAP_VISITOR_EMAIL', null, $source);
        $visitorPassword = self::envValue('APP_BOOTSTRAP_VISITOR_PASSWORD', null, $source);
        if ($visitorEmail && $visitorPassword) {
            $users[] = [
                'nome' => 'Visitante de Teste',
                'email' => $visitorEmail,
                'senha' => $visitorPassword,
                'tipo_acesso' => 'limitado',
                'telefone' => null,
            ];
        }

        return $users;
    }

    public static function createTableStatements() {
        return [
            'usuarios' => "CREATE TABLE IF NOT EXISTS usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                senha VARCHAR(255) NOT NULL,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                tipo_acesso ENUM('limitado','ilimitado') NOT NULL DEFAULT 'limitado',
                telefone VARCHAR(20) DEFAULT NULL
                ) ENGINE=InnoDB;",
            'categorias' => "CREATE TABLE IF NOT EXISTS categorias (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB;",
            'produtos' => "CREATE TABLE IF NOT EXISTS produtos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                categoria_id INT NOT NULL,
                nome VARCHAR(150) NOT NULL,
                preco DECIMAL(10,2) NOT NULL,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                usuario_id INT NOT NULL DEFAULT 1,
                INDEX idx_produtos_categoria (categoria_id),
                INDEX idx_produtos_usuario (usuario_id),
                CONSTRAINT fk_produtos_categorias FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE,
                CONSTRAINT fk_produtos_usuarios FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
                ) ENGINE=InnoDB;",
            'imagens' => "CREATE TABLE IF NOT EXISTS imagens (
                id INT AUTO_INCREMENT PRIMARY KEY,
                produto_id INT NOT NULL,
                caminho VARCHAR(255) NOT NULL,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_imagens_produto (produto_id),
                CONSTRAINT fk_imagens_produtos FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
                ) ENGINE=InnoDB;",
        ];
    }

    public static function compatibilityStatements() {
        return [
            'usuarios.tipo_acesso' => "ALTER TABLE usuarios ADD COLUMN tipo_acesso ENUM('limitado','ilimitado') NOT NULL DEFAULT 'limitado' AFTER criado_em",
            'usuarios.telefone' => "ALTER TABLE usuarios ADD COLUMN telefone VARCHAR(20) DEFAULT NULL AFTER tipo_acesso",
            'produtos.usuario_id' => "ALTER TABLE produtos ADD COLUMN usuario_id INT NOT NULL DEFAULT 1 AFTER criado_em",
            'produtos.idx_produtos_usuario' => "ALTER TABLE produtos ADD INDEX idx_produtos_usuario (usuario_id)",
            'produtos.fk_produtos_usuarios' => "ALTER TABLE produtos ADD CONSTRAINT fk_produtos_usuarios FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE",
        ];
    }
}