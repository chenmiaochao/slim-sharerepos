<?php

namespace LocalHalPH34\ShareReports\Classes\entity;

/**
 * ユーザエンティティクラス
 */
class User {
  /**
   * 主キーのid
   */
  private $id;
  /**
   * メールアドレス(ログインに使用)
   */
  private $usMail;
  /**
   * 名前
   */
  private $usName;
  /**
   * パスワード
   */
  private $usPassword;
  /**
   * 権限
   */
  private $usAuth;

  // 以下アクセサメソッド
  public function getId(): ?int {
    return $this->id;
  }
  public function setId(int $id): void {
    $this->id = $id;
  }

  public function getUsMail(): ?string {
    return $this->usMail;
  }
  public function setUsMail(string $usMail): void {
    $this->usMail = $usMail;
  }

  public function getUsName(): ?string {
    return $this->usName;
  }
  public function setUsName(string $usName): void {
    $this->usName = $usName;
  }

  public function getUsPassword(): ?string {
    return $this->usPassword;
  }
  public function setUsPassword(string $usPassword): void {
    $this->usPassword = $usPassword;
  }

  public function getUsAuth(): ?int {
    return $this->usAuth;
  }
  public function setUsAuth(int $usAuth): void {
    $this->usAuth = $usAuth;
  }
}