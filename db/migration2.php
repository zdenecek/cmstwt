<?php

$db = include('db.php');

$db->exec('ALTER TABLE articles
  ADD likes INTEGER DEFAULT 0;
');
