<?php

namespace LocalHalPH34\ShareReports\Classes\entity;

class Reportcate {
  /**
   * 主キーのid
   */
  private $id;
  /**
   * 種類名
   */
  private $rcName;
  /**
   * 備考
   */
  private $rcNote;
  /**
   * リスト表示の有無
   */
  private $rcListFlg;
  /**
   * 表示順序
   */
  private $rcOrder;

  // 以下アクセサメソッド
  public function getId(): ?int {
    return $this->id;
  }
  public function setId(int $id): void {
    $this->id = $id;
  }

  public function getRcName(): ?string {
    return $this->rcName;
  }
  public function setRcName(string $rcName): void {
    $this->rcName = $rcName;
  }

  public function getRcNote(): ?string {
    return $this->rcNote;
  }
  public function setRcNote(string $rcNote): void {
    $this->rcNote = $rcNote;
  }

  public function getRcListFlg(): ?int {
    return $this->rcListFlg;
  }
  public function setRcListFlg(int $rcListFlg): void {
    $this->rcListFlg = $rcListFlg;
  }

  public function getRcOrder(): ?int {
    return $this->rcOrder;
  }
  public function setRcOrder(int $rcOrder): void {
    $this->rcOrder = $rcOrder;
  }
}