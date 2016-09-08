<?php

namespace MyApp;
use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterLogin {

  public $_userInfo;

  public function login() {
    if ($this->isLoggedIn()) {
      goHome();
    }
    if (!isset($_GET['oauth_token']) || !isset($_GET['oauth_verifier'])) {
      // 認証画面への移動処理
      $this->_redirectFlow();
    } else {
      // 認証後の処理
      $this->_callbackFlow();
    }
  }

  private function _redirectFlow() {
    $conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

    // リクエストトークンの発行
      $tokens = $conn->oauth('oauth/request_token', [
        'oauth_callback' => CALLBACK_URL
      ]);

    // リクエストトークンの保存
      $_SESSION['oauth_token'] = $tokens['oauth_token'];
      $_SESSION['oauth_token_secret'] = $tokens['oauth_token_secret'];

    // リダイレクト処理
      $authorizeUrl = $conn->url('oauth/authenticate', [
        'oauth_token' => $tokens['oauth_token']
      ]);
      header('Location:' . $authorizeUrl);
      exit;
  }

  private function _callbackFlow() {
    if ($_GET['oauth_token'] !== $_SESSION['oauth_token']) {
      // oauth_token がセッションと一致しないため、不正
      throw new \Exception('invalid oauth_token');
    }

    $conn = new TwitterOAuth(
      CONSUMER_KEY,
      CONSUMER_SECRET,
      $_SESSION['oauth_token'],
      $_SESSION['oauth_token_secret']
    );

    $tokens = $conn->oauth('oauth/access_token', [
      'oauth_verifier' => $_GET['oauth_verifier']
    ]);

    $user = new User();
    $user->saveTokens($tokens, $conn);

    $_SESSION['me'] = $user->getUser($tokens['user_id']);

    unset($_SESSION['oauth_token']);
    unset($_SESSION['oauth_token_secret']);

    goHome();
  }

  public function isLoggedIn() {
    return isset($_SESSION['me']) && !empty($_SESSION['me']);
  }
}
