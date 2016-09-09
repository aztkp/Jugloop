<?php
  require_once('../../../config.php');

  $twitterLogin = new MyApp\TwitterLogin();
  $user = new MyApp\User();
  $postClass = new MyApp\Post();
  $stamp = new MyApp\Stamp();

  if ($twitterLogin->isLoggedIn()) {
    $me = $_SESSION['me'];
    $juggler = $user->getUserFromId($me->id);
    $posts = $postClass->getPosts($me->id);
    $info = $postClass->getInfoFromId($me->id);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isValidate = $user->__validateToken();
    if ($isValidate) {
      if ($_POST['status'] === "update") {
        $stamp->createCigarStamp($_POST);
      } elseif ($_POST['status'] === "delete") {
        $stamp->deleteCigarStamp($_POST);
      }
    header('Location:' . $_POST['trick_id'] );
    }
  }
