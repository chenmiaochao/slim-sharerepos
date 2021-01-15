<?php

namespace LocalHalPH34\ShareReports\Classes\controller;

use PDO;
use PDOException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use LocalHalPH34\ShareReports\Classes\Conf;
use LocalHalPH34\ShareReports\Classes\exception\DataAccessException;
use LocalHalPH34\ShareReports\Classes\entity\Report;
use LocalHalPH34\ShareReports\Classes\entity\Reportcate;
use LocalHalPH34\ShareReports\Classes\dao\ReportcateDAO;
use LocalHalPH34\ShareReports\Classes\dao\ReportDAO;
use LocalHalPH34\ShareReports\Classes\controller\ParentController;
use LocalHalPH34\ShareReports\Classes\dao\UserDAO;

class RepoController extends ParentController{
    //レポートリスト画面表示処理
    public function showRepoList(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $flashMessages = $this->flash->getMessages();
        if(isset($flashMessages)){
            $assign["flashMsg"] = $this->flash->getFirstMessage("flashMsg");
        }
        $this->cleanSession();
        try{
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);
            $usersDAO = new UserDAO($db);
            $repoList = $reportDAO->findAll();
            $userNameList = $usersDAO->findName();
            $userName = ($_SESSION["name"]);
            $assign["userName"] = $userName;
            $assign["userNameList"] = $userNameList;
            $assign["repoList"] = $repoList;
        }
        catch(PDOException $ex){
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        }
        finally {
            $db = null;
        }
        $returnResponse = $this->view->render($response, "/report/list.html", $assign);
        return $returnResponse;
    }

    /**
     * detail表示処理
     */
    public function showDetail(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $flashMessages = $this->flash->getMessages();
        if(isset($flashMessages)){
            $assign["flashMsg"] = $this->flash->getFirstMessage("flashMsg");
        }
        $this->cleanSession();
        $detailId = $args["detailId"];
        $detailId = intval($detailId);
        $userName = ($_SESSION["name"]);
        try{
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);
            $report = $reportDAO->findByPK($detailId);
            $assign["report"] = $report;
            $assign["userName"] = $userName;
        }
        catch(PDOException $ex){
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        }
        finally {
            $db = null;
        }
        $returnResponse = $this->view->render($response, "/report/detail.html", $assign);
        return $returnResponse;
    }

        
    /**
     * レポート情報登録処理画面
     * 
     */
    public function goAdd(ServerRequestInterface $request,ResponseInterface $response, array $args): ResponseInterface{
        $minWorkYear = 1980;
        $userName = ($_SESSION["name"]);
        $addTime["year"] = date("Y");
        $addTime["month"] = date("m");
        $addTime["date"] = date("d");
        $addTimeTo["hour"] = date("H");
        $addTimeTo["minute"] = date("i");

        $assign["userName"] = $userName;
        $assign["addTime"] = $addTime;
        $assign["addTimeTo"] = $addTimeTo;
        $assign["minWorkYear"] = $minWorkYear;

        try{
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportcateDAO = new ReportcateDAO($db);
            $reportcateList = $reportcateDAO->findAll();
            $assign["reportcateList"] = $reportcateList;
        }
        catch(PDOException $ex){
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        }
        finally {
            $db = null;
        }
        $returnResponse = $this->view->render($response, "report/add.html", $assign);
        return $returnResponse;
    }


    /**
     * レポート情報登録処理
     */
    public function add(ServerRequestInterface $request,ResponseInterface $response, array $args): ResponseInterface{
        $templatePath = "reports/add.html";
        $isRedirect = false;
        $assign = [];
        $userName = ($_SESSION["name"]);
        $assign["userName"] = $userName;

        $postParams = $request->getParsedBody();

        $addReportYear = $postParams["addReportYear"];
        $addReportMonth = $postParams["addReportMonth"];
        $addReportDate = $postParams["addReportDate"];
        $addRpTimeFromHour = $postParams["addRpTimeFromHour"];
        $addRpTimeFromMinute = $postParams["addRpTimeFromMinute"];
        $addRpTimeToHour = $postParams["addRpTimeToHour"];
        $addRpTimeToMinute = $postParams["addRpTimeToMinute"];
        $addRpContent = $postParams["addRpContent"];
        $addRpCreatedAt = date("Y-m-d H:i:s");
        $addReportcateId = $postParams["addReportcateId"];
        $addReportcateId = intval($addReportcateId);
        $addUserId = $_SESSION["id"];

        //連結してdb用のフォーマットへ
        $addReportMonth = str_pad($addReportMonth,2,"0",STR_PAD_LEFT);
        $addReportDate = str_pad($addReportDate,2,"0",STR_PAD_LEFT);
        $addRpTimeFromHour = str_pad($addRpTimeFromHour,2,"0",STR_PAD_LEFT);
        $addRpTimeFromMinute = str_pad($addRpTimeFromMinute,2,"0",STR_PAD_LEFT);
        $addRpTimeToHour = str_pad($addRpTimeToHour,2,"0",STR_PAD_LEFT);
        $addRpTimeToMinute = str_pad($addRpTimeToMinute,2,"0",STR_PAD_LEFT);

        $CompareTimeFrom = $addRpTimeFromHour.$addRpTimeFromMinute;
        $CompareTimeTo = $addRpTimeToHour.$addRpTimeToMinute;

        $addRpDate = $addReportYear."-".$addReportMonth."-".$addReportDate;
        $addRpTimeFrom = $addRpTimeFromHour.":".$addRpTimeFromMinute;
        $addRpTimeTo = $addRpTimeToHour.":".$addRpTimeToMinute;


        $report = new Report();
        $report->setRpDate($addRpDate);
        $report->setRpTimeFrom($addRpTimeFrom);
        $report->setRpTimeTo($addRpTimeTo);
        $report->setRpContent($addRpContent);
        $report->setRpCreatedAt($addRpCreatedAt);
        $report->setReportcateId($addReportcateId);
        $report->setUserId($addUserId);


        $validationMsgs = [];
            try {
                $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
                $ReportDAO = new ReportDAO($db);
                $reportcateDAO = new ReportcateDAO($db);
                $reportcateList = $reportcateDAO->findAll();
                $assign["reportcateList"] = $reportcateList;
                if($CompareTimeTo < $CompareTimeFrom){
                    $validationMsgs[] = "開始時間が終わり時間より遅い、もう一度入力してください。";
                    $assign["validationMsgs"] = $validationMsgs;
    
                    $minWorkYear = 1980;
                    $userName = ($_SESSION["name"]);
    
                    $valiTime["year"] = $addReportYear;
                    $valiTime["month"] = $addReportMonth;
                    $valiTime["date"] = $addReportDate;
                    $valiTimeFrom["hour"] = $addRpTimeFromHour;
                    $valiTimeFrom["minute"] = $addRpTimeFromMinute;
                    $valiTimeTo["hour"] = $addRpTimeToHour;
                    $valiTimeTo["minute"] = $addRpTimeToMinute;
                    $valiReportcateId = $addReportcateId;

    
                    $assign["report"] = $report;
                    $assign["userName"] = $userName;
                    $assign["valiTime"] = $valiTime;
                    $assign["addRpCreatedAt"] = $addRpCreatedAt;
                    $assign["valiTimeFrom"] = $valiTimeFrom;
                    $assign["valiTimeTo"] = $valiTimeTo;
                    $assign["minWorkYear"] = $minWorkYear;
                    $assign["valiReportcateId"] = $valiReportcateId;
                }
                if(empty($validationMsgs)){

                    $RpId = $ReportDAO->insert($report);

                    if($RpId === -1){
                        throw new DataAccessException("情報登録に失敗しました。もう一度やり直してください");
                    }
                    else{
                        $isRedirect = true;
                        $this->flash->addMessage('flashMsg', "レポートid".$RpId."でレポート情報を登録しました。");
                    }
                }
                else{
                    $assign["report"] = $report;
                    $assign["validationMsgs"] = $validationMsgs;
                }
            }
            catch(PDOException $ex){
                $exCode = $ex->getCode();
                throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
            }
            finally {
                $db = null;
            }
        
        if($isRedirect) {
            $returnResponse = $response->withStatus(302)->withHeader("Location", "/ph34/sharereports/public/reports/list");
        }
        else{
            $returnResponse = $this->view->render($response, "report/add.html", $assign);
        }
        return $returnResponse;
    }
    /**
     * 編集画面表示
     *
     * 
     */
    public function prepareEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $assign = [];
        $EditRepoId = $args["repoId"];
        $minWorkYear = 1980;
        $userName = ($_SESSION["name"]);

        try{
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);
            $report = $reportDAO->findByPK($EditRepoId);
            $reportcateDAO = new ReportcateDAO($db);
            $reportcateList = $reportcateDAO->findAll();
            $assign["reportcateList"] = $reportcateList;

            if(empty($report)){
                throw new DataAccessException("レポーと情報の取得に失敗しました。");
            }
            else{
                //年月日分割
                $editRpCreatedAt["year"] = substr($report->getRpCreatedAt(), 0,4);
                $editRpCreatedAt["year"] = intval($editRpCreatedAt["year"]);
                $editRpCreatedAt["month"] = substr($report->getRpCreatedAt(), 5,2);
                $editRpCreatedAt["month"] = intval($editRpCreatedAt["month"]);
                $editRpCreatedAt["date"] = substr($report->getRpCreatedAt(), 8,2);
                $editRpCreatedAt["date"] = intval($editRpCreatedAt["date"]);

                $editTimeFrom["hour"] = substr($report->getRpTimeFrom(), 0,2);
                $editTimeFrom["hour"] = intval($editTimeFrom["hour"]);
                $editTimeFrom["minute"] = substr($report->getRpTimeFrom(), 3,2);
                $editTimeFrom["minute"] = intval($editTimeFrom["minute"]);

                $editTimeTo["hour"] = substr($report->getRpTimeTo(), 0,2);
                $editTimeTo["hour"] = intval($editTimeTo["hour"]);
                $editTimeTo["minute"] = substr($report->getRpTimeTo(), 3,2);
                $editTimeTo["minute"] = intval($editTimeTo["minute"]);

                $assign["report"] = $report;
                $assign["userName"] = $userName;
                $assign["editRpCreatedAt"] = $editRpCreatedAt;
                $assign["editTimeFrom"] = $editTimeFrom;
                $assign["editTimeTo"] = $editTimeTo;
                $assign["minWorkYear"] = $minWorkYear;
                
            }
        }
        catch(PDOException $ex){
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        }
        finally {
            $db = null;
        }
        $returnResponse = $this->view->render($response, "report/edit.html", $assign);
        return $returnResponse;


    }
    /**
     * 編集処理
     */
    public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {

        $assign = [];
        $userName = ($_SESSION["name"]);
        $assign["userName"] = $userName;
        $isRedirect = false;
        $postParams = $request->getParsedBody();

        $editRepoId = $postParams["editRpId"];
        $editReportYear = $postParams["editReportYear"];
        $editReportMonth = $postParams["editReportMonth"];
        $editReportDate = $postParams["editReportDate"];
        $editRpTimeFromHour = $postParams["editRpTimeFromHour"];
        $editRpTimeFromMinute = $postParams["editRpTimeFromMinute"];
        $editRpTimeToHour = $postParams["editRpTimeToHour"];
        $editRpTimeToMinute = $postParams["editRpTimeToMinute"];
        $editRpContent = $postParams["editRpContent"];
        $editRpCreatedAt = date("Y-m-d H:i:s");
        $editReportcateId = $postParams["editReportcateId"];
        $editUserId = $_SESSION["id"];


        //連結してdb用のフォーマットへ
        $editReportMonth = str_pad($editReportMonth,2,"0",STR_PAD_LEFT);
        $editReportDate = str_pad($editReportDate,2,"0",STR_PAD_LEFT);
        $editRpTimeFromHour = str_pad($editRpTimeFromHour,2,"0",STR_PAD_LEFT);
        $editRpTimeFromMinute = str_pad($editRpTimeFromMinute,2,"0",STR_PAD_LEFT);
        $editRpTimeToHour = str_pad($editRpTimeToHour,2,"0",STR_PAD_LEFT);
        $editRpTimeToMinute = str_pad($editRpTimeToMinute,2,"0",STR_PAD_LEFT);

        $CompareTimeFrom = $editRpTimeFromHour.$editRpTimeFromMinute;
        $CompareTimeTo = $editRpTimeToHour.$editRpTimeToMinute;

        $editRpDate = $editReportYear."-".$editReportMonth."-".$editReportDate;
        $editRpTimeFrom = $editRpTimeFromHour.":".$editRpTimeFromMinute;
        $editRpTimeTo = $editRpTimeToHour.":".$editRpTimeToMinute;


        $report = new Report();
        $report->setId($editRepoId);
        $report->setRpDate($editRpDate);
        $report->setRpTimeFrom($editRpTimeFrom);
        $report->setRpTimeTo($editRpTimeTo);
        $report->setRpContent($editRpContent);
        $report->setRpCreatedAt($editRpCreatedAt);
        $report->setReportcateId($editReportcateId);
        $report->setUserId($editUserId);

        try{
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);
            $reportcateDAO = new ReportcateDAO($db);
            $reportcateList = $reportcateDAO->findAll();
            $assign["reportcateList"] = $reportcateList;
            //時間バリデーション
            if($CompareTimeTo < $CompareTimeFrom){
                $validationMsgs[] = "開始時間が終わり時間より遅い、もう一度入力してください。";
                $assign["validationMsgs"] = $validationMsgs;

                $minWorkYear = 1980;
                $userName = ($_SESSION["name"]);

                $valiTime["year"] = $editReportYear;
                $valiTime["month"] = $editReportMonth;
                $valiTime["date"] = $editReportDate;
                $valiTimeFrom["hour"] = $editRpTimeFromHour;
                $valiTimeFrom["minute"] = $editRpTimeFromMinute;
                $valiTimeTo["hour"] = $editRpTimeToHour;
                $valiTimeTo["minute"] = $editRpTimeToMinute;


                $assign["report"] = $report;
                $assign["userName"] = $userName;
                $assign["valiTime"] = $valiTime;
                $assign["editRpCreatedAt"] = $editRpCreatedAt;
                $assign["valiTimeFrom"] = $valiTimeFrom;
                $assign["valiTimeTo"] = $valiTimeTo;
                $assign["minWorkYear"] = $minWorkYear;
            }
            if(empty($validationMsgs)){
                $result = $reportDAO->update($report);
                if(!$result){
                    throw new DataAccessException("レポート情報の更新に失敗しました。");
                }
                else{
                    $isRedirect = true;
                    $this->flash->addMessage('flashMsg', "レポートid".$editRepoId."でレポート情報を更新しました。");
                }
            }
        }
        catch(PDOException $ex){
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        }
        finally {
            $db = null;
        }
        if($isRedirect){
            $returnResponse = $response->withStatus(200)->withHeader("Location", "/ph34/sharereports/public/reports/detail/".$editRepoId."");
        }
        else{
            $returnResponse = $this->view->render($response,"report/edit.html", $assign);
        }


        return $returnResponse;
    }
        /**
     * delete画面表示
     *
     * 
     */
    public function confirmDelete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $assign = [];
        $userName = ($_SESSION["name"]);
        $assign["userName"] = $userName;
        $DeleteRepoId = $args["repoId"];

        $minWorkYear = 1980;
        $userName = ($_SESSION["name"]);


        try{
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);
            $report = $reportDAO->findByPK($DeleteRepoId);
            $reportcateDAO = new ReportcateDAO($db);
            $reportcate = $reportcateDAO->findByPK($report->getReportcateId());
            if(empty($report)){
                throw new DataAccessException("レポーと情報の取得に失敗しました。");
            }
            else{
                //年月日分割
                $deleteRpCreatedAt["year"] = substr($report->getRpCreatedAt(), 0,4);
                $deleteRpCreatedAt["year"] = intval($deleteRpCreatedAt["year"]);
                $deleteRpCreatedAt["month"] = substr($report->getRpCreatedAt(), 5,2);
                $deleteRpCreatedAt["month"] = intval($deleteRpCreatedAt["month"]);
                $deleteRpCreatedAt["date"] = substr($report->getRpCreatedAt(), 8,2);
                $deleteRpCreatedAt["date"] = intval($deleteRpCreatedAt["date"]);

                $deleteTimeFrom["hour"] = substr($report->getRpTimeFrom(), 0,2);
                $deleteTimeFrom["hour"] = intval($deleteTimeFrom["hour"]);
                $deleteTimeFrom["minute"] = substr($report->getRpTimeFrom(), 3,2);
                $deleteTimeFrom["minute"] = intval($deleteTimeFrom["minute"]);

                $deleteTimeTo["hour"] = substr($report->getRpTimeTo(), 0,2);
                $deleteTimeTo["hour"] = intval($deleteTimeTo["hour"]);
                $deleteTimeTo["minute"] = substr($report->getRpTimeTo(), 3,2);
                $deleteTimeTo["minute"] = intval($deleteTimeTo["minute"]);

                $assign["reportcate"] = $reportcate;
                $assign["report"] = $report;
                $assign["userName"] = $userName;
                $assign["deleteRpCreatedAt"] = $deleteRpCreatedAt;
                $assign["deleteTimeFrom"] = $deleteTimeFrom;
                $assign["deleteTimeTo"] = $deleteTimeTo;
                $assign["minWorkYear"] = $minWorkYear;
                
            }
        }
        catch(PDOException $ex){
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        }
        finally {
            $db = null;
        }
        $returnResponse = $this->view->render($response, "report/confirmDelete.html", $assign);
        return $returnResponse;


    }
    /**
     * 削除！
     */
    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $postParams = $request->getParsedBody();
        $deleteRpId = $postParams["deleteRpId"];
        $deleteRpId = intval($deleteRpId);

        try{
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $ReportDAO = new ReportDAO($db);
            $result = $ReportDAO->delete($deleteRpId);
            if($result){
                $this->flash->addMessage("flashMsg", "レポートid".$deleteRpId."の情報を削除しました");
            }
            else{
                throw new DataAccessException("レポート削除失敗しました。もう一度初めからやり直してください。");
            }
        }
        catch(PDOException $ex){
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        }
        finally{
            $db = null;
        }
        $returnResponse = $response->withStatus(200)->withHeader("Location", "/ph34/sharereports/public/reports/list");
        return $returnResponse;
    }

}

