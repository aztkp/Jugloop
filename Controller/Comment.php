<?php

namespace MyApp;

class Comment {

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

  public function getComment($id) {
    $sql = sprintf("select * from post_comments where post_id = %d", $id);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $res;
  }

  public function getCommentNum($id) {
    $sql = sprintf("select count(*) from post_comments where post_id=%d", $id);
    $stmt = $this->_db->query($sql);
    $res =  $stmt->fetchAll(\PDO::FETCH_NUM)[0][0];
    return (int)$res;
  }

  public function createComment($post) {
    $sql = "insert into post_comments (
      post_id,
      user_id,
      comment,
      created
      ) values (
      :post_id,
      :user_id,
      :comment,
      now()
      )";

    $stmt = $this->_db->prepare($sql);

    $stmt->bindValue(':post_id', (int)$post['post_id'], \PDO::PARAM_INT);
    $stmt->bindValue(':user_id', (int)$post['user_id'], \PDO::PARAM_INT);
    $stmt->bindValue(':comment', $post['comment'], \PDO::PARAM_STR);

    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to insert comment!');
    }
  }

}
