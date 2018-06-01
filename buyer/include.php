<?php

$rootPath = $_SERVER["DOCUMENT_ROOT"] . "/sheralibaba";
$buyerPath = $rootPath . "/buyer";

require_once $rootPath . "/src/session.php";
require_once $rootPath . "/src/db_connection.php";
require_once $rootPath . "/src/functions.php";

if (!authenticateBuyer()) {
  redirect("../login.php");
}