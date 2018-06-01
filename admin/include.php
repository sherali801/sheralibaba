<?php

$rootPath = $_SERVER["DOCUMENT_ROOT"] . "/sheralibaba";
$adminPath = $rootPath . "/admin";

require_once $rootPath . "/src/session.php";
require_once $rootPath . "/src/db_connection.php";
require_once $rootPath . "/src/functions.php";

if (!authenticateAdmin()) {
  redirect("../login.php");
}