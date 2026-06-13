<?php

$phpunitBinary = __DIR__ . '/../vendor/bin/phpunit';
$phpunitConfig = __DIR__ . '/../phpunit.xml';

if (is_file($phpunitBinary) && is_file($phpunitConfig)) {
    passthru(PHP_BINARY . ' ' . escapeshellarg($phpunitBinary) . ' --configuration ' . escapeshellarg($phpunitConfig), $exitCode);
    exit($exitCode);
}

$suites = [
    require __DIR__ . '/GaleriaPolicyTest.php',
    require __DIR__ . '/BootstrapConfigTest.php',
];

$failures = [];
$executed = 0;

foreach ($suites as $suite) {
    foreach ($suite as $name => $test) {
        $executed++;

        try {
            $test();
            echo '[PASS] ' . $name . PHP_EOL;
        } catch (Throwable $exception) {
            $failures[] = '[FAIL] ' . $name . ' - ' . $exception->getMessage();
            fwrite(STDERR, end($failures) . PHP_EOL);
        }
    }
}

echo 'Executados: ' . $executed . PHP_EOL;

if ($failures !== []) {
    echo 'Falhas: ' . count($failures) . PHP_EOL;
    exit(1);
}

echo 'Falhas: 0' . PHP_EOL;