<?php

  namespace MyApp;

  class Follow {

    private $_db;

    public function __construct() {
      $this->_connectDB();
    }

    private function _connectDB() {
      try {
        $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
        $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      } catch (\PDOException $e) {
        throw new \Exception('Failed to connect DB!');
      }
    }

    public function follow($post) {
      $sql = "insert into following (
        user_id, follower_id, created
      ) values (
        :user_id, :follower_id, now()
      )";

      $stmt = $this->_db->prepare($sql);

      $stmt->bindValue(':user_id', (int)$post['user_id'], \PDO::PARAM_INT);
      $stmt->bindValue(':follower_id', (int)$post['follower_id'], \PDO::PARAM_INT);

      try {
        $stmt->execute();
      } catch (\PDOException $e) {
        throw new \Exception('Failed to update status!');
      }
    }

    public function unfollow($post) {
      $sql = sprintf("delete from following where user_id=%d and follower_id=%d", $post['user_id'], $post['follower_id']);
      $stmt = $this->_db->prepare($sql);
      try {
        $stmt->execute();
      } catch (\PDOException $e) {
        throw new \Exception('Failed to update status!');
      }
    }

    public function getFollowers($user_id) {
      $sql = sprintf("select user_id from following where follower_id=%d", $user_id);
      $stmt = $this->_db->query($sql);
      $followers = $stmt->fetchAll(\PDO::FETCH_NUM)[0];
      array_push($followers, $user_id);
      return $followers;
    }

    public function isFollowed($user_id, $follower_id) {
      $sql = sprintf("select count(*) from following where user_id=%d and follower_id=%d", (int)$user_id, (int)$follower_id);
      $stmt = $this->_db->query($sql);
      return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0] == 1 ? true : false;
    }

    public function getFollowNum($user_id) {
      $sql = sprintf("select count(*) from following where follower_id=%d", $user_id);
      $stmt = $this->_db->query($sql);
      return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0];
    }

    public function getFollowersNum($user_id) {
      $sql = sprintf("select count(*) from following where user_id=%d", $user_id);
      $stmt = $this->_db->query($sql);
      return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0];
    }
  }
