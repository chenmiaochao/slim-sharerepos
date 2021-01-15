<?php

namespace LocalHalPH34\ShareReports\Classes\middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use LocalHalPH34\ShareReports\Classes\exception\NoLoginException;

class LoginCheck {
  public function __invoke(Request $request, Handler $handler): Response {
    if(!isset($_SESSION["loginFlg"]) || $_SESSION["loginFlg"] == false || !isset($_SESSION["id"]) || !isset($_SESSION["name"]) || !isset($_SESSION["auth"])) {
      $result = true;
      throw new NoLoginException();
    }
    $response = $handler->handle($request);
    return $response;
  }
}