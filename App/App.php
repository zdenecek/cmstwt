<?php

namespace App;

class App
{
    public static function debug($string)
    {
        if (!Config::DEBUG) {
            return;
        }
        echo '<pre class="debug">';
        print_r($string);
        echo "</pre>";
    }

    private $router;
    private $database;

    public function run()
    {
        $this->database = include("./db.php");

        $this->router = new Router();
        $this->router->registerRoutes(include("./routes.php"));

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        $this->handle($method, $uri);
    }


    private function handle($method, $uri) {
      $request = $this->router->route($method, $uri);
      $request->data = $_REQUEST;

      $route = $request->route;

      $response = new Response();
      // for now assume route args have correct order. Could be solved with reflection
      $request->routeArgs = array_merge(['request' => $request, 'response' => $response], $request->routeArgs);

      if ($route->controller !== null) {
          $controller = new $route->controller($this->database);
          $route->handler->invokeArgs($controller, $request->routeArgs);
      } else {
          $route->handler->invokeArgs($request->routeArgs);
      }

      if($response->type === Response::REDIRECT_SOFT) {
        $this->handle($response->redirectMethod, $response->redirectUrl);
      }
      else {
        $response->send();
      }
    }
}
