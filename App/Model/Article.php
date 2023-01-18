<?php

namespace App\Model;


class Article {

  public string $id;
  public string $name;
  public string $content;
  public int $likes;

  public function __construct($id, $name, $content, $likes) {
    $this->id = $id;
    $this->name = $name;
    $this->content = $content;
    $this->likes = $likes;
  }
}