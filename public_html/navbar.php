<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
  <div class="navbar-header">
    <button class="navbar-toggle" data-toggle="collapse" data-target=".target">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
      <a class="navbar-brand" href="/">Jugloop!</a>
  </div>
  <div class="collapse navbar-collapse target">
    <ul class="nav navbar-nav navbar-right">
      <?php if ($twitterLogin->isLoggedIn()) : ?>
        <li><a href="/new">記録する</a></li>
        <li><a href="/juggler/<?php echo h($me->id); ?>">マイページ</a></li>
        <li><a href="/find">ユーザー検索</a></li>
        <li><a href="/settings">設定変更</a></li>
      <?php else: ?>
        <li><a href="login">ログイン</a></li>
      <?php endif; ?>
    </ul>
  </div>
  </div>
</nav>
