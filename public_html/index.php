<?php
  require_once('../config.php');

  $twitterLogin = new MyApp\TwitterLogin();
  $user = new MyApp\User();
  $postClass = new MyApp\Post();

  if ($twitterLogin->isLoggedIn()) {
    $me = $_SESSION['me'];
    $juggler = $user->getUserFromId($me->id);
    $info = $postClass->getInfoFromId($me->id);
    $posts = $postClass->getPosts($me->id);
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
      <div style="overflow: hidden;">
      <div class="top_btnbox">
        <div id="button">
          <a href="new"><button class="btn btn-warning btn-block top_btn">練習を記録</button></a>
        </div>
      </div>
      <div class="top_btnbox">
        <div id="button">
          <a href="stamp/cigar"><button class="btn btn-warning btn-block top_btn">スタンプ</button></a>
        </div>
      </div>
      <div class="top_btnbox">
        <div id="button">
          <a href="juggler/<?=h($me->id);?>"><button class="btn btn-warning btn-block top_btn">マイページ</button></a>
        </div>
      </div>
    </div>

      <div class="panel panel-default">
        <div class="panel-body">
          <div class="top_imgbox">
            <img src="http://furyu.nazo.cc/twicon/<?= h($me->tw_screen_name); ?>/original" width="100" class="right-fixed"/>
          </div>
          <div class="top_infobox">
            <div class="text-primary js12">今月の練習時間</div>
            <div class="bold js24"><?= h(timeEcho($info['monthtime'][0]))?></div>
            <div class="text-primary js12">次のレベルまで</div>
            <div class="bold js24"><?= h($info['lefttime'])?></div>
            <div class="js12 right-fixed">
            <a href="juggler/<?= h($me->id);?>">>>詳しく見る</a>
          </div>
          </div>
        </div>
      </div>

          <?php foreach ($posts as $post) : ?>
            <?php  $userinfo = $postClass->getInfoFromId($post->user_id); ?>
          <div class="panel panel-default" style="margin-bottom: 20px;">
        	<div class="panel-body">
            <a href="juggler/<?= h($post->user_id);?>">
            <img src="http://furyu.nazo.cc/twicon/<?= h($user->getScreenName($post->user_id)); ?>/original" width="70" class="left-fixed" id="post_userimg"/></a>

            <div class="post_userinfo">
              <a href="juggler/<?= h($post->user_id);?>">
              <strong><?= h($user->getJugglerName($post->user_id)); ?></strong></a>
              <strong><span id="post_lv">- Lv.<?= h($userinfo['level']) ?></span></strong>
                 <span class="js12 text-muted" style="margin-left: 15px;"><?= date("n月d日 G:i", strtotime(h($post->created)));?>に記録</span>
            </div>

            <div class="cnt" style="padding-top: 10px;">
          		<span id="post_time"><span class="glyphicon glyphicon-time js20"></span><?= h(timeEcho($post->time));?></span>
            </div>
            <div class="cnt text-primary" style="padding-left: 70px;">
              <span class="js14"><span class="glyphicon glyphicon-wrench"></span>
              <?php switch ($post->tool) {
                case "1" :
                  echo "ボール";
                  break;
                case "2" :
                  echo "クラブ";
                  break;
                case "3" :
                  echo "リング";
                  break;
                case "4" :
                  echo "ディアボロ";
                  break;
                case "5" :
                  echo "デビルスティック";
                  break;
                case "6" :
                  echo "フラワースティック";
                  break;
                case "7" :
                  echo "シガーボックス";
                  break;
                case "8" :
                  echo "コンタクトボール";
                  break;
                case "9" :
                  echo "けん玉";
                  break;
                case "10" :
                  echo "ポイ・スタッフ";
                  break;
                case "11" :
                  echo "ヨーヨー";
                  break;
                case "12" :
                  echo "その他";
                  break;
              }
              ?></span>
            </div>
          <?php if($post->hitokoto): ?>
          <div class="well well-sm" id="post_memo">
              <?php echo h($post->hitokoto); ?>
          </div>
        <?php endif; ?>

       <?php if($me->id === $post->user_id): ?>
          <div class="right-fixed js14" style="margin-top: 5px;">
          <a href="edit/<?php echo h($post->id);?>">編集</a> ｜ <a href="delete/<?php echo h($post->id); ?>" >削除</a>
          </div>
      <?php endif; ?>
        	</div>
          </div>
        <?php endforeach; ?>
        <?php if (!isset($post)) : ?>
          <div class="alert alert-success cnt">
            まだ練習記録が存在しません。<br>
            練習を記録しましょう！
        </div>
      </div>
        <?php endif; ?>
      <?php else: ?>
        <?php include_once('top.html') ?>
      <?php endif; ?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
