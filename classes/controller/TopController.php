<?php
/**
 * ph34 src17
 */
namespace LocalHalPH34\ShareReports\Classes\controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use LocalHalPH34\ShareReports\Classes\controller\ParentController;

/**
 * top controller
 */
class TopController extends ParentController{
    /**
     * top画面表示処理
     */
    public function goTop(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $this->cleanSession();
        $returnResponse = $this->view->render($response, "report/list.html");
        return $returnResponse;
    }
}