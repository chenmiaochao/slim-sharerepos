<?php

namespace LocalHalPH34\ShareReports\Classes\dao;

use PDO;
use LocalHalPH34\ShareReports\Classes\entity\Reportcate;

class ReportcateDAO {
  /**
   * @var PDO DB接続オブジェクト
   */
  private $db;

  /**
   * コンストラクタ
   * 
   * @param PDO $db DB接続オブジェクト
   */
  public function __construct(PDO $db) {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $this->db = $db;
  }

  public function findByPK(int $id): ?Reportcate {
    $sql = "SELECT * FROM reportcates WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $result = $stmt->execute();
    $report = null;
    if($result && $row = $stmt->fetch()) {
      $idDB = $row["id"];
      $rcName = $row["rc_name"];
      $rcNote = $row["rc_note"];
      $rcListFlg = $row["rc_list_flg"];
      $rcOrder = $row["rc_order"];

      $reportcate = new Reportcate();
      $reportcate->setId($idDB);
      $reportcate->setRcName($rcName);
      $reportcate->setRcNote($rcNote);
      $reportcate->setRcListFlg($rcListFlg);
      $reportcate->setRcOrder($rcOrder);
    }

    return $reportcate;
  }

  public function findAll(): array {
    $sql = "SELECT * FROM reportcates WHERE rc_list_flg = 1 ORDER BY rc_order";
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute();
    $reportcateList = [];
    while($row = $stmt->fetch()) {
      $id = $row["id"];
      $rcName = $row["rc_name"];
      $rcNote = $row["rc_note"];
      $rcListFlg = $row["rc_list_flg"];
      $rcOrder = $row["rc_order"];

      $reportcate = new Reportcate();
      $reportcate->setId($id);
      $reportcate->setRcName($rcName);
      $reportcate->setRcNote($rcNote);
      $reportcate->setRcListFlg($rcListFlg);
      $reportcate->setRcOrder($rcOrder);
      $reportcateList[$id] = $reportcate;
    }
    return $reportcateList;
  }
}