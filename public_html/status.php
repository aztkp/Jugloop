<?php
  require_once('../config.php');
  $post_id = $_GET['id'];

  $twitterLogin = new MyApp\TwitterLogin();
  $user = new MyApp\User();
  $postClass = new MyApp\Post();
  $commentClass = new MyApp\Comment();

  $res = $postClass->_isExists($post_id);

  if ($twitterLogin->isLoggedIn()) {
    $me = $_SESSION['me'];
    if ($res) {
      $post = $postClass->getPostFromId($post_id);
    }
    $comments = $commentClass->getComment($post_id);
    $commentNum = $commentClass->getCommentNum($post_id);
  } else {
    goHome();
  }


  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isValidate = $user->__validateToken();
    if ($isValidate) {
      $commentClass->createComment($_POST);
      header('Location: /status/'. $_POST['post_id'] );
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
     <div class="main">
     <?php if (isset($post)): ?>
       <?php  $userinfo = $postClass->getInfoFromId($post->user_id); ?>
     <div class="panel panel-default" style="margin-bottom: 20px;">
     <div class="panel-body">
       <a href="juggler/<?= h($post->user_id);?>">
       <img src="http://furyu.nazo.cc/twicon/<?= h($user->getScreenName($post->user_id)); ?>/original" width="70" class="left-fixed" id="post_userimg"/></a>

       <div class="post_userinfo">
         <a href="/juggler/<?= h($post->user_id);?>">
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
     <div class="well well-sm" id="post_memo" style="margin-bottom: 20px;">
         <?php echo h($post->hitokoto); ?>
     </div>
   <?php else : ?>
     <hr>
   <?php endif; ?>
   <?php if ($post->user_id === $me->id) : ?>
     <div class="right-fixed js14" style="margin-top: -5px;">
      <a href="/edit/<?php echo h($post->id);?>">編集</a> ｜ <a href="/delete/<?php echo h($post->id); ?>" >削除</a>
     </div>
     <?php endif; ?>
     </div>
     </div>

     <div class="panel panel-default" style="height: 125px;">
       <div class="form-group">
         <label class="control-label" for="usage2input2" style="margin: 10px 0 0 10px;"><i class="glyphicon glyphicon-comment js14"></i> コメントを投稿</label><br>
         <img src="http://furyu.nazo.cc/twicon/<?= h($user->getScreenName($me->id)); ?>/original" class="left-fixed thumbnail" style="display: inline-block;"/>
         <form action="" method="POST">
           <input type="hidden" class="form-control" name="post_id" value="<?= h($post_id);?>">
           <input type="hidden" class="form-control" name="user_id" value="<?= h($me->id);?>">
           <input type="text" class="form-control" name="comment" value="" style="display: inline-block; width: 333px; margin: 10px 0 0 5px;">
           <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
           <button type="submit" class="btn btn-sm btn-warning right-fixed" style="display: inline-block; margin:5px 10px 0 0;">コメントを送信</button>
         </form>
       </div>
     </div>


     <?php if($comments): ?>
     <div class="panel panel-default">
       <label class="control-label" for="usage2input2" style="margin: 10px 0 0 10px;"><i class="glyphicon glyphicon-fire js16"></i> コメント ( <?= h($commentNum); ?> )</label><br>

         <?php foreach ($comments as $comment) : ?>
           <?php  $commentUserinfo = $postClass->getInfoFromId($comment->user_id); ?>
         <div class="comment_box">
         <img src="http://furyu.nazo.cc/twicon/<?= h($user->getScreenName($comment->user_id)); ?>/original" class="left-fixed thumbnail"/>
         <a href="/juggler/<?= h($comment->user_id); ?>"><strong class="js14" style="margin-left:10px;"><?= h($user->getJugglerName($comment->user_id)); ?></strong></a>
         <strong><span id="post_lv" class="js12">- Lv.<?= h($commentUserinfo['level']) ?></span></strong>
         <span class="js12 text-muted" style="margin-left: 15px;"><?= date("n月d日 G:i", strtotime(h($comment->created)));?> に投稿</span>
         <div class="well well-sm comment_text" style="margin-bottom: 20px;">
           <?= h($comment->comment) ?>
         </div>
         <?php if($comment->user_id === $me->id): ?>
           <button type="submit" class="btn btn-xs btn-defalut right-fixed" style="display: inline-block; margin:-15px 10px 0 0;">コメントを削除</button>
         <?php endif; ?>
         </div>
       <?php endforeach; ?>
       <?php endif; ?>
     </div>

     <?php else : ?>
              <div class="alert alert-warning" role="alert">
              お探しのページは存在しません。
            </div>
     <?php endif; ?>
   </div>
   <?php include_once('footer.php') ?>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
     <script src="../js/bootstrap.min.js"></script>
   </body>
 </html>
