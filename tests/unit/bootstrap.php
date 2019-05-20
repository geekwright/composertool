<?php
spl_autoload_register(function ($class) {
    $projectRoot = dirname(dirname(__DIR__)) . '/';
    require  $projectRoot . 'xoops_version.php';
    $prefix = $modversion['namespace'] . '\\';
    $base_dir = $projectRoot . 'src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});
require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
