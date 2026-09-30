<?php
spl_autoload_register(function ($class_name) {
    $directories = ['models/', 'controllers/', ''];
    foreach ($directories as $dir) {
        $file = __DIR__ . '/' . $dir . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
?>