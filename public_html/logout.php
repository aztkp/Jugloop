<?php

require_once('../config.php');
$user = new MyApp\User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $isValidate = $user->__validateToken();
  if ($isValidate) {
    $_SESSION = [];
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time() - 86400, '/');
    }
    session_destroy();
  }
}
goHome();
