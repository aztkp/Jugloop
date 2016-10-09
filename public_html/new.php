<?php
  require_once('../config.php');
  date_default_timezone_get('Asia/Tokyo');

  $twitterLogin = new MyApp\TwitterLogin();
  $postClass = new MyApp\Post();
  $user = new MyApp\User();

  if ($twitterLogin->isLoggedIn()) {
    $me = $_SESSION['me'];
  } else {
    goHome();
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isValidate = $user->__validateToken();
    if ($isValidate) {
    $postClass->createPost($_POST);
    goHome();
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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>
  <body>
    <?php include_once('navbar.php') ?>
    <div class="main">
      <ul class="breadcrumb">
        <li><a href="/">Top</a></li>
        <li class="active">New</li>
      </ul>
    <h1 class="page-header">記録する</h1>
    <form class="form-horizontal" method="post">

      <input type="hidden" name="user_id" value="<?php echo h($me->id);?> ">
      <div class="form-group">
        <label class="col-sm-4 control-label" for="usage2select2">練習時間</label><br>
        <div class="col-xs-6 col-sm-4">
          <select class="form-control" name="hour" id="time_h">
            <?php for ($i=0; $i<=12; $i++) : ?>
            <option value="<?=$i?>"><?=$i?>時間</option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="col-xs-6 col-sm-4">
          <select class="form-control" name="min" id="time_m">
            <?php for ($i=0; $i<=60; $i++) : ?>
            <option value="<?=$i?>"><?=$i?>分</option>
            <?php endfor; ?>
          </select>
        </div>
      </div>

     <div class="form-group">
       <label class="col-sm-4 control-label" for="usage2select2">練習した道具</label>
       <div class="col-sm-8">
         <select class="form-control" name="tool">
           <option value="1" <?php if($me->main_tool == 1) echo "selected";?>>ボール</option>
           <option value="2" <?php if($me->main_tool == 2) echo "selected";?>>クラブ</option>
           <option value="3" <?php if($me->main_tool == 3) echo "selected";?>>リング</option>
           <option value="4" <?php if($me->main_tool == 4) echo "selected";?>>ディアボロ</option>
           <option value="5" <?php if($me->main_tool == 5) echo "selected";?>>デビルスティック</option>
           <option value="6" <?php if($me->main_tool == 6) echo "selected";?>>フラワースティック</option>
           <option value="7" <?php if($me->main_tool == 7) echo "selected";?>>シガーボックス</option>
           <option value="8" <?php if($me->main_tool == 8) echo "selected";?>>コンタクトボール</option>
           <option value="9" <?php if($me->main_tool == 9) echo "selected";?>>けん玉</option>
           <option value="10" <?php if($me->main_tool == 10) echo "selected";?>>ポイ・スタッフ</option>
           <option value="11" <?php if($me->main_tool == 11) echo "selected";?>>ヨーヨー</option>
           <option value="12" <?php if($me->main_tool == 12) echo "selected";?>>その他</option>
         </select>
       </div>
     </div>

     <div class="form-group">
       <label class="col-sm-4 control-label" for="usage2input2">練習メモ</label>
       <div class="col-sm-8">
         <input type="text" class="form-control" name="hitokoto" value="">
       </div>
     </div>
     <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
     <div class="control-group">
       <div class="controls">
         <button type="submit" class="btn btn-primary right-fixed">記録する</button>
       </div>
     </div>
    </form>
  </div>
  <?php include_once('footer.php') ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
