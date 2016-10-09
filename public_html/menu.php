<?php
  require_once('../config.php');

  $twitterLogin = new MyApp\TwitterLogin();
  $user = new MyApp\User();
  $postClass = new MyApp\Post();
  $commentClass = new MyApp\Comment();
  $page = 1;

  if ($twitterLogin->isLoggedIn()) {
    $me = $_SESSION['me'];
    $juggler = $user->getUserFromId($me->id);
    $info = $postClass->getInfoFromId($me->id);

  // pagenation

    if($_GET) {
      $page = $_GET['p'];
    }

    $allPageNum = $postClass->getPagesNum($me->id);


    $disp = 5;
    $next = $page + 1;
    $prev = $page - 1;
    $start = ($page - floor($disp/2))> 0 ? ($page - ceil($disp/2)) : 1;
    $end = ($page + floor($disp/2)) > $disp ? ($page + floor($disp/2)) : $disp;
    if ($end > $allPageNum) $end = $allPageNum;
    $start = (0 < $end - 5) ? $end-4 : 1;

    $posts = $postClass->getPosts($me->id, ($page-1)*10);
    $pageMenu = $postClass->getPagesNum($me->id);

  }
 ?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jugloop! | ジャグラーのための練習時間記録アプリケーション</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">

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
    <?php include_once('navbar.php') ?>
    <div class="main">
      <?php if ($twitterLogin->isLoggedIn()): ?>
        <h2>練習を記録する</h2>
        <h2>仲間の記録を閲覧する</h2>
        <h2>仲間の記録にコメントする</h2>
        <h2>仲間を探す</h2>
        <h2>自分の練習を振り返る</h2>
        <h2></h2>

      <?php else: ?>
        <?php include_once('top.html') ?>
      <?php endif; ?>
      </div>
      <?php include_once('footer.php') ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
