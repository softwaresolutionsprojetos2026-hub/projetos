<?php

require_once __DIR__ . '/../Support/GaleriaPolicy.php';
require_once __DIR__ . '/../Support/BootstrapConfig.php';

function assertTrue($condition, $message) {
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function assertFalse($condition, $message) {
    assertTrue(!$condition, $message);
}

function assertSameValue($expected, $actual, $message) {
    if ($expected !== $actual) {
        throw new RuntimeException($message . ' Expected: ' . var_export($expected, true) . ' Actual: ' . var_export($actual, true));
    }
}