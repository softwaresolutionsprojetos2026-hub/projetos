<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Support/GaleriaPolicy.php';

final class GaleriaPolicyPhpUnitTest extends TestCase {

    public function testMasterSessionDetection(): void {
        $this->assertTrue(GaleriaPolicy::isMasterSession(['usuario_id' => 1]));
        $this->assertFalse(GaleriaPolicy::isMasterSession(['usuario_id' => 2]));
    }

    public function testOwnershipOrMasterCanManageProduct(): void {
        $this->assertTrue(GaleriaPolicy::canManageProduct(7, 7, false));
        $this->assertTrue(GaleriaPolicy::canManageProduct(7, 3, true));
        $this->assertFalse(GaleriaPolicy::canManageProduct(7, 3, false));
    }

    public function testUploadRules(): void {
        $this->assertSame(2, GaleriaPolicy::countUploadedImages(['a.jpg', '', 'b.png']));
        $this->assertTrue(GaleriaPolicy::hasImageUploadLimitExceeded('limitado', 3));
        $this->assertFalse(GaleriaPolicy::hasImageUploadLimitExceeded('ilimitado', 3));
        $this->assertTrue(GaleriaPolicy::isAllowedImageFilename('foto.webp'));
        $this->assertFalse(GaleriaPolicy::isAllowedImageFilename('shell.php'));
    }
}