<?php

use LocalHalPH34\ShareReports\Classes\middleware\LoginCheck;
use LocalHalPH34\ShareReports\Classes\controller\LoginController;
use LocalHalPH34\ShareReports\Classes\controller\TopController;
use LocalHalPH34\ShareReports\Classes\controller\RepoController;

$app->setBasePath("/ph34/sharereports/public");
$app->get("/", LoginController::class.":goLogin");
$app->post("/login", LoginController::class.":login");
$app->get("/logout", LoginController::class.":logout");
$app->get("/goTop", TopController::class.":goTop")->add(new LoginCheck());
$app->get("/reports/list", RepoController::class.":showRepoList")->add(new LoginCheck());
$app->get("/reports/detail/{detailId}", RepoController::class.":showDetail")->add(new LoginCheck());
$app->get("/reports/goAdd", RepoController::class.":goAdd")->add(new LoginCheck());
$app->post("/reports/add", RepoController::class.":add")->add(new LoginCheck());
$app->get("/reports/prepareEdit/{repoId}", RepoController::class.":prepareEdit")->add(new LoginCheck());
$app->post("/reports/edit", RepoController::class.":edit")->add(new LoginCheck());
$app->get("/reports/confirmDelete/{repoId}", RepoController::class.":confirmDelete")->add(new LoginCheck());
$app->post("/reports/delete", RepoController::class.":delete")->add(new LoginCheck());

