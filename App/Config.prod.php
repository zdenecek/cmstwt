<?php

namespace App;

class Config {

  const DEBUG = false;
  
  const BASE_URI = "/~78002598/cms/";
  //const BASE_URI = "/";
  // leave empty for default mysql connection with config in db_config.php
  const CONN_STRING = "";
  // use this for sqlite on local
  //const CONN_STRING = "sqlite:db.db";
  const PAGE_404 = "404";

  const MIGRATE = false;

}