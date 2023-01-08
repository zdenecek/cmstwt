<?php

namespace App\Service;

use App\App;
use App\Model\Article;

class ArticleService {

  private $database;
  
  function __construct($database) {
    $this->database = $database;
  }

  public function getAll(): array {

    $sth = $this->database->prepare("SELECT id, name, content FROM articles");
    $sth->execute();

    $result = $sth->fetchAll();

    $articles = array_map(fn ($res) => new Article($res['id'], $res['name'], $res['content'] ?? ''), $result);

    App::debug(json_encode(array_map(fn ($article) => $article->id, $articles)));

    return $articles;
  }

  public function getOne($id): ?Article {

    $sth = $this->database->prepare("SELECT name, content FROM articles WHERE id = :id");
    $sth->bindValue(':id', $id);
    $sth->execute();

    $res = $sth->fetch();

    App::debug($res);

    if(!$res) {
      return null;
    }

    return new Article($id, $res['name'], $res['content'] ?? '');
  }

  public function deleteOne($id): bool {

    $sth = $this->database->prepare("DELETE FROM articles WHERE id = :id");
    $sth->bindValue(':id', $id);
    $sth->execute();

    return  $sth->rowCount() > 0;
  }

  public function updateOne($article) {
    $sth = $this->database->prepare("UPDATE articles SET name = :name, content = :content WHERE id = :id");
    $sth->bindValue(':id', $article->id);
    $sth->bindValue(':name', $article->name);
    $sth->bindValue(':content', $article->content);
    $sth->execute();
  }

  public function createOne($name) {
    $stm = $this->database->prepare('INSERT INTO `articles` (`name`) VALUES (:name)');
    $stm->bindValue(':name', $name, \PDO::PARAM_STR);
    $stm->execute();

    $id = $this->database->lastInsertId();

    return new Article($id, $name, '');
  }


}