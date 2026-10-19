<?php
declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $folders = ['Entities', 'DataBase', 'Exceptions', 'Repositories', 'Services','Utlis'];

    foreach ($folders as $folder) {
        $file = __DIR__ . "/{$folder}/{$class}.php";

        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});