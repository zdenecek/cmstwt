<?php

use App\App;


spl_autoload_register(static function($class) {
    $path = explode('\\', $class);
    $name = array_pop($path);

    $absolute = implode(DIRECTORY_SEPARATOR, $path) . DIRECTORY_SEPARATOR . $name . '.php';
    if (empty($path)) {
        $absolute = $name . '.php';
    }

    if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $absolute)) {
        require_once __DIR__ . DIRECTORY_SEPARATOR . $absolute;
    }
});


$app = new App();
$app->run();