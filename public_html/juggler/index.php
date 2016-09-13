<?php
  require_once('../../config.php');
  $id = $_GET['id'];

  $twitterLogin = new MyApp\TwitterLogin();
  $user = new MyApp\User();
  $post = new MyApp\Post();
  $follow = new MyApp\Follow();

  // クエリーから取得したIDのユーザーが存在するか
  $res = $user->_isExists($id);

  if ($twitterLogin->isLoggedIn()) {
    $me = $_SESSION['me'];
  }

  if ($res) {
    $juggler = $user->getUserFromId($id);
    $info = $post->getInfoFromId($id);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isValidate = $user->__validateToken();
    if ($isValidate) {
      if($_POST['action_type'] === "follow") {
        $follow->follow($_POST);
      } else if ($_POST['action_type'] === "unfollow") {
        $follow->unfollow($_POST);
      }
      header('Location: /juggler/' . $_POST['user_id']);
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

   </head>
   <body>
     <?php include_once('../navbar.php') ?>
     <div class="main">
     <?php if ($res): ?>

       <ul class="breadcrumb">
         <li><a href="/">Top</a></li>
         <li class="active">
         <?php if (isset($juggler->juggler_name)): ?>
         <?= h($juggler->juggler_name); ?>
         <?php else: ?>
           <?= h($juggler->tw_screen_name); ?>
         <?php endif; ?>
          </li>
       </ul>

       <img src="http://furyu.nazo.cc/twicon/<?= h($juggler->tw_screen_name); ?>/original" id="juggler_img" width="150"/>
       <div class="juggler_levbox">Lv.<?= h($info['level']); ?></div>
       <h2 id="juggler_name">
         <?php if (isset($juggler->juggler_name)): ?>
         <?= h($juggler->juggler_name); ?>
         <?php else: ?>
           <?= h($juggler->tw_screen_name); ?>
         <?php endif; ?>
       </h2>
       <?php if($me->id != $juggler->id): ?>
         <?php if($follow->isFollowed($juggler->id, $me->id)): ?>
           <form method="post">
           <input type="hidden" name="action_type" value="unfollow">
           <input type="hidden" name="user_id" value="<?= h($juggler->id); ?>">
           <input type="hidden" name="follower_id" value="<?= h($me->id); ?>">
           <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
           <center><button class="btn btn-primary btn-sm" style="margin-bottom: 10px;">
             <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>フォロー解除
           </button></center>
           </form>
         <?php else: ?>
         <form method="post">
         <input type="hidden" name="action_type" value="follow">
         <input type="hidden" name="user_id" value="<?= h($juggler->id); ?>">
         <input type="hidden" name="follower_id" value="<?= h($me->id); ?>">
         <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
         <center><button class="btn btn-warning btn-sm " style="margin-bottom: 10px;">
           <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>フォローする
         </button></center>
         </form>
         <?php endif; ?>
       <?php endif; ?>

       <div class="panel panel-default">
         <table class="juggler_info">
           <tbody>
             <tr>
               <th class="text-primary js12">ポスト数</th>
               <th class="text-primary js12">フォロー</th>
               <th class="text-primary js12">フォロワー</th>
             </tr>
             <tr>
               <td class="js20">
                 <a href="<?= h($juggler->id) ?>/posts ">
                 <?= h($post->getUserPostsNum($juggler->id)); ?>
                 </a>
               </td>
               <td class="js20">
                 <a href="<?= h($juggler->id) ?>/follow ">
                 <?= h($follow->getFollowNum($juggler->id)); ?>
                 </a>
               </td>
               <td class="js20">
                 <a href="<?= h($juggler->id) ?>/follower ">
                 <?= h($follow->getFollowersNum($juggler->id)); ?>
                 </a>
               </td>
             </tr>
           </tbody>
         </table>
       </div>
       <?php if($juggler->goal): ?>
         <div class="well well-sm cnt">現在の目標：<?=h($juggler->goal); ?></div>
      <?php endif; ?>
      <?php if($juggler->main_tool) : ?>
      <div class="juggler_infobox"><div class='cnt js14 text-primary'>メイン道具</div>
      <div class='cnt bold js24'>
        <?php switch ($juggler->main_tool) {
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
        ?>
      </div></div>
    <?php endif; ?>
    <?php if($juggler->circle) : ?>
      <div class="juggler_infobox"><div class='cnt js14 text-primary'>所属サークル・団体</div>
      <div class='cnt bold js24'><?= h($juggler->circle)?></div></div>
    <?php endif; ?>
       <div class="juggler_infobox"><div class='cnt js14 text-primary'>総練習時間</div>
       <div class='cnt bold js24'><?= h(timeEcho($info['alltime'][0]))?></div></div>
       <div class="juggler_infobox"><div class='cnt js14 text-primary'>次のレベルまで</div>
       <div class='cnt bold js24'><?= h($info['lefttime'])?></div></div>
       <?php if($juggler->introduction) : ?>
       <div class="juggler_infobox"><div class='cnt js14 text-primary'>自己紹介</div>
       <div class='well well-sm'><?= nl2br(h($juggler->introduction))?></div></div>

     <?php endif; ?>
     <?php else : ?>
     <?php echo "お探しのユーザーは存在しません。"; ?>
     <?php endif; ?>

   </div>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
     <script src="../../js/bootstrap.min.js"></script>
   </body>
 </html>
