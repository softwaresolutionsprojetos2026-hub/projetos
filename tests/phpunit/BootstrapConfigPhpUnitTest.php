<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Support/BootstrapConfig.php';

final class BootstrapConfigPhpUnitTest extends TestCase {

    public function testDatabaseConfigUsesDefaults(): void {
        $config = BootstrapConfig::databaseConfig([]);

        $this->assertSame('127.0.0.1', $config['host']);
        $this->assertSame(3306, $config['port']);
        $this->assertSame('root', $config['user']);
        $this->assertSame('', $config['pass']);
        $this->assertSame('sistema_galeria', $config['dbname']);
        $this->assertSame('production', $config['app_env']);
    }

    public function testBootstrapUsersRequiresExplicitEnablement(): void {
        $this->assertSame([], BootstrapConfig::bootstrapUsers([]));

        $users = BootstrapConfig::bootstrapUsers([
            'APP_BOOTSTRAP_SEED_USERS' => 'true',
            'APP_BOOTSTRAP_ADMIN_EMAIL' => 'admin@example.com',
            'APP_BOOTSTRAP_ADMIN_PASSWORD' => 'secret',
        ]);

        $this->assertCount(1, $users);
        $this->assertSame('Administrador', $users[0]['nome']);
        $this->assertSame('ilimitado', $users[0]['tipo_acesso']);
    }

    public function testSchemaStatementsExposeExpectedConstraints(): void {
        $statements = BootstrapConfig::createTableStatements();
        $compatibility = BootstrapConfig::compatibilityStatements();

        $this->assertArrayHasKey('produtos', $statements);
        $this->assertStringContainsString('idx_produtos_usuario', $statements['produtos']);
        $this->assertStringContainsString('fk_produtos_usuarios', $statements['produtos']);
        $this->assertArrayHasKey('produtos.fk_produtos_usuarios', $compatibility);
    }
}