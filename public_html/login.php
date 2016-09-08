<?php

  require_once('../config.php');

  $twitterLogin = new MyApp\TwitterLogin();

  try {
    $twitterLogin->login();
  } catch (Exception $e) {
    echo $e->getMessage();
    exit;
  }
