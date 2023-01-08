<?php

namespace App;

class View
{
    private $template;
    private $args;

    function __construct(string $template, array $args)
    {
        $this->template = $template;
        $this->args = $args;
    }

    public static function create(string $template, array $args = [])
    {
        return new self($template, $args);
    }


    public function render(): void
    {
        
        extract($this->args);
        include 'App/helpers.php';

        ob_start();
        include 'resources/views/base.phtml';
        $capture = ob_get_clean();

        ob_start();
        include 'resources/views/' . $this->template . '.phtml';
        $body = ob_get_clean();

        echo str_replace("{{ BODY }}", $body, $capture);
    }
}
