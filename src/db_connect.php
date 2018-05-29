<?php

require_once "config.php";

try {
	$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	if ($db->error) {
		require_once "../404.php";
		exit(1);
	}
} catch (Exception $e) {
	require_once "../404.php";
	exit(1);
}