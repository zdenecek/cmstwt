<?php

use App\Config;

include 'db_config.php';

if(strlen(Config::CONN_STRING) != 0)
{
  $connString = Config::CONN_STRING;
}
else  {
  $connString = 'mysql:dbname=' . $db_config['database'] . ';host=' . $db_config['server'] . ';charset=utf8mb4';
}

$db = new PDO($connString, $db_config['login'], $db_config['password']);
  
return $db;