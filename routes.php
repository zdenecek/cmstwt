<?php

// Routes for the router

use App\Config;
use App\Controller\ErrorController;
use App\Controller\ArticleController;

return [
  ['articles', "GET",  "showAll" . "@" . ArticleController::class],
  ['article/{id}', "GET",  "showDetail" . "@" . ArticleController::class],
  ['article-edit/{id}', "GET",  "showUpdate" . "@" . ArticleController::class],
  ['article-create', "POST",  "create" . "@" . ArticleController::class],
  ['article-edit/{id}', "POST",  "update" . "@" . ArticleController::class],
  ['article/{id}', "DELETE",  "delete" . "@" . ArticleController::class],
  ['test', "GET",  function ($request, $response) {
    $response->text("Hello World")->code(200);
  }],
  ['migrate1', "GET",  function ($request, $response) {
    if(Config::MIGRATE) include 'db/migration1.php';
  }],
  ['migrate2', "GET",  function ($request, $response) {
    if(Config::MIGRATE) include 'db/migration2.php';
  }],
  ['.*', "*",  "notFound" . "@" . ErrorController::class],
];
