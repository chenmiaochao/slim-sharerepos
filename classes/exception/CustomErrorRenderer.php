<?php

namespace LocalHalPH34\ShareReports\Classes\exception;

use Throwable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Error\Renderers\HtmlErrorRenderer;
use Slim\Views\Twig;

use LocalHalPH34\ShareReports\Classes\exception\NoLoginException;
use LocalHalPH34\ShareReports\Classes\exception\DataAccessException;

class CustomErrorRenderer {
  public function __invoke(Throwable $exception, bool $displayErrorDetails): string {
    $view = Twig::create($_SERVER["DOCUMENT_ROOT"]."/ph34/sharereports/templates");
    if($exception instanceof NoLoginException) {
      $validationMsgs[] = "ログインしていないか、前回ログインしてから一定期間が経過しています。もう一度ログインし直してください。";
      $assign["validationMsgs"] = $validationMsgs;
      $returnHtml = $view->fetch("login.html", $assign);
    }
    elseif($exception instanceof DataAccessException) {
      $assign["errorMsg"] = $exception->getMessage();
      $returnHtml = $view->fetch("error.html", $assign);
    }
    else {
      $htmlErrorRenderer = new HtmlErrorRenderer();
      $returnHtml = $htmlErrorRenderer($exception, $displayErrorDetails);
    }
    return $returnHtml;
  }
}