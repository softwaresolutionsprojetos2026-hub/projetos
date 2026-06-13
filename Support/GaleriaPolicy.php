<?php

class GaleriaPolicy {

    public static function isMasterSession($session) {
        return isset($session['usuario_id']) && (int) $session['usuario_id'] === 1;
    }

    public static function isLimitedUser($userType) {
        return $userType === 'limitado';
    }

    public static function canManageProduct($ownerUserId, $currentUserId, $isMaster) {
        if ($isMaster) {
            return true;
        }

        return (int) $ownerUserId === (int) $currentUserId;
    }

    public static function countUploadedImages($imageNames) {
        if (!is_array($imageNames)) {
            return 0;
        }

        return count(array_filter($imageNames, static function ($name) {
            return trim((string) $name) !== '';
        }));
    }

    public static function hasImageUploadLimitExceeded($userType, $imageCount, $limit = 2) {
        return self::isLimitedUser($userType) && (int) $imageCount > (int) $limit;
    }

    public static function isAllowedImageFilename($filename) {
        $extension = strtolower(pathinfo((string) $filename, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
    }
}