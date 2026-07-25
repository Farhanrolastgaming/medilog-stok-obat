<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

if (!file_exists('/tmp/bootstrap/cache/packages.php')) {
    @file_put_contents('/tmp/bootstrap/cache/packages.php', '<?php return [];');
}
if (!file_exists('/tmp/bootstrap/cache/services.php')) {
    @file_put_contents('/tmp/bootstrap/cache/services.php', '<?php return [];');
}

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo '<h1>Laravel Deployment Error Details</h1>';
    echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
    echo '<pre style="background:#f4f4f4;padding:15px;border-radius:5px;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
}
