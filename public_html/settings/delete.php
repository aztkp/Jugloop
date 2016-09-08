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
      $user->deleteUser($me->id);

      $_SESSION = [];
      if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 86400, '/');
      }
      session_destroy();
      
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
     <?php include_once('../navbar.php') ?>
     <div class="main_n">
    <h1 class="page-header">退会する</h1>
    <div class="alert alert-warning" role="alert">
    <b>Warning!</b><br>退会処理を行うと、これまでに行った練習記録、スタンプの記録、登録情報は破棄され、
    復元することはできません。よろしいですか？
  </div>
    <form method="post">
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    <button type="submit" class="btn btn-warning">退会する</button>
    </form>
  </div>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
     <script src="../js/bootstrap.min.js"></script>
   </body>
 </html>
