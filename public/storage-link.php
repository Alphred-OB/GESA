<?php

// One-off helper to create the Laravel public storage symlink on shared hosting.
// Usage:
//   1. Upload this file to your Laravel project's public/ directory.
//   2. Visit it in a browser: https://your-domain.com/storage-link.php
//   3. After it reports success, DELETE this file.

header('Content-Type: text/plain');

$publicDir = __DIR__;
$projectRoot = realpath($publicDir . DIRECTORY_SEPARATOR . '..');
$target = $projectRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public';
$link = $publicDir . DIRECTORY_SEPARATOR . 'storage';

echo "Laravel storage link helper\n";
echo "-------------------------\n";
echo "Project root:  {$projectRoot}\n";
echo "Target (storage): {$target}\n";
echo "Link (public/storage): {$link}\n\n";

if ($projectRoot === false) {
    echo "[ERROR] Could not resolve project root path.\n";
    exit(1);
}

if (!is_dir($target)) {
    echo "[ERROR] Target directory does not exist: {$target}\n";
    exit(1);
}

if (is_link($link)) {
    echo "[OK] public/storage already exists and is a symlink. Nothing to do.\n";
    exit(0);
}

if (file_exists($link) && !is_link($link)) {
    echo "[ERROR] A non-symlink already exists at public/storage.\n";
    echo "        Please delete or rename that folder first in cPanel, then run this script again.\n";
    exit(1);
}

try {
    if (@symlink($target, $link)) {
        echo "[OK] Symlink created successfully.\n";
        echo "public/storage -> {$target}\n";
        echo "You can now delete this file (storage-link.php).\n";
        exit(0);
    }

    $error = error_get_last();
    $msg = $error['message'] ?? 'Unknown error';
    echo "[ERROR] symlink() failed: {$msg}\n";
    echo "Your hosting provider may not allow symlinks from PHP.\n";
    exit(1);
} catch (Throwable $e) {
    echo "[ERROR] Exception while creating symlink: {$e->getMessage()}\n";
    echo "Your hosting provider may not allow symlinks from PHP.\n";
    exit(1);
}
