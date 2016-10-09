<?php
  require_once('../../config.php');

  $twitterLogin = new MyApp\TwitterLogin();
  $userClass = new MyApp\User();
  $postClass = new MyApp\Post();

  if($_GET) {
    $circleName = urldecode($_GET['name']);
  } else {
     header('Location: /find');
  }

  $unregistered = false;
  if($circleName == "unregistered" && $_GET['unregistered'] == true) {
    $unregistered = true;
  }

  $memberNum = $userClass->getCircleMembersNum($_GET['name']);

  if ($twitterLogin->isLoggedIn()) {
    $me = $_SESSION['me'];
    $juggler = $userClass->getUserFromId($me->id);
    if (!$unregistered) {
      $users = $userClass->getUsersFromCircleName($circleName);
    } else {
      $users = $userClass->getCircleUnregisterUsers();
    }
    $circles = $userClass->getCircleName(10, 1);
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
          <li><a href="/find">Find</a></li>
          <li class="active"><?php if(!$unregistered):?><?=h($circleName);?><?php else:?><?="Unregistered";?>
            <?php endif; ?>
          </li>
        </ul>
        <?php if ($memberNum == 0 && !$unregistered) : ?>
          <div class="alert alert-warning" role="alert">
          お探しのページは存在しません。
        </div>
        <?php else: ?>

          <span class="js24"><?php if(!$unregistered){echo h($circleName);}else{echo "サークル未登録者";}?></span>

        <?php foreach($users as $user): ?>

          <div class="panel panel-default">
            <div class="panel-body">
              <img src="http://furyu.nazo.cc/twicon/<?= h($user->tw_screen_name); ?>/original" width="60" class="left-fixed" id="find_img">
              <table class="user_table">
                <tbody>
                  <tr>
                    <th><div class="text-primary js10">ユーザー名</div></th>
                    <th><div class="text-primary js10">メイン道具</div></th>
                    <th><div class="text-primary js10">所属サークル</div></th>
                  </tr>
                  <tr>
                    <td><div id="find_username"><strong>
                      <?php if (isset($user->juggler_name)): ?>
                      <?= h($user->juggler_name); ?>
                      <?php else: ?>
                        <?= h($user->tw_screen_name); ?>
                      <?php endif; ?>
                    </strong></div></td>
                    <td><div id="find_tool"><strong>
                      <!-- <?= h($user->main_tool); ?> -->
                      <?php switch ($user->main_tool) {
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
                        default :
                          echo "未登録";
                      } ?>
                    </strong></div></td>
                    <td><div id="find_circle"><strong>

                      <?php if(isset($user->circle)): ?>
                        <?= h($user->circle); ?>
                      <?php else: ?>
                        <?= "未登録" ?>
                      <?php endif; ?>

                    </strong></div></td>
                  </tr>
                </tbody>
              </table>
              <a href="../juggler/<?= h($user->id);?>" class="right-fixed js12">>>詳しく見る</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
      <?php endif; ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
