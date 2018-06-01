<?php

$rootPath = $_SERVER["DOCUMENT_ROOT"] . "/sheralibaba";
$manufacturerPath = $rootPath . "/manufacturer";

require_once $rootPath . "/src/session.php";
require_once $rootPath . "/src/db_connection.php";
require_once $rootPath . "/src/functions.php";

if (!authenticateManufacturer()) {
	redirect("../login.php");
}