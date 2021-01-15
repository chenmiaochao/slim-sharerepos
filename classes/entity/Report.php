<?php

namespace LocalHalPH34\ShareReports\Classes\entity;

/**
 * レポートエンティティクラス
 */
class Report {
  /**
   * 主キーのid
   */
  private $id;
  /**
   * 作業日
   */
  private $rpDate;
  /**
   * 作業開始時間
   */
  private $rpTimeFrom;
  /**
   * 作業終了時間
   */
  private $rpTimeTo;
  /**
   * 作業内容
   */
  private $rpContent;
  /**
   * 登録日時
   */
  private $rpCreatedAt;
  /**
   * 作業種類ID
   */
  private $reportcateId;
  /**
   * 報告者ID
   */
  private $userId;

  // 以下アクセサメソッド
  public function getId(): ?int {
    return $this->id;
  }
  public function setId(int $id): void {
    $this->id = $id;
  }

  public function getRpDate(): ?string {
    return $this->rpDate;
  }
  public function setRpDate(string $rpDate): void {
    $this->rpDate = $rpDate;
  }

  public function getRpTimeFrom(): ?string {
    return $this->rpTimeFrom;
  }
  public function setRpTimeFrom(string $rpTimeFrom): void {
    $this->rpTimeFrom = $rpTimeFrom;
  }

  public function getRpTimeTo(): ?string {
    return $this->rpTimeTo;
  }
  public function setRpTimeTo(string $rpTimeTo): void {
    $this->rpTimeTo = $rpTimeTo;
  }

  public function getRpContent(): ?string {
    return $this->rpContent;
  }
  public function setRpContent(string $rpContent): void {
    $this->rpContent = $rpContent;
  }

  public function getRpCreatedAt(): ?string {
    return $this->rpCreatedAt;
  }
  public function setRpCreatedAt(string $rpCreatedAt): void {
    $this->rpCreatedAt = $rpCreatedAt;
  }

  public function getReportcateId(): ?int {
    return $this->reportcateId;
  }
  public function setReportcateId(int $reportcateId): void {
    $this->reportcateId = $reportcateId;
  }

  public function getUserId(): ?int {
    return $this->userId;
  }
  public function setUserId(int $userId): void {
    $this->userId = $userId;
  }
}