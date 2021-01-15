<?php
/**
 * Ph34 sample slim src03
 */
use LocalHalPH34\ShareReports\Classes\exception\CustomErrorRenderer;

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->registerErrorRenderer("text/html", CustomErrorRenderer::class);