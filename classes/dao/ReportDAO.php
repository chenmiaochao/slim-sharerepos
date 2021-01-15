<?php

namespace LocalHalPH34\ShareReports\Classes\dao;

use PDO;
use LocalHalPH34\ShareReports\Classes\entity\Report;

/**
 * Reportテーブルへのデータ操作クラス
 */
class ReportDAO {
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

  /**
   * 主キーによる検索
   * 
   * @param integer $id 主キーであるid
   * @return entity Report 該当するオブジェクト。ただし、該当データがない場合はnull
   */
  public function findByPK(int $id): ?Report {
    $sql = "SELECT * FROM reports WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    // var_dump($id);
    $stmt->bindValue(":id", $id , PDO::PARAM_INT);
    $result = $stmt->execute();
    $report = null;
    if($result && $row = $stmt->fetch()) {
      $idDB = $row["id"];
      $rpDate = $row["rp_date"];
      $rpTimeFrom = $row["rp_time_from"];
      $rpTimeTo = $row["rp_time_to"];
      $rpContent = $row["rp_content"];
      $rpCreatedAt = $row["rp_created_at"];
      $reportcateId = $row["reportcate_id"];
      $userId = $row["user_id"];

      $report = new Report();

      $report->setId($idDB);
      $report->setRpDate($rpDate);
      $report->setRpTimeFrom($rpTimeFrom);
      $report->setRpTimeTo($rpTimeTo);
      $report->setRpContent($rpContent);
      $report->setRpCreatedAt($rpCreatedAt);
      $report->setReportcateId($reportcateId);
      $report->setUserId($userId);
    }
    return $report;
  }


  /**
   * 全レポート情報検索
   * 
   * @return array
   * 全レポート情報が格納された連想配列、キーはid、値はReportエンティティクラス
   */
  public function findAll(): array {
    $sql = "SELECT * FROM reports ORDER BY rp_date DESC";
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute();
    $reportList = [];
    while($result && $row = $stmt->fetch()) {
      $id = $row["id"];
      $rpDate = $row["rp_date"];
      $rpTimeFrom = $row["rp_time_from"];
      $rpTimeTo = $row["rp_time_to"];
      $rpContent = $row["rp_content"];
      $rpCreatedAt = $row["rp_created_at"];
      $reportcateId = $row["reportcate_id"];
      $userId = $row["user_id"];

      $report = new Report();
      $report->setId($id);
      $report->setRpDate($rpDate);
      $report->setRpTimeFrom($rpTimeFrom);
      $report->setRpTimeTo($rpTimeTo);
      $report->setRpContent($rpContent);
      $report->setRpCreatedAt($rpCreatedAt);
      $report->setReportcateId($reportcateId);
      $report->setUserId($userId);
      $reportList[$id] = $report;
    }
    return $reportList;
  }

  //レポートの数を取得する
  public function getRcLength(): int {
    $sql = "SELECT COUNT(id) FROM reports";
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute();
    $row = $stmt->fetch();
    $length = $row["COUNT(id)"];
    return $length;
  }


  //ページネーション
  public function getPage(int $page) : array {
    $sql = "SELECT * FROM reports ORDER BY rp_date DESC LIMIT 3 OFFSET :page";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(":page", $page, PDO::PARAM_INT);
    $result = $stmt->execute();
    $reportList = [];
    while($row = $stmt->fetch()) {
      $id = $row["id"];
      $rpDate = $row["rp_date"];
      $rpTimeFrom = $row["rp_time_from"];
      $rpTimeTo = $row["rp_time_to"];
      $rpContent = $row["rp_content"];
      $rpCreatedAt = $row["rp_created_at"];
      $reportcateId = $row["reportcate_id"];
      $userId = $row["user_id"];

      $report = new Report();
      $report->setId($id);
      $report->setRpDate($rpDate);
      $report->setRpTimeFrom($rpTimeFrom);
      $report->setRpTimeTo($rpTimeTo);
      $report->setRpContent($rpContent);
      $report->setRpCreatedAt($rpCreatedAt);
      $report->setReportcateId($reportcateId);
      $report->setUserId($userId);
      $reportList[$id] = $report;
    }
    return $reportList;
  }

  /**
   * レポート情報登録
   * 
   * @param Report $report 登録情報が格納されたReportオブジェクト
   * @return integer 登録情報の連番主キーの値。登録に失敗した場合は-1
   */
  public function insert(Report $report): int {
    $sqlInsert = "INSERT INTO reports (rp_date, rp_time_from, rp_time_to, rp_content, rp_created_at, reportcate_id, user_id) VALUES (:rp_date, :rp_time_from, :rp_time_to, :rp_content, :rp_created_at, :reportcate_id, :user_id)";
    $stmt = $this->db->prepare($sqlInsert);
    // var_dump("a");
    $stmt->bindValue(":rp_date", $report->getRpDate(), PDO::PARAM_STR);
    $stmt->bindValue(":rp_time_from", $report->getRpTimeFrom(), PDO::PARAM_STR);
    $stmt->bindValue(":rp_time_to", $report->getRpTimeTo(), PDO::PARAM_STR);
    $stmt->bindValue(":rp_content", $report->getRpContent(), PDO::PARAM_STR);
    $stmt->bindValue(":rp_created_at", $report->getRpCreatedAt(), PDO::PARAM_STR);
    $stmt->bindValue(":reportcate_id", $report->getReportcateId(), PDO::PARAM_INT);
    $stmt->bindValue(":user_id", $report->getUserId(), PDO::PARAM_INT);

    // $sqlInsert = "INSERT INTO reports (rp_date, rp_time_from, rp_time_to, rp_content, rp_created_at, reportcate_id, user_id) VALUES (".$report->getRpDate().",".$report->getRpTimeFrom().",".$report->getRpTimeTo().",".$report->getRpContent().",".$report->getRpCreatedAt().",".$report->getReportcateId().",".$report->getUserId().")";
    // echo $sqlInsert;
    $result = $stmt->execute();

    
    if($result) {
      $rpId = $this->db->lastInsertId();
    }else {
      $rpId = -1;
    }

    return $rpId;
  }

  /**
   * レポート情報更新 更新対象は1レコードのみ
   *
   * @param Report $report
   *   更新情報が格納されたReportオブジェクト。主キーがこのオブジェクトのidの値のレコードを更新する
   * @return boolean 更新が成功したかどうかを表す値
   */
  public function update(Report $report): bool {
    $sqlUpdate = "UPDATE reports SET rp_date = :rp_date, rp_time_from = :rp_time_from, rp_time_to = :rp_time_to, rp_content = :rp_content, reportcate_id = :reportcate_id WHERE id = :id";
    // $sqlUpdate = "UPDATE reports SET rp_date = ".$report->getRpDate().", rp_time_from = ".$report->getRpTimeFrom().", rp_time_to = ".$report->getRpTimeTo().", rp_content = ".$report->getRpContent().", reportcate_id = ".$report->getReportcateId()." WHERE id = ".$report->getUserId()."";
    // var_dump($sqlUpdate);

    $stmt = $this->db->prepare($sqlUpdate);
    $stmt->bindValue(":rp_date", $report->getRpDate(), PDO::PARAM_STR);
    $stmt->bindValue(":rp_time_from", $report->getRpTimeFrom(), PDO::PARAM_STR);
    $stmt->bindValue(":rp_time_to", $report->getRpTimeTo(), PDO::PARAM_STR);
    $stmt->bindValue(":rp_content", $report->getRpContent(), PDO::PARAM_STR);
    $stmt->bindValue(":reportcate_id", $report->getReportcateId(), PDO::PARAM_INT);
    $stmt->bindValue(":id", $report->getId(), PDO::PARAM_INT);
    $result = $stmt->execute();
    return $result;
  }

  /**
   * レポート情報削除 削除対象は1レコードのみ
   *
   * @param integer $id 削除対象の主キー
   * @return boolean 削除が成功したかどうかを表す値
   */
  public function delete(int $id): bool {
    $sql = "DELETE FROM reports WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $result = $stmt->execute();

    return $result;
  }
}
