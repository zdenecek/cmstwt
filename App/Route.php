<?php

namespace App;

class Route {
  public  $handler;
  public  $method;
  public  $uri;
  public $params; 
  public $regexp; 
  public $controller;

  function __construct($uri, $regexp,  $method, $controller, $handler, $params) {
    $this->uri = $uri;
    $this->handler = $handler;
    $this->params = $params;
    $this->method = $method;
    $this->regexp = $regexp;
    $this->controller = $controller;
  }

}
