<?php
  require_once('../../config.php');

  $twitterLogin = new MyApp\TwitterLogin();
  $user = new MyApp\User();

  if ($twitterLogin->isLoggedIn()) {
    $_SESSION['me'] = $user->getUser($_SESSION['me']->tw_user_id);
    $me = $_SESSION['me'];
  } else {
    goHome();
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isValid = $user->__validateToken();
    if ($isValid) {
    $user->updateUser($me->tw_user_id, $_POST);
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/juggler/' . $me->id);
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
     <link href="../css/bootstrap.min.css" rel="stylesheet">
     <link href="../css/styles.css" rel="stylesheet">

     <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
     <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
     <!--[if lt IE 9]>
       <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
       <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
     <![endif]-->

   </head>
   <body>
     <?php include_once('../navbar.php') ?>
     <div class="main">
     <h1 class="page-header">設定変更</h1>
     <form class="form-horizontal" method="post">
    	<div class="form-group">
    		<label class="col-sm-4 control-label" for="usage2input2">ジャグラー・ネーム</label>
    		<div class="col-sm-8">
    			<input type="text" class="form-control" name="name" value="<?php echo h($me->juggler_name); ?>">
    		</div>
    	</div>

      <div class="form-group">
        <label class="col-sm-4 control-label" for="usage2select2">メイン道具</label>
        <div class="col-sm-8">
          <select class="form-control" id="usage2select2" name="tool">
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
        <label class="col-sm-4 control-label" for="usage2input2">所属サークル・団体</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="circle" value="<?php echo h($me->circle); ?>">
        </div>
      </div>



      <div class="form-group">
        <label class="col-sm-4 control-label" for="usage2input2">現在の目標</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="goal" value="<?php echo h($me->goal); ?>">
          <div class="help-block">今、練習中の技などなど...</div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="">自己紹介</label>
          <div class="controls">
            <textarea class="form-control" rows="7" name="introduction"><?php echo h($me->introduction); ?></textarea>
          <div class="help-block">(500字以内)</div>
        </div>
      </div>
      <!-- <div class="control-group">
        <label class="control-label" for="">Twitter連携</label>
          <div class="controls">
            <input type="radio" name="tw_status" value="2" <?php if($me->tw_status == 2) echo "checked";?>>練習記録時とレベルアップ時に自動的にTweetする<br>
            <input type="radio" name="tw_status" value="1" <?php if($me->tw_status == 1) echo "checked";?>>レベルアップ時に自動的にTweetする<br>
            <input type="radio" name="tw_status" value="0" <?php if($me->tw_status == 0) echo "checked";?>>Twitter連携を行わない
          </div>
        </div> -->
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
      <div class="control-group">
        <div class="controls" id="update_btn">
          <button type="submit" class="btn btn-primary">更新する</button>
        </div>
      </div>
    </form>

    <h1 class="page-header">ログアウト</h1>
    <form action="../logout" method="post">
      <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
      <button type="submit" class="btn btn-warning right-fixed">ログアウトする</button>
    </form>
    <h1 class="page-header">退会する</h1>
    <a href="delete"><button type="submit" class="btn btn-warning right-fixed">退会手続きに入る</button></a>

  </div>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
     <script src="../js/bootstrap.min.js"></script>
   </body>
 </html>
