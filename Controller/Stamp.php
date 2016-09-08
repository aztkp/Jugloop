<?php

namespace MyApp;

class Stamp {

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

  // Cigar

  public function getCigarScore($id) {
    $sql = sprintf("select sum(point) as sum from cigar_status where user_id=%d", (int)$id);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ)[0]->sum;
    return (int)$res;
  }

  public function getCigarTrick($id) {
    $sql = sprintf("select * from cigar_tricks where id=%d", (int)$id);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ)[0];
    return $res;
  }

  public function getCigarStatus($id) {
    $sql = sprintf("select * from cigar_status where user_id=%d order by created desc", $id);
    $stmt = $this->_db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $res;
  }

  public function getCigarProgress($id, $trickId) {
    $userLev = $this->_getUserCigarLev($id, $trickId);
    $maxLev = $this->_getMaxCigarLev($trickId);
    return ($userLev / $maxLev) * 100;
  }

  public function isDoneByCigar($id, $trickId, $level, $option) {
    $sql = sprintf("select count(*) from cigar_status where user_id=%d and trick_id=%d and level=%d and options=%d", (int)$id, (int)$trickId,(int)$level, (int)$option);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0] == 1 ? true : false;
  }

  private function _getUserCigarLev($id, $trickId) {
    $sql = sprintf("select count(*) from cigar_status where user_id=%d and trick_id=%d", (int)$id, (int)$trickId);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0];
  }

  private function _getMaxCigarLev($trickId) {
    $sql = sprintf("select level from cigar_tricks where id=%d",(int)$trickId);
    $stmt = $this->_db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_NUM)[0][0];
  }

  public function createCigarStamp($post) {
    $sql = "insert into cigar_status (
      user_id,
      trick_id,
      options,
      level,
      point,
      created,
      modified
      ) values (
      :user_id,
      :trick_id,
      :options,
      :level,
      :point,
      now(),
      now()
      )";
    $stmt = $this->_db->prepare($sql);

    $stmt->bindValue(':user_id', (int)$post['user_id'], \PDO::PARAM_INT);
    $stmt->bindValue(':trick_id', (int)$post['trick_id'], \PDO::PARAM_INT);
    $stmt->bindValue(':options', (int)$post['options'], \PDO::PARAM_INT);
    $stmt->bindValue(':level', (int)$post['level'], \PDO::PARAM_INT);
    $stmt->bindValue(':point', (int)$post['point'], \PDO::PARAM_INT);

    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to insert stamp status!');
    }
  }

  public function deleteCigarStamp($post) {
    $sql = sprintf("delete from cigar_status where user_id=%d and trick_id=%d and options=%d and level=%d"
           , (int)$post['user_id'], (int)$post['trick_id'], (int)$post['options'], (int)$post['level']);
    $stmt = $this->_db->prepare($sql);
    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      throw new \Exception('Failed to delete stamp!');
    }
  }

}
