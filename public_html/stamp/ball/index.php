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
    $stamps = $stamp->getCigarStatus($me->id);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['tool']) {
      case 1: header('Location: ../ball');
              break;
      case 2: header('Location: ../club');
              break;
      case 3: header('Location: ../ring');
              break;
      case 4: header('Location: ../dia');
              break;
      case 5: header('Location: ../devil');
              break;
      case 6: header('Location: ../flower');
              break;
      case 7: header('Location: ../cigar');
              break;
      case 8: header('Location: ../contact');
              break;
      case 9: header('Location: ../kendama');
              break;
      case 10: header('Location: ../poi-staff');
               break;
      case 11: header('Location: ../yo-yo-');
               break;
      case 12: header('Location: ../others');
               break;
    }
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
        <li class="active">Ball</li>
      </ul>


      <form method="post">
      <div class="form-group">
        <div class="col-xs-8">
          <select class="form-control" name="tool">
            <option value="1" selected>ボール</option>
            <option value="2">クラブ</option>
            <option value="3">リング</option>
            <option value="4">ディアボロ</option>
            <option value="5">デビルスティック</option>
            <option value="6">フラワースティック</option>
            <option value="7">シガーボックス</option>
            <option value="8">コンタクトボール</option>
            <option value="9">けん玉</option>
            <option value="10">ポイ・スタッフ</option>
            <option value="11">ヨーヨー</option>
            <option value="12">その他</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <div class="controls">
          <div class="col-xs-2">
          <button type="submit" class="btn btn-primary">道具を変更</button>
        </div>
        </div>
      </div>
    </form>

    <br>

    <div class="alert alert-dismissible alert-danger" style="margin-top: 40px;">
      <strong>大変申し訳ございません。</strong><br>現在、スタンプ機能はシガーボックスのみテスト運用中です。
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
