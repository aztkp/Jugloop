<?php
  require_once('../../config.php');
  $id = $_GET['id'];

  $twitterLogin = new MyApp\TwitterLogin();
  $user = new MyApp\User();
  $postClass = new MyApp\Post();

  if ($twitterLogin->isLoggedIn()) {
    $me = $_SESSION['me'];
    $juggler = $user->getUserFromId($id);
    $posts = $postClass->getUserPosts($id);
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
    <?php include_once('../navbar.php') ?>
    <div class="main">
      <?php if ($twitterLogin->isLoggedIn()): ?>

        <ul class="breadcrumb">
          <li><a href="/">Top</a></li>
          <li><a href="/juggler/<?= h($juggler->id); ?>">
          <?php if (isset($juggler->juggler_name)): ?>
            <?= h($juggler->juggler_name); ?>
          <?php else: ?>
            <?= h($juggler->tw_screen_name); ?>
          <?php endif; ?>
          </a></li>
           <li class="active">Posts</li>
        </ul>

          <?php foreach ($posts as $post) : ?>
          <div class="juggling_post">
          <div class="panel panel-default">
        	<div class="panel-heading">
              <!-- <a href="status.php?id=<?php echo h($post->id); ?>"> -->
              <?php echo date("n月d日 G:i", strtotime(h($post->created)));?>
            <!-- </a> -->
        	</div>
        	<div class="panel-body">
            <div class="jikan_box">
          		<span class="glyphicon glyphicon-time"></span><span id="jikan"><?php echo h(timeEcho($post->time)); ?></span>
            </div>
            <div class="tool_box">
              <span class="glyphicon glyphicon-wrench"></span><span id="tool">
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
          <div class="well well-sm" id="hitokoto">
              <?php echo h($post->hitokoto); ?>
          </div>
        <?php endif; ?>
          <div class="infotool js14">
          <a href="edit/<?php echo h($post->id);?>">編集</a> ｜ <a href="delete/<?php echo h($post->id); ?>" >削除</a>
        </div>
          </div>
        	</div>
          </div>
        <?php endforeach; ?>
        <?php if (!isset($post)) : ?>
          <div class="alert alert-success" id="mes_box">
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
    <script src="../../js/bootstrap.min.js"></script>
  </body>
</html>