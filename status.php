<?php
  require_once(__DIR__ . '/config.php');
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

 ?>
 <!DOCTYPE html>
 <html lang="ja">
   <head>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Jugloop! | ジャグラーのための練習時間記録アプリケーション</title>
     <link href="css/bootstrap.min.css" rel="stylesheet">

     <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
     <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
     <!--[if lt IE 9]>
       <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
       <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
     <![endif]-->

   </head>
   <body>
     <?php include_once('navbar.html') ?>

     <br><br><br><br><br>
     <?php if ($res): ?>
       <div class="panel panel-default">
        <div class="panel-heading">
             <a href="/status.php?id=<?php echo h($post->id); ?>"> <?php echo date("Y年m月d日 H時i分", strtotime(h($post->kaishi)));?></a>
        </div>
      <div class="panel-body">
            <?php echo h($post->jikan) ?>
         <div class="well well-sm">
             <?php echo h($post->hitokoto); ?>
         </div>
         編集 ｜ 削除
      </div>
       </div>
     <?php echo "This page is " . $post_id; ?>
     <?php else : ?>
     <?php echo "お探しのステータスは存在しません。"; ?>
     <?php endif; ?>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
     <script src="js/bootstrap.min.js"></script>
   </body>
 </html>
