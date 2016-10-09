<?php

namespace MyApp;

class User {
  private $_db;

  public function __construct() {
    $this->_createToken();
    $this->_connectDB();
  }

  private function _createToken() {
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
  }

  private function _connectDB() {
    try {
      $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
      $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      throw new \Exception('Failed to connect DB!');
    }
  }

  public function getUser($twUserId) {
    $sql = sprintf("select * from users where tw_user_id=%d", $twUserId);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetch(\PDO::FETCH_OBJ);
    return $res;
  }

  public function getUserFromId($id) {
    $sql = sprintf("select * from users where id=%d", $id);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetch(\PDO::FETCH_OBJ);
    return $res;
  }

  public function getAllUsers() {
    $sql = sprintf("select * from users");
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $res;
  }

  private function _exists($twUserId) {
    $sql = sprintf("select count(*) from users where tw_user_id=%d", $twUserId);
    $res = $this->_db->query($sql);
    return $res->fetchColumn() === '1';
  }

  public function saveTokens($tokens, $conn) {
    if ($this->_exists($tokens['user_id'])) {
      $this->_update($tokens);
    } else {
      $this->_insert($tokens);
      $this->_saveImg($tokens, $conn);
    }
  }

  private function _insert($tokens) {
    $sql = "insert into users (
      tw_user_id,
      tw_screen_name,
      tw_access_token,
      tw_access_token_secret,
      juggler_name,
      created,
      modified
      ) values (
      :tw_user_id,
      :tw_screen_name,
      :tw_access_token,
      :tw_access_token_secret,
      :juggler_name,
      now(),
      now()
      )";
    $stmt = $this->_db->prepare($sql);

    $stmt->bindValue(':tw_user_id', (int)$tokens['user_id'], \PDO::PARAM_INT);
    $stmt->bindValue(':tw_screen_name', $tokens['screen_name'], \PDO::PARAM_STR);
    $stmt->bindValue(':tw_access_token', $tokens['oauth_token'], \PDO::PARAM_STR);
    $stmt->bindValue(':tw_access_token_secret', $tokens['oauth_token_secret'], \PDO::PARAM_STR);
    $stmt->bindValue(':juggler_name', $tokens['screen_name'], \PDO::PARAM_STR);


    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to insert user!');
    }
  }

  public function _saveImg($tokens, $conn) {

  }

  private function _update($tokens) {
    $sql = "update users set
    tw_access_token = :tw_access_token,
    tw_access_token_secret = :tw_access_token_secret,
    modified = now()
    where tw_user_id = :tw_user_id";

    $stmt = $this->_db->prepare($sql);

    $stmt->bindValue(':tw_access_token', $tokens['oauth_token'], \PDO::PARAM_STR);
    $stmt->bindValue(':tw_access_token_secret', $tokens['oauth_token_secret'], \PDO::PARAM_STR);
    $stmt->bindValue(':tw_user_id', (int)$tokens['user_id'], \PDO::PARAM_INT);

    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to update user!');
    }
  }

  public function _isExists($id) {
    $sql = sprintf("select count(*) from users where id=%d", $id);
    $res = $this->_db->query($sql);
    return $res->fetchColumn() === '1';
  }

  public function __validateToken() {
    if (
      !isset($_SESSION['token']) ||
      !isset($_POST['token']) ||
      $_SESSION['token'] !==  $_POST['token']
    ) {
      return false;
    } else {
      return true;
    }
  }

  public function updateUser($id, $post) {
    $sql = "update users set
    juggler_name = :name,
    main_tool = :tool,
    circle = :circle,
    goal = :goal,
    introduction = :introduction,
    tw_status = :tw_status
    where tw_user_id = :tw_user_id";

    $stmt = $this->_db->prepare($sql);

    $stmt->bindValue(':name', $post['name'], \PDO::PARAM_STR);
    $stmt->bindValue(':tool', (int)$post['tool'], \PDO::PARAM_INT);
    $stmt->bindValue(':circle', $post['circle'], \PDO::PARAM_STR);
    $stmt->bindValue(':goal', $post['goal'], \PDO::PARAM_STR);
    $stmt->bindValue(':introduction', $post['introduction'], \PDO::PARAM_STR);
    $stmt->bindValue(':tw_status', (int)$post['tw_status'], \PDO::PARAM_INT);
    $stmt->bindValue(':tw_user_id', (int)$id, \PDO::PARAM_INT);

    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to update user!');
    }
  }

  public function deletePost($id) {
    $sql = sprintf("delete from posts where id=%d", $id);
    $stmt = $this->_db->prepare($sql);

    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to update user!');
    }
  }

  public function deleteUser($id) {
    $sql = sprintf("delete from users where id=%d", $id);
    $stmt = $this->_db->prepare($sql);
    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to delete user!');
    }

    $sql = sprintf("delete from posts where user_id=%d", $id);
    $stmt = $this->_db->prepare($sql);
    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to delete user\'s post!');
    }

    $sql = sprintf("delete from cigar_status where user_id=%d", $id);
    $stmt = $this->_db->prepare($sql);
    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to delete user\'s status!');
    }
  }

  public function getFollowInfo($id) {
    $followersId = $this->getFollow($id);
    if ($followersId === null) return null;
    $followers = implode(',', $followersId);
    $sql = sprintf("select * from users where id in ($followers) order by created desc");
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $res;
  }

  public function getFollow($user_id) {
    if (!$this->_isFollowing($user_id)) return null;
    $sql = sprintf("select user_id from following where follower_id=%d", $user_id);
    $stmt = $this->_db->query($sql);
    $followers = $stmt->fetchAll(\PDO::FETCH_OBJ);
    $followersId = [];
    for ($i=0; $i<sizeof($followers); $i++) {
      $followersId[$i]= $followers[$i]->user_id;
    }
    return $followersId;
  }

  private function _isFollowing($user_id) {
    $sql = sprintf("select count(*) from following where follower_id=%d", $user_id);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0] != 0;
  }

  public function getFollowerInfo($id) {
    $followersId = $this->getFollower($id);
    if ($followersId === null) return null;
    $followers = implode(',', $followersId);
    $sql = sprintf("select * from users where id in ($followers) order by created desc");
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $res;
  }

  public function getFollower($user_id) {
    if (!$this->_isFollowed($user_id)) return null;
    $sql = sprintf("select follower_id from following where user_id=%d", $user_id);
    $stmt = $this->_db->query($sql);
    $followers = $stmt->fetchAll(\PDO::FETCH_NUM)[0];
    return $followers;
  }

  private function _isFollowed($user_id) {
    $sql = sprintf("select count(*) from following where user_id=%d", $user_id);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0] != 0;
  }

  public function getScreenName($id) {
    $sql = sprintf("select tw_screen_name from users where id=%d", $id);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_OBJ)[0]->tw_screen_name;
  }

  public function getJugglerName($id) {
    $sql = sprintf("select juggler_name from users where id=%d", $id);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_OBJ)[0]->juggler_name;
  }

  public function getCircleName($num, $offset) {
    $sql = sprintf("select distinct circle from users limit %d, %d", $offset, $num);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $res;
  }

  public function getCircleMembersNum($circle) {
    $sql = sprintf("select count(*) from users where circle = :circle");
    $stmt = $this->_db->prepare($sql);
    $stmt->bindValue(':circle', $circle, \PDO::PARAM_STR);

    try {
      $stmt->execute();
      $res = $stmt->rowCount();
      $res = $stmt->fetchAll(\PDO::FETCH_NUM)[0][0];
      return $res;
    } catch (\PDOException $e) {
      throw new \Exception('Failed to get user!');
    }
  }

  public function getUsersFromCircleName($circle) {
    $sql = sprintf("select * from users where circle = :circle");
    $stmt = $this->_db->prepare($sql);
    $stmt->bindValue(':circle', $circle, \PDO::PARAM_STR);
    try {
      $stmt->execute();
      $res = $stmt->rowCount();
      $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
      return $res;
    } catch (\PDOException $e) {
      throw new \Exception('Failed to get user!');
    }
  }

  public function getUnregisteredNum() {
    $sql = sprintf("select count(*) from users where circle is NULL");
    $stmt = $this->_db->prepare($sql);
    try {
      $stmt->execute();
      $res = $stmt->rowCount();
      $res = $stmt->fetchAll(\PDO::FETCH_NUM)[0][0];
      return $res;
    } catch (\PDOException $e) {
      throw new \Exception('Failed to get user!');
    }
  }

  public function getCircleUnregisterUsers() {
    $sql = sprintf("select * from users where circle is NULL");
    $stmt = $this->_db->prepare($sql);
    try {
      $stmt->execute();
      $res = $stmt->rowCount();
      $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
      return $res;
    } catch (\PDOException $e) {
      throw new \Exception('Failed to get user!');
    }
  }

  public function getLatestUsers($num) {
    $sql = sprintf("select * from users order by created desc limit %d", $num);
    $stmt = $this->_db->prepare($sql);
    try {
      $stmt->execute();
      $res = $stmt->rowCount();
      $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
      return $res;
    } catch (\PDOException $e) {
      throw new \Exception('Failed to get user!');
    }
  }
}
