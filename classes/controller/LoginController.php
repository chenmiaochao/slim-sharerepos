<?php
/**
 * ph34 sample13 src16
 */
namespace LocalHalPH34\ShareReports\Classes\controller;

use PDO;
use PDOException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use LocalHalPH34\ShareReports\Classes\Conf;
use LocalHalPH34\ShareReports\Classes\exception\DataAccessException;
use LocalHalPH34\ShareReports\Classes\dao\UserDAO;
use LocalHalPH34\ShareReports\Classes\entity\User;
use LocalHalPH34\ShareReports\Classes\controller\ParentController;


class LoginController extends ParentController{
    /**
     * ログイン画面処理
     *
     */
    public function goLogin(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $returnResponse = $this->view->render($response, "login.html");
        return $returnResponse;
    }
    /**
     * ログイン・ログアウトに関するコンストローラー
     * 
     */
    public function login(ServerRequestInterface $request , ResponseInterface $response, array $args): ResponseInterface {
        $isRedirect = false;
        $templatePath = "login.html";
        $assign = [];

        $postParams = $request->getParsedBody();
        $loginEmail = $postParams["loginEmail"];
        $loginPw = $postParams["loginPw"];

        $loginEmail = trim($loginEmail);
        $loginPw = trim($loginPw);

        $validationMsgs = [];
        if(empty($validationMsgs)){
            try {
                $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
                $userDAO = new UserDAO($db);
            
                $user = $userDAO->findByLoginMail($loginEmail);
                if($user == null){
                    $validationMsgs[] = "存在しないidです。正しいidを入力してください。";
                }
                else{
                    $userPw = $user->getUsPassword();
                    if(password_verify($loginPw, $userPw)){
                        $id = $user->getId();
                        $name = $user->getUsName();
                        $mail = $user->getUsMail();
                        $_SESSION["loginFlg"] = true;
                        $_SESSION["mail"] = $mail;
                        $_SESSION["id"] = $id;
                        $_SESSION["name"] = $name;
                        $_SESSION["auth"] = 1;
                        $isRedirect = true;
            
                    }
                    else{
                        $validationMsgs[] = "パスワードが違います。正しいパスワードを入力してください。";
                    }
                }
            }
            catch(PDOException $ex){
                var_dump($ex);
                $assign["errorMsg"] = "DB接続失敗";
                $templatePath = "error.html";
            }
            finally {
                $db = null;
            }
        }

        if($isRedirect) {
            $returnResponse = $response->withStatus(302)->withHeader("Location", "/ph34/sharereports/public/reports/list");
        }
        else{
            if(!empty($validationMsgs)){
                $assign["validationMsgs"] = $validationMsgs;
                $assign["loginEmail"] = $loginEmail;
            }
            $returnResponse = $this->view->render($response, $templatePath, $assign);
        }       
        return $returnResponse; 
    }

    /**
     * ログアウト
     */
    public function logout(ServerRequestInterface $request, ResponseInterface $response , array $args): ResponseInterface{
        session_destroy();
        $returnResponse = $response->withStatus(302)->withHeader("Location", "/ph34/sharereports/public/");
        return $returnResponse;
    }
}
