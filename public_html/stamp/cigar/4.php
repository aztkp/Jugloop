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
    <link href="../stamps.css" rel="stylesheet">

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
        <li class="active">Daikaiten</li>
      </ul>

      <div class="trick_title text-success js24">大回転</div>
      <div class="trick_intro js14">これができればシガーボックス入門者卒業</div><br><br>

      <div class="well">
        ルール<br>
        　・技と技のインターバルは1秒以内とする<br>
      </div>

      <div class="panel panel-default trick_panel">
        <div class="text-success level_txt trick_title_box"><div id="trick_level"><span class="glyphicon glyphicon-flash"></span>Level 1 (2pt)</div></div>
        <div class="trick_count_box text-success"><div id="trick_count">3回</div></div>
        <div class="trick_btn_box">
          <?php if($stamp->isDoneByCigar($me->id, 4, 1, 0) && $stamp->isDoneByCigar($me->id, 4, 2, 0)): ?>
            <button class="btn btn-primary disabled">達成済
          <?php elseif($stamp->isDoneByCigar($me->id, 4, 1, 0)): ?>
            <form action="delete" method="post">
              <input type="hidden" name="trick_id" value="4">
              <input type="hidden" name="level" value="1">
              <input type="hidden" name="options" value="0">
              <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
              <button class="btn btn-primary">達成済
            </form>
          <?php else: ?>
            <form action="update" method="post">
              <input type="hidden" name="trick_id" value="4">
              <input type="hidden" name="level" value="1">
              <input type="hidden" name="options" value="0">
              <input type="hidden" name="point" value="2">
              <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
              <button class="btn btn-defalut">未達成
            </form>
          <?php endif; ?>
        </div>
      </div>

      <div class="panel panel-default trick_panel">
        <div class="text-success level_txt trick_title_box"><div id="trick_level"><span class="glyphicon glyphicon-flash"></span>Level 2 (4pt)</div></div>
        <div class="trick_count_box text-success"><div id="trick_count">5回</div>
        </div><div class="trick_btn_box">
          <?php if($stamp->isDoneByCigar($me->id, 4, 2, 0) && $stamp->isDoneByCigar($me->id, 4, 3, 0)): ?>
            <button class="btn btn-primary disabled">達成済
          <?php elseif($stamp->isDoneByCigar($me->id, 4, 2, 0)): ?>
            <form action="delete" method="post">
              <input type="hidden" name="trick_id" value="4">
              <input type="hidden" name="level" value="2">
              <input type="hidden" name="options" value="0">
              <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
              <button class="btn btn-primary">達成済
            </form>
          <?php elseif($stamp->isDoneByCigar($me->id, 4, 1, 0)): ?>
            <form action="update" method="post">
              <input type="hidden" name="trick_id" value="4">
              <input type="hidden" name="level" value="2">
              <input type="hidden" name="options" value="0">
              <input type="hidden" name="point" value="4">
              <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
              <button class="btn btn-defalut">未達成
            </form>
          <?php else: ?>
            <button class="btn btn-defalut disabled">未達成
          <?php endif; ?>
        </div>
      </div>

      <div class="panel panel-default trick_panel">
        <div class="text-success level_txt trick_title_box"><div id="trick_level"><span class="glyphicon glyphicon-flash"></span>Level 3 (6pt)</div></div>
        <div class="trick_count_box text-success"><div id="trick_count">10回</div></div>
        <div class="trick_btn_box">
          <?php if($stamp->isDoneByCigar($me->id, 4, 3, 0) && $stamp->isDoneByCigar($me->id, 4, 4, 0)): ?>
            <button class="btn btn-primary disabled">達成済
          <?php elseif($stamp->isDoneByCigar($me->id, 4, 3, 0)): ?>
            <form action="delete" method="post">
              <input type="hidden" name="trick_id" value="4">
              <input type="hidden" name="level" value="3">
              <input type="hidden" name="options" value="0">
              <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
              <button class="btn btn-primary">達成済
            </form>
          <?php elseif($stamp->isDoneByCigar($me->id, 4, 2, 0)): ?>
            <form action="update" method="post">
              <input type="hidden" name="trick_id" value="4">
              <input type="hidden" name="level" value="3">
              <input type="hidden" name="options" value="0">
              <input type="hidden" name="point" value="6">
              <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
              <button class="btn btn-defalut">未達成
            </form>
          <?php else: ?>
            <button class="btn btn-defalut disabled">未達成
          <?php endif; ?>
          </div>
      </div>

      <div class="panel panel-default trick_panel">
        <div class="text-success level_txt trick_title_box"><div id="trick_level"><span class="glyphicon glyphicon-flash"></span>Level 4 (8pt)</div></div>
        <div class="trick_count_box text-success"><div id="trick_count">20回</div></div>
        <div class="trick_btn_box">
          <?php if($stamp->isDoneByCigar($me->id, 4, 4, 0) && $stamp->isDoneByCigar($me->id, 4, 5, 0)): ?>
            <button class="btn btn-primary disabled">達成済
          <?php elseif($stamp->isDoneByCigar($me->id, 4, 4, 0)): ?>
            <form action="delete" method="post">
              <input type="hidden" name="trick_id" value="4">
              <input type="hidden" name="level" value="4">
              <input type="hidden" name="options" value="0">
              <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
              <button class="btn btn-primary">達成済
            </form>
          <?php elseif($stamp->isDoneByCigar($me->id, 4, 3, 0)): ?>
            <form action="update" method="post">
              <input type="hidden" name="trick_id" value="4">
              <input type="hidden" name="level" value="4">
              <input type="hidden" name="options" value="0">
              <input type="hidden" name="point" value="8">
              <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
              <button class="btn btn-defalut">未達成
            </form>
          <?php else: ?>
            <button class="btn btn-defalut disabled">未達成
          <?php endif; ?>
        </div>
      </div>

      <div class="panel panel-default trick_panel">
        <div class="text-success level_txt trick_title_box"><div id="trick_level"><span class="glyphicon glyphicon-flash"></span>Level 5 (10pt)</div></div>
        <div class="trick_count_box text-success"><div id="trick_count">30回</div></div>
        <div class="trick_btn_box">
          <?php if($stamp->isDoneByCigar($me->id, 4, 5, 0)): ?>
            <form action="delete" method="post">
              <input type="hidden" name="trick_id" value="4">
              <input type="hidden" name="level" value="5">
              <input type="hidden" name="options" value="0">
              <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
              <button class="btn btn-primary">達成済
            </form>
          <?php elseif($stamp->isDoneByCigar($me->id, 4, 4, 0)): ?>
            <form action="update" method="post">
              <input type="hidden" name="trick_id" value="4">
              <input type="hidden" name="level" value="5">
              <input type="hidden" name="options" value="0">
              <input type="hidden" name="point" value="10">
              <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
              <button class="btn btn-defalut">未達成
            </form>
          <?php else: ?>
            <button class="btn btn-defalut disabled">未達成
          <?php endif; ?>
        </div>
      </div>

  </div><!--main_n -->
      <?php if ($twitterLogin->isLoggedIn()): ?>
      <?php endif; ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../../js/bootstrap.min.js"></script>
  </body>
</html>
