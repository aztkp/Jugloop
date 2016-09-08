<?php
  require_once('../../../config.php');

  $twitterLogin = new MyApp\TwitterLogin();
  $user = new MyApp\User();
  $postClass = new MyApp\Post();

  if ($twitterLogin->isLoggedIn()) {
    $me = $_SESSION['me'];
    $juggler = $user->getUserFromId($me->id);
    $posts = $postClass->getPosts($me->id);
    $info = $postClass->getInfoFromId($me->id);
  }
 ?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jugloop! | ジャグラーのための練習時間記録アプリケーション</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/styles.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
      (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-7014251046948920",
        enable_page_level_ads: true
      });
    </script>
  </head>
  <body>
    <?php include_once('../../navbar.php') ?>
    <div class="main">
      <ul class="breadcrumb">
        <li><a href="/">Top</a></li>
        <li><a href="/stamp">Stamp</a></li>
        <li><a href="/stamp/cigar">Cigarbox</a></li>
        <li class="active">HaimenDaikaiten</li>
      </ul>
  </div><!--main_n -->
      <?php if ($twitterLogin->isLoggedIn()): ?>
      <?php endif; ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../js/bootstrap.min.js"></script>
  </body>
</html>
