<?php

require_once __DIR__ . '/bootstrap.php';

return [
    'usa defaults de banco quando ambiente nao informa valores' => function () {
        $config = BootstrapConfig::databaseConfig([]);

        assertSameValue('127.0.0.1', $config['host'], 'host default incorreto');
        assertSameValue(3306, $config['port'], 'porta default incorreta');
        assertSameValue('root', $config['user'], 'usuario default incorreto');
        assertSameValue('', $config['pass'], 'senha default incorreta');
        assertSameValue('sistema_galeria', $config['dbname'], 'database default incorreto');
        assertSameValue('production', $config['app_env'], 'app env default incorreto');
    },
    'respeita overrides de ambiente para banco' => function () {
        $config = BootstrapConfig::databaseConfig([
            'DB_HOST' => 'galeria-mysql',
            'DB_PORT' => '3307',
            'DB_USER' => 'galeria',
            'DB_PASSWORD' => 'segredo',
            'DB_NAME' => 'galeria_teste',
            'APP_ENV' => 'local',
        ]);

        assertSameValue('galeria-mysql', $config['host'], 'host override incorreto');
        assertSameValue(3307, $config['port'], 'porta override incorreta');
        assertSameValue('galeria', $config['user'], 'usuario override incorreto');
        assertSameValue('segredo', $config['pass'], 'senha override incorreta');
        assertSameValue('galeria_teste', $config['dbname'], 'database override incorreto');
        assertSameValue('local', $config['app_env'], 'app env override incorreto');
    },
    'gera usuarios bootstrap apenas quando habilitado e completo' => function () {
        $users = BootstrapConfig::bootstrapUsers([
            'APP_BOOTSTRAP_SEED_USERS' => 'true',
            'APP_BOOTSTRAP_ADMIN_EMAIL' => 'admin@example.com',
            'APP_BOOTSTRAP_ADMIN_PASSWORD' => 'admin-secret',
            'APP_BOOTSTRAP_VISITOR_EMAIL' => 'visitante@example.com',
            'APP_BOOTSTRAP_VISITOR_PASSWORD' => 'visitante-secret',
        ]);

        assertSameValue(2, count($users), 'deve gerar admin e visitante');
        assertSameValue('Administrador', $users[0]['nome'], 'primeiro bootstrap deve ser admin');
        assertSameValue('ilimitado', $users[0]['tipo_acesso'], 'admin deve ser ilimitado');
        assertSameValue('Visitante de Teste', $users[1]['nome'], 'segundo bootstrap deve ser visitante');
        assertSameValue('limitado', $users[1]['tipo_acesso'], 'visitante deve ser limitado');
    },
    'nao gera usuarios bootstrap quando desabilitado ou incompleto' => function () {
        assertSameValue([], BootstrapConfig::bootstrapUsers([]), 'nao deve gerar usuarios por padrao');

        $users = BootstrapConfig::bootstrapUsers([
            'APP_BOOTSTRAP_SEED_USERS' => '1',
            'APP_BOOTSTRAP_ADMIN_EMAIL' => 'admin@example.com',
            'APP_BOOTSTRAP_ADMIN_PASSWORD' => '',
        ]);

        assertSameValue(0, count($users), 'credenciais incompletas nao devem gerar bootstrap');
    },
    'expõe statements esperados para schema e compatibilidade' => function () {
        $createStatements = BootstrapConfig::createTableStatements();
        $compatibilityStatements = BootstrapConfig::compatibilityStatements();

        assertTrue(isset($createStatements['usuarios']), 'schema deve incluir usuarios');
        assertTrue(strpos($createStatements['produtos'], 'usuario_id INT NOT NULL DEFAULT 1') !== false, 'produtos deve incluir usuario_id');
        assertTrue(strpos($createStatements['imagens'], 'fk_imagens_produtos') !== false, 'imagens deve incluir foreign key');
        assertTrue(isset($compatibilityStatements['usuarios.tipo_acesso']), 'compat deve incluir tipo_acesso');
        assertTrue(isset($compatibilityStatements['produtos.fk_produtos_usuarios']), 'compat deve incluir fk de usuario');
    },
];