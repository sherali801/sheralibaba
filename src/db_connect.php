<?php

require_once __DIR__ . "/config.php";

try {
  $db = new mysqli(DB_SERVER, DB_USER, DB_PWD, DB_NAME);
  if ($db->error) {
    die("Please Try Again Later.");
  }
} catch (Exception $e) {
  die("Please Try Again Later.");
}

?>
