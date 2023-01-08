<?php

namespace App;
class Request
{
    public $route;
    public $url;
    public $routeArgs;
    public $data = [];

    public function __construct($route, $routeArgs, $url)
    {
        $this->route = $route;
        $this->routeArgs = $routeArgs;
        $this->url = $url;
    }
        
}
