<?php

$db = include('db.php');

$db->exec('CREATE TABLE IF NOT EXISTS articles (
  id INTEGER AUTO_INCREMENT,
  name TEXT,
  content TEXT,
  PRIMARY KEY(id)
)');
