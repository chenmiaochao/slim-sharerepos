<?php
/**  
 * ph34 sample13 src15
*/
namespace LocalHalPH34\ShareReports\Classes\controller;

use Slim\Views\Twig;
use Slim\Flash\Messages;

/**
 * 各コントローラーの親クラス
 * 共通メソッドを記述
 */
class ParentController {
    /**
     * テンプレーど描画に使用するTwigインスタンス
     */
    protected $view;
    /**
     * フラッシュメッセージに利用するMessageインスタンス
     */
    protected $flash;
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->view = Twig::create($_SERVER["DOCUMENT_ROOT"]."/ph34/sharereports/templates");
        $this->flash = new Messages();
        
    }
    public function cleanSession():void {
        $loginFlg = $_SESSION["loginFlg"];
        $id = $_SESSION["id"];
        $mail = $_SESSION["mail"];
        $name = $_SESSION["name"];
        $auth = $_SESSION["auth"];

        session_unset();

        $_SESSION["loginFlg"] = $loginFlg;
        $_SESSION["id"] = $id;
        $_SESSION["mail"] = $mail;
        $_SESSION["name"] = $name;
        $_SESSION["auth"] = $auth;
    }
}