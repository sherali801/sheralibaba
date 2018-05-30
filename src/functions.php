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
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result);
		if ($row["username"] == $username && password_verify($password, $row["pwd"])) {
			$_SESSION["id"] = $row["id"];
			$_SESSION["username"] = $row["username"];
			$_SESSION["role"] = $row["role"];
			return true;
		}
	}
	$_SESSION["errors"][] = "Username/Password combination doesn't match.";
	return false;
}

function getAdminProfile($id) {
	global $conn;
	$sql = "SELECT user.id userId, user.username username, admin.id adminId, admin.first_name firstName, admin.last_name lastName, admin.contact_no contactNo, admin.email email, address.id addressId, address.street street, address.city city, address.state state, address.country country, address.zip zip
			FROM user, admin, address
			WHERE user.id = {$id}
			AND user.role = 1 
			AND user.role_id = admin.id
			AND user.address_id = address.id";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	return null;
}

function duplicateUsernameWithId($username, $id) {
	global $conn;
	$sql = "SELECT COUNT(*)
			FROM user 
			WHERE username = '{$username}' 
			AND id != {$id}";
	if ($result = mysqli_query($conn, $sql)) {
		$result = mysqli_fetch_row($result);
		$result = array_shift($result);
		if ($result) {
			return false;
		}
	}
	return true;
}

function updateAdminProfile($id, $username, $password, $adminId, $firstName, $lastName, $email, $contactNo, $addressId, $street, $city, $state, $country, $zip, $dt) {
	global $conn;
	$status = false;
	$conn->autocommit(false);
	if (updateAddress($addressId, $street, $city, $state, $country, $zip, $dt)) {
		if (updateAdmin($adminId, $firstName, $lastName, $email, $contactNo, $dt)) {
			if (updateUser($id, $username, $password, $dt)) {
				$status = true;
			}
		}
	}
	$conn->autocommit(true);
	return $status;
}

function updateUser($id, $username, $password, $dt) {
	global $conn;
	$sql = "UPDATE user SET 
			username = '{$username}',
			pwd = '{$password}',
			modified_date = '{$dt}'
			WHERE id = {$id}";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) >= 0;
}

function updateAdmin($id, $firstName, $lastName, $email, $contactNo, $dt) {
	global $conn;
	$sql = "UPDATE admin SET 
			first_name = '{$firstName}',
			last_name = '{$lastName}',
			email = '{$email}',
			contact_no = '{$contactNo}', 
			modified_date = '{$dt}'
			WHERE id = {$id}";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) >= 0;
}

function updateAddress($id, $street, $city, $state, $country, $zip, $dt) {
	global $conn;
	$sql = "UPDATE address SET 
			street = '{$street}',
			city = '{$city}',
			state = '{$state}',
			country = '{$country}',
			zip = '{$zip}', 
			modified_date = '{$dt}'
			WHERE id = {$id}";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) >= 0;
}

function duplicateCategoryName($categoryName) {
	global $conn;
	$sql = "SELECT *
			FROM category 
			WHERE category_name = '{$categoryName}'";
	$result = mysqli_query($conn, $sql);
	return mysqli_num_rows($result) == 0;
}

function addNewCategory($categoryName, $adminId, $dt) {
	global $conn;
	$sql = "INSERT INTO category (
			category_name, created_date, modified_date, admin_id
			) VALUES (
			'{$categoryName}', '{$dt}', '{$dt}', {$adminId}
			)";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) == 1;
}

function getAllCategoriesByAdminId($adminId) {
	global $conn;
	$sql = "SELECT *
			FROM category
			WHERE admin_id = {$adminId}";
	if ($result = mysqli_query($conn, $sql)) {
		$categories = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$categories[] = $row;
		}
		return $categories;
	}
	return null;
}

function getCategoryByIdWithAdminId($id, $adminId) {
	global $conn;
	$sql = "SELECT category.id id, category.category_name categoryName
			FROM category
			WHERE category.id = {$id}
			AND category.admin_id = {$adminId}";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	return null;
}

function duplicateCategoryNameWithId($categoryName, $id) {
	global $conn;
	$sql = "SELECT *
			FROM category 
			WHERE category_name = '{$categoryName}' 
			AND id != {$id}";
	$result = mysqli_query($conn, $sql);
	return mysqli_num_rows($result) == 0;
}

function updateCategory($id, $categoryName, $adminId, $dt) {
	global $conn;
	$sql = "UPDATE category SET 
			category_name = '{$categoryName}',
			modified_date = '{$dt}'
			WHERE id = {$id}
			AND admin_id = {$adminId}";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) >= 0;
}