<?php

namespace App\Model;


class Article {

  public string $id;
  public string $name;
  public string $content;

  public function __construct($id, $name, $content) {
    $this->id = $id;
    $this->name = $name;
    $this->content = $content;
  }
}