<?php

spl_autoload_register(function($class) {
  $prefix = 'MyApp\\';

  if (strpos($class, $prefix) === 0) {
    $className = substr($class, strlen($prefix));
    $classFilePath = __DIR__ . '/Controller/' . $className . '.php';

    if (file_exists($classFilePath)) {
      require $classFilePath;
    } else {
      echo 'No such class,' . $className;
      exit;
    }
  }
});
