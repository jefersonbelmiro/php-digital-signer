<?php
// @codeCoverageIgnoreStart

if (file_exists("vendor/autoload.php")) {
  return require_once("vendor/autoload.php");
}

spl_autoload_register(function($className) {

    if (count(explode("\\", $className)) <= 1) {
      return false;
    }

    $classPath = str_replace("\\", '/', $className) . '.php';
    $path = __DIR__ . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR;
    
    if (file_exists($path . $classPath)) {
      return require_once $path . $classPath;
    }    
    
    return false;

}, true, true);

// @codeCoverageIgnoreEnd