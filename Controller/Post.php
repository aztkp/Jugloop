<?php

namespace MyApp;

class Post {

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

  public function getPosts($id, $offset) {
    $followersId = $this->_getFollow($id);
    $followersId = implode(',', $followersId);
    $sql = sprintf("select * from posts where user_id in ($followersId) order by created desc limit %d, 3", $offset);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $res;
  }

  public function getPostsNum($id) {
    $followersId = $this->_getFollow($id);
    $followersId = implode(',', $followersId);
    $sql = sprintf("select count(*) from posts where user_id in ($followersId) order by created desc");
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0];
  }

  public function getPagesNum($id) {
    $postsNum = $this->getPostsNum($id);
    $res = (int)ceil($postsNum / 3);
    return $res;
  }

  private function _getFollow($user_id) {
    $sql = sprintf("select user_id from following where follower_id=%d", $user_id);
    $stmt = $this->_db->query($sql);
    $followers = $stmt->fetchAll(\PDO::FETCH_OBJ);
    $followersId = [];
    for ($i=0; $i<sizeof($followers); $i++) {
      $followersId[$i]= $followers[$i]->user_id;
    }
    array_push($followersId, $user_id);
    return $followersId;
  }

  /* 指定したユーザーのポストのみ取得 */

  public function getUserPosts($id) {
    $sql = sprintf("select * from posts where user_id=%d order by created desc", $id);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $res;
  }

  public function getUserPostsNum($id) {
    $sql = sprintf("select count(*) from posts where user_id=%d", $id);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0];
  }

  private function _isFollowing($user_id) {
    $sql = sprintf("select count(*) from following where follower_id=%d", $user_id);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0] != 0;
  }

  public function createPost($post) {
    $post['time'] = $post['hour'] . ":" . $post['min'];
    $sql = "insert into posts (
      user_id,
      tool,
      time,
      hitokoto,
      created,
      modified
      ) values (
      :user_id,
      :tool,
      :time,
      :hitokoto,
      now(),
      now()
      )";

    $stmt = $this->_db->prepare($sql);

    $stmt->bindValue(':user_id', (int)$post['user_id'], \PDO::PARAM_INT);
    $stmt->bindValue(':tool', (int)$post['tool'], \PDO::PARAM_INT);
    $stmt->bindValue(':time', $post['time'], \PDO::PARAM_STR);
    $stmt->bindValue(':hitokoto', $post['hitokoto'], \PDO::PARAM_STR);

    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to insert post!');
    }
  }

  public function _isExists($post_id) {
    $sql = sprintf("select count(*) from posts where id=%d", $post_id);
    $res = $this->_db->query($sql);
    return $res->fetchColumn() === '1';
  }

  public function getPostFromId($post_id) {
    $sql = sprintf("select * from posts where id=%d", $post_id);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetch(\PDO::FETCH_OBJ);
    return $res;
  }

  public function updatePost($id, $post) {
    $post['time'] = $post['hour'] . ":" . $post['min'];
    $sql = "update posts set
    tool = :tool,
    time = :time,
    hitokoto = :hitokoto,
    modified = now()
    where id = :id";

    $stmt = $this->_db->prepare($sql);

    $stmt->bindValue(':tool', (int)$post['tool'], \PDO::PARAM_INT);
    $stmt->bindValue(':time', $post['time'], \PDO::PARAM_STR);
    $stmt->bindValue(':hitokoto', $post['hitokoto'], \PDO::PARAM_STR);
    $stmt->bindValue(':id', (int)$id, \PDO::PARAM_INT);

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

  public function getInfoFromId($id) {
    $INFO['alltime'] = $this->_getAllTime($id);
    $INFO['alltimeM'] = $this->_toMin($INFO['alltime'][0]);
    $INFO['monthtime'] = $this->_getMonthTime($id);
    list ($INFO['level'], $INFO['left']) = $this->_getLevel((int)$INFO['alltimeM']);
    $INFO['lefttime'] = $this->toMin2($INFO['left']);
    return $INFO;
  }

  private function _calcTime($kaishi, $owari) {
    // $kaishiSec = strtotime($kaishi);
    // $owariSec = strtotime($owari);
    // $dif = $owariSec - $kaishiSec;
    // $result = gmdate("H:i", $dif);
    return $result;
  }

  private function _getAllTime($id) {
    $sql = sprintf("select sec_to_time(sum(time_to_sec(time))) from posts where user_id=%d", (int)$id);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetch(\PDO::FETCH_NUM);
    return $res;
  }

  private function _getMonthTime($id) {
    $sql = sprintf("select sec_to_time(sum(time_to_sec(time))) from posts where MONTH(now()) = MONTH(created) and user_id=%d", (int)$id);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetch(\PDO::FETCH_NUM);
    return $res;
  }

  private function _toMin($alltime) {
    if ($alltime) {
      $tArry = explode(":", $alltime);
      $hour = $tArry[0]*60;
      $mins = $hour + $tArry[1];
      return $mins;
    }
  }

  private function _getLevel($min) {
      if ($min < 60) {
        // レベル1の場合
        $level = 1;
        $left = 60 - $min;
      } else if ($min < 180) {
        // レベル2〜3の場合
        $tmp = $min;
        $level = floor(($tmp / 60) + 1);
        $left = $level * 60 - $min;
      } else if ($min < 450) {
        // レベル4〜6の場合
        $tmp = $min - 180;
        $level = floor(($tmp / 90) + 4);
        $left = (180 + ($level - 3) * 90) - $min;
      } else if ($min < 930) {
        // レベル7〜10の場合
        $tmp = $min - 450;
        $level = floor(($tmp / 120) + 7);
        $left = (450 + ($level - 6) * 120) - $min;
      } else {
        $tmp = 930;
        $level = 11;
        $def = 120;
        while (($def * 1.1 + $level * 15) / 2 + $tmp < $min) {
          $level++;
          $def = ($def * 1.1 + $level * 15) / 2;
          $tmp += $def;
        }
        $left = $tmp + ($def * 1.1 + $level * 15) / 2 - $min;
      }
      return array ( (int)$level, (int)ceil($left));
  }

  private function toMin2($left) {
      if ($left < 60) {
        return $left . '分';
      } else {
        $hour = floor($left / 60);
        $min = $left - $hour * 60;
        return $hour . '時間' . $min . '分';
      }

  }
}
