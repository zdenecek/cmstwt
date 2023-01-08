<?php

namespace App;

use Exception;
use ReflectionFunction;
use ReflectionMethod;

class Router
{
    private array $routes = [
      'GET' => [],
      'POST' => [],
      'PUT' => [],
      'DELETE' => [],
    ];

    public function __construct()
    {
    }

    public function route($method, $uri)
    {
      

      App::debug("routing $method $uri");

      $routes = $this->routes[$method];

      foreach ($routes as $route) {
      App::debug("matching $route->regexp");
        

        if (preg_match($route->regexp, $uri, $matches)) {

          App::debug("matched route:");
          App::debug($route);


          $args = [];

          for ($i = 1; $i < count($matches); $i++) {
            $args[$route->params[$i - 1]] = $matches[$i];
          }

          return new Request($route, $args, $uri);
        }
      }

      throw new Exception('Route not found');
    }

    public function registerRoutes(array $routes)
    {
      foreach ($routes as $route) {
        $this->registerRoute($route[1], $route[0], $route[2]);
      }
    }

    public function registerRoute(string $method, string $uri, $handler)
    {
      assert(in_array($method, ['GET', 'POST', 'PUT', 'DELETE', '*']));
      assert(is_callable($handler) || is_string($handler));

      $paramRegexp = "'\{([a-zA-Z]+)\}'";

      $uri = Config::BASE_URI . $uri;

      $paramCount = preg_match_all($paramRegexp, $uri, $matches);
      $params = [];

      
      for ($i = 1; $i <= $paramCount; $i++) {
        $params[] = $matches[$i][0];
      }

      $regexp = "'^" . preg_replace('/{([a-zA-Z]+)\}/', '([^/]+)', $uri) . "$'";

      $handler =  $this->parseHandler($handler, $controller);

      $route = new Route($uri, $regexp, $method, $controller, $handler, $params);
      if($method === "*") {
        foreach ($this->routes as $key => $_) {
          $this->routes[$key][] = $route;
        }
      } else {
        $this->routes[$method][] = $route;
      }

      return $route;
    }

    private function parseHandler($handler, &$controller ) {
      $controller = null;

      if (is_string($handler)) {
        $handler = explode('@', $handler);
        $controller = $handler[1];
        $action = $handler[0];

        if (!class_exists($controller) || !method_exists($controller, $action)) {

          throw new Exception('Invalid handler');
        }

        $handler = new ReflectionMethod($controller, $action);
      }
      else {
        $handler = new ReflectionFunction($handler);
      }

      return $handler;
    }
}
