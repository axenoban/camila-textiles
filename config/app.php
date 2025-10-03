<?php
// config/app.php

if (!defined('BASE_URL')) {
    $projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'])) : '';

    $basePath = '';

    if ($documentRoot && strpos($projectRoot, $documentRoot) === 0) {
        $basePath = substr($projectRoot, strlen($documentRoot));
    }

    if (!$basePath) {
        $basePath = ($documentRoot && $projectRoot === $documentRoot)
            ? ''
            : '/' . trim(basename($projectRoot), '/');
    }

    define('BASE_URL', rtrim($basePath, '/') ?: '');
}
