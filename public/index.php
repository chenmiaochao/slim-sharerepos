<?php
/**
 * ph34 sample13 src05
 */
use Slim\Factory\AppFactory;

require_once($_SERVER["DOCUMENT_ROOT"]."/ph34/sharereports/vendor/autoload.php");

$app = AppFactory::create();

require_once($_SERVER["DOCUMENT_ROOT"]."/ph34/sharereports/bootstrappers.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/ph34/sharereports/routes.php");

$app->run();