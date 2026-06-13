<?php

require_once __DIR__ . '/bootstrap.php';

return [
    'detecta master por sessao' => function () {
        assertTrue(GaleriaPolicy::isMasterSession(['usuario_id' => 1]), 'usuario 1 deve ser master');
        assertFalse(GaleriaPolicy::isMasterSession(['usuario_id' => 2]), 'usuario comum nao deve ser master');
        assertFalse(GaleriaPolicy::isMasterSession([]), 'sessao vazia nao deve ser master');
    },
    'permite gerenciamento por dono ou master' => function () {
        assertTrue(GaleriaPolicy::canManageProduct(7, 7, false), 'dono do produto deve poder gerenciar');
        assertTrue(GaleriaPolicy::canManageProduct(7, 3, true), 'master deve poder gerenciar qualquer produto');
        assertFalse(GaleriaPolicy::canManageProduct(7, 3, false), 'usuario sem posse nao deve gerenciar produto');
    },
    'conta apenas uploads informados' => function () {
        assertSameValue(0, GaleriaPolicy::countUploadedImages([]), 'lista vazia deve contar zero');
        assertSameValue(2, GaleriaPolicy::countUploadedImages(['foto1.jpg', '', 'foto2.png', '   ']), 'deve ignorar itens vazios');
    },
    'aplica limite de imagens apenas para usuario limitado' => function () {
        assertTrue(GaleriaPolicy::hasImageUploadLimitExceeded('limitado', 3), 'usuario limitado deve respeitar teto');
        assertFalse(GaleriaPolicy::hasImageUploadLimitExceeded('limitado', 2), 'limite exato nao deve falhar');
        assertFalse(GaleriaPolicy::hasImageUploadLimitExceeded('ilimitado', 99), 'usuario ilimitado nao deve sofrer bloqueio');
    },
    'valida extensoes de imagem permitidas' => function () {
        assertTrue(GaleriaPolicy::isAllowedImageFilename('foto.JPEG'), 'jpeg deve ser aceito sem sensibilidade a caixa');
        assertTrue(GaleriaPolicy::isAllowedImageFilename('banner.webp'), 'webp deve ser aceito');
        assertFalse(GaleriaPolicy::isAllowedImageFilename('shell.php'), 'php nao deve ser aceito');
        assertFalse(GaleriaPolicy::isAllowedImageFilename('arquivo-sem-extensao'), 'arquivo sem extensao nao deve ser aceito');
    },
];