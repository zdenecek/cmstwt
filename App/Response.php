<?php

namespace App;

class Response
{
  const CODE = 'code';
  const REDIRECT = 'redirect';
  const REDIRECT_SOFT = 'redirect_soft';
  const JSON = 'json';
  const HTML = 'html';
  const TEXT = 'text';

  public $type;
  public $action;
  private $code;
  public $redirectUrl;
  public $redirectMethod;
  public $data;

  public function __construct() {
    $this->code(200);
    $this->action = fn() => http_response_code(200);
  }

  public function failImmediately($code = 500) {
    http_response_code($code);
    die;
  }

  public function send() {
    call_user_func($this->action);
  }

  public function code(int $code)
  {
    $this->type = self::CODE;
    $this->code = $code;

    return $this;

  }

  private function setRedirectUrl($url) {
    $this->redirectUrl = Config::BASE_URI . $url;
  }

  public function redirectHard(string $uri, int $code = 302) {
    if($code !== null) $code = $this->code;
    $this->type = self::REDIRECT;
    $this->setRedirectUrl($uri);
    $this->action = function()  {
      http_response_code($this->code);
      header("Location: $this->redirectUrl");
    };

    return $this;
  } 

  public function with($data) {
    $this->data = $data;
    return $this;
  }

  public function redirect(string $uri, $method = "GET") {
    $this->type = self::REDIRECT_SOFT;
    $this->setRedirectUrl($uri);
    $method = strtoupper($method);
    $this->action = function() {
      throw new \Exception("Soft redirect should not be sent.");
    };

    return $this;
  }

  public function json($data, int $code = null) {
    $this->type = self::JSON;
    if($code !== null) $this->code = $code;
    $this->action = function() use ($data) {
      http_response_code($this->code);
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($data);
    };

    return $this;

  }

  public function view(View $view) {
    $this->type = self::HTML;
    $this->action = function() use ($view) {
      http_response_code($this->code);
      header('Content-Type: text/html; charset=utf-8');
      $view->render();
    };

    return $this;

  }

  public function text($text) {
    $this->type = self::TEXT;
    $this->action = function() use ($text) {
      http_response_code($this->code);
      header('Content-Type: text/html; charset=utf-8');
      echo preg_replace('/\n/', '<br>', $text);
    };

    return $this;
  }

  public function notFound() {
    $this->view(View::Create(Config::PAGE_404));

    return $this;

  }
}