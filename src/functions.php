<?php

require_once "db_connection.php";
require_once "session.php";

function MySqlFormattedTime($dt) {
  return strftime("%Y-%m-%d %H:%M:%S", $dt);
}

function redirect($to) {
	header("Location: " . $to);
	exit(0);
}

function array_remove($element, $array) {
  $index = array_search($element, $array);
  array_splice($array, $index, 1);
  return $array;
}

function authenticateUser() {
	if (!isset($_SESSION["id"])) {
		return false;
	}
	return true;
}

function authenticateAdmin() {
	if (!authenticateUser()) {
		return false;
	}
	if (!isset($_SESSION["role"])) {
		return false;
	}
	return $_SESSION["role"] == 1;
}

function validateUser($username, $password) {
	global $conn;
	$sql = "SELECT * 
          FROM user 
          WHERE username = '{$username}'";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		while ($row = mysqli_fetch_assoc($result)) {
			if ($row["username"] == $username && password_verify($password, $row["pwd"])) {
				$_SESSION["id"] = $row["id"];
				$_SESSION["username"] = $row["username"];
				$_SESSION["role"] = $row["role"];
				return true;
			}
		}
	}
	$_SESSION["errors"][] = "Username/Password combination doesn't match.";
	return false;
}