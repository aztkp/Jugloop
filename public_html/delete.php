<?php
  require_once('../config.php');
  $post_id = $_GET['id'];

  $twitterLogin = new MyApp\TwitterLogin();
  $user = new MyApp\User();
  $postClass = new MyApp\Post();

  // クエリーから取得したIDのユーザーが存在するか
  $res = $postClass->_isExists($post_id);

  if ($twitterLogin->isLoggedIn()) {
    $me = $_SESSION['me'];
  }

  if ($res) {
    $post = $postClass->getPostFromId($post_id);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isValidate = $user->__validateToken();
    if ($isValidate) {
    $postClass->deletePost($post->id);
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
     <?php include_once('navbar.php') ?>
     <div class="main_n">
       <?php if (isset($post) && $me->id === $post->user_id): ?>
         <h1 class="page-header">記録を削除</h1>
     <div class="juggling_post">
     <div class="panel panel-default">
    <div class="panel-heading">
         <a href="/status.php?id=<?php echo h($post->id); ?>">
         <?php echo date("n月d日 G:i", strtotime(h($post->created)));?></a>
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
         }         ?></span>
       </div>
     <?php if($post->hitokoto): ?>
     <div class="well well-sm" id="hitokoto">
         <?php echo h($post->hitokoto); ?>
     </div>
   <?php endif; ?>
     </div>
    </div>
     </div>
     <div class="alert alert-warning" role="alert">
     <b>Warning!</b><br>この処理は取り消すことができません。よろしいですか？
   </div>
   <form method="post">
     <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
     <button type="submit" class="btn btn-primary" id="update_btn">削除</button>
   </form>
     <?php else : ?>
       <div class="main_n">
     <?php echo "お探しのページにはアクセスできません"; ?>
   </div>
     <?php endif; ?>
   </div>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
     <script src="../js/bootstrap.min.js"></script>
   </body>
 </html>
