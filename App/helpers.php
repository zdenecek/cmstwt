<?php

use App\Config;

function image($path) {
  echo Config::BASE_URI . "resources/img/$path";
}

function url($url) {
  echo Config::BASE_URI . $url;
}

function script($path) {
  echo '<script src="' .  Config::BASE_URI . 'resources/js/' . $path . '"></script>';
}



function json($data) {
  echo htmlspecialchars(json_encode($data));
}