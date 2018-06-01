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

function lastInsertId() {
	global $conn;
	return mysqli_insert_id($conn);
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

function authenticateBuyer() {
	if (!authenticateUser()) {
		return false;
	}
	if (!isset($_SESSION["role"])) {
		return false;
	}
	return $_SESSION["role"] == 3;
}

function authenticateManufacturer() {
	if (!authenticateUser()) {
		return false;
	}
	if (!isset($_SESSION["role"])) {
		return false;
	}
	return $_SESSION["role"] == 2;
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

function duplicateUsername($username) {
	global $conn;
	$count = 0;
	$sql = "SELECT *
			FROM user 
			WHERE username = '{$username}'";
	if ($result = mysqli_query($conn, $sql)) {
		$count = mysqli_num_rows($result);
	}
	return $count == 0;
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

function getCategoryById($id) {
	global $conn;
	$sql = "SELECT *
			FROM category
			WHERE category.id = {$id}";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	return null;
}

function getManufactuerById($id) {
	global $conn;
	$sql = "SELECT *
			FROM manufacturer
			WHERE manufacturer.id = {$id}";
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

function getManufacturerProfile($id) {
	global $conn;
	$sql = "SELECT user.id userId, user.username username, manufacturer.id manufacturerId, manufacturer.business_name businessName, manufacturer.contact_no contactNo, manufacturer.email email, manufacturer.url url, manufacturer.description description, address.id addressId, address.street street, address.city city, address.state state, address.country country, address.zip zip
			FROM user, manufacturer, address
			WHERE user.id = {$id}
			AND user.role = 2 
			AND user.role_id = manufacturer.id
			AND user.address_id = address.id";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	return null;
}

function createManufacturerProfile($username, $password, $businessName, $email, $contactNo, $url, $description, $street, $city, $state, $country, $zip, $dt) {
	global $conn;
	$status = false;
	$conn->autocommit(false);
	if (createAddress($street, $city, $state, $country, $zip, $dt)) {
		$addressId = lastInsertId();
		if (createManufacturer($businessName, $email, $contactNo, $url, $description, $dt)) {
			$manufacturerId = lastInsertId();
			if (createUser($username, $password, 2, $manufacturerId, $addressId, $dt)) {
				$status = true;
			} else {
				die(mysqli_error($conn));
			}
		} else {
			die("2");
		}
	} else {
		die("1");
	}
	$conn->autocommit(true);
	return $status;
}

function createManufacturer($businessName, $email, $contactNo, $url, $description, $dt) {
	global $conn;
	$sql = "INSERT INTO manufacturer (
			business_name, email, contact_no, url, description, created_date, modified_date
			) VALUES (
			'{$businessName}', '{$email}', '{$contactNo}', '{$url}', '{$description}', '{$dt}', '{$dt}'
			)";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) == 1;
}

function createAddress($street, $city, $state, $country, $zip, $dt) {
	global $conn;
	$sql = "INSERT INTO address (
			street, city, state, country, zip, created_date, modified_date
			) VALUES (
			'{$street}', '{$city}', '{$state}', '{$country}', '{$zip}', '{$dt}', '{$dt}'
			)";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) == 1;
}

function createUser($username, $password, $role, $roleId, $addressId, $dt) {
	global $conn;
	$sql = "INSERT INTO user (
			username, pwd, role, role_id, address_id, created_date, modified_date
			) VALUES (
			'{$username}', '{$password}', '{$role}', '{$roleId}', '{$addressId}', '{$dt}', '{$dt}'
			)";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) == 1;
}

function updateManufacturerProfile($id, $username, $password, $manufacturerId, $businessName, $email, $contactNo, $url, $description, $addressId, $street, $city, $state, $country, $zip, $dt) {
	global $conn;
	$status = false;
	$conn->autocommit(false);
	if (updateAddress($addressId, $street, $city, $state, $country, $zip, $dt)) {
		if (updateManufacturer($manufacturerId, $businessName, $email, $contactNo, $url, $description, $dt)) {
			if (updateUser($id, $username, $password, $dt)) {
				$status = true;
			}
		} 
	}
	$conn->autocommit(true);
	return $status;
}

function updateManufacturer($id, $businessName, $email, $contactNo, $url, $description, $dt) {
	global $conn;
	$sql = "UPDATE manufacturer SET 
			business_name = '{$businessName}',
			email = '{$email}',
			contact_no = '{$contactNo}',
			url = '{$url}',
			description = '{$description}',  
			modified_date = '{$dt}'
			WHERE id = {$id}";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) >= 0;
}

function duplicateBusinessName($businessName) {
	global $conn;
	$sql = "SELECT *
			FROM manufacturer 
			WHERE business_name = '{$businessName}'";
	$result = mysqli_query($conn, $sql);
	return mysqli_num_rows($result) == 0;
}

function duplicateBusinessNameWithId($businessName, $id) {
	global $conn;
	$sql = "SELECT *
			FROM manufacturer 
			WHERE business_name = '{$businessName}' 
			AND id != {$id}";
	$result = mysqli_query($conn, $sql);
	return mysqli_num_rows($result) == 0;
}

function getAllCategories() {
	global $conn;
	$sql = "SELECT *
			FROM category";
	if ($result = mysqli_query($conn, $sql)) {
		$categories = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$categories[] = $row;
		}
		return $categories;
	}
	return null;
}

function addNewProduct($productName, $price, $quantity, $visibility, $imageURL, $categoryId, $description, $manufacturerId, $dt) {
	global $conn;
	$sql = "INSERT INTO product (
			product_name, price, quantity, visibility, image_url, category_id, description, created_date, modified_date, manufacturer_id
			) VALUES (
			'{$productName}', {$price}, {$quantity}, {$visibility}, '{$imageURL}', {$categoryId}, '{$description}', '{$dt}', '{$dt}', {$manufacturerId}
			)";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) == 1;
}

function getAllProductsByManufacturerId($manufacturerId) {
	global $conn;
	$sql = "SELECT *
			FROM product
			WHERE manufacturer_id = {$manufacturerId}";
	if ($result = mysqli_query($conn, $sql)) {
		$products = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$products[] = $row;
		}
		return $products;
	}
	return null;
}

function getProductByIdWithManufacturerId($id, $manufacturerId) {
	global $conn;
	$sql = "SELECT product.id id, product.product_name productName, product.price price, product.quantity quantity, product.visibility visibility, product.image_url imageURL, product.category_id categoryId, product.description description
			FROM product
			WHERE product.id = {$id}
			AND product.manufacturer_id = {$manufacturerId}";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	return null;
}

function updateProduct($id, $productName, $price, $quantity, $visibility, $imageURL, $categoryId, $description, $manufacturerId, $dt) {
	global $conn;
	$sql = "UPDATE product SET 
			product_name = '{$productName}',
			price = {$price},
			quantity = {$quantity},
			visibility = {$visibility},
			image_url = '{$imageURL}',
			category_id = {$categoryId},
			description = '{$description}',
			modified_date = '{$dt}'
			WHERE id = {$id}
			AND manufacturer_id = {$manufacturerId}";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) >= 0;
}

function getAllManufacturers() {
	global $conn;
	$sql = "SELECT *
			FROM manufacturer";
	if ($result = mysqli_query($conn, $sql)) {
		$manufacturers = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$manufacturers[] = $row;
		}
		return $manufacturers;
	}
	return null;
}

function getAllVisibleProducts($q, $manufacturerId, $categoryId, $perPage, $offset) {
	global $conn;
	$sql = "SELECT *
			FROM product
			WHERE visibility = 1";
	if (!empty($q)) {
		$sql .= " AND (product_name LIKE '%{$q}%' OR description LIKE '%{$q}%')";
	}
	if ($manufacturerId > 0) {
		$sql .= " AND manufacturer_id = {$manufacturerId}";
	}
	if ($categoryId > 0) {
		$sql .= " AND category_id = {$categoryId}";
	}
	$sql .= " LIMIT {$perPage} OFFSET {$offset}";
	if ($result = mysqli_query($conn, $sql)) {
		$products = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$products[] = $row;
		}
		return $products;
	}
	return null;
}

function getBuyerProfile($id) {
	global $conn;
	$sql = "SELECT user.id userId, user.username username, buyer.id buyerId, buyer.first_name firstName, buyer.last_name lastName, buyer.contact_no contactNo, buyer.email email, address.id addressId, address.street street, address.city city, address.state state, address.country country, address.zip zip
			FROM user, buyer, address
			WHERE user.id = {$id}
			AND user.role = 3 
			AND user.role_id = buyer.id
			AND user.address_id = address.id";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	return null;
}

function updateBuyerProfile($id, $username, $password, $buyerId, $firstName, $lastName, $email, $contactNo, $addressId, $street, $city, $state, $country, $zip, $dt) {
	global $conn;
	$status = false;
	$conn->autocommit(false);
	if (updateAddress($addressId, $street, $city, $state, $country, $zip, $dt)) {
		if (updateBuyer($buyerId, $firstName, $lastName, $email, $contactNo, $dt)) {
			if (updateUser($id, $username, $password, $dt)) {
				$status = true;
			}
		}
	}
	$conn->autocommit(true);
	return $status;
}

function updateBuyer($id, $firstName, $lastName, $email, $contactNo, $dt) {
	global $conn;
	$sql = "UPDATE buyer SET 
			first_name = '{$firstName}',
			last_name = '{$lastName}',
			email = '{$email}',
			contact_no = '{$contactNo}', 
			modified_date = '{$dt}'
			WHERE id = {$id}";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) >= 0;
}

function getCountOfProducts($q, $manufacturerId, $categoryId) {
	global $conn;
	$countOfProducts = 0;
	$sql = "SELECT COUNT(*)
			FROM product";
	if (!empty($q)) {
		$sql .= " WHERE (product_name LIKE '%{$q}%' OR description LIKE '%{$q}%')";
	}
	if ($manufacturerId > 0) {
		if (!empty($q)) {
			$sql .= " AND";
		} else {
			$sql .= " WHERE";
		}
		$sql .= " manufacturer_id = {$manufacturerId}";
	}
	if ($categoryId > 0) {
		if (!empty($q)) {
			$sql .= " AND";
		} else {
			if ($manufacturerId > 0) {
				$sql .= " AND";
			} else {
				$sql .= " WHERE";
			}
		}
		$sql .= " category_id = {$categoryId}";
	}
	if ($result = mysqli_query($conn, $sql)) {
		$result = mysqli_fetch_row($result);
		$countOfProducts = array_shift($result);
	}
	return $countOfProducts;
}

function getVisibleProductById($id) {
	global $conn;
	$sql = "SELECT product.id id, product.product_name productName, product.price price, product.quantity quantity, product.visibility visibility, product.image_url imageURL, product.category_id categoryId, product.manufacturer_id manufacturerId, product.description description
			FROM product
			WHERE product.id = {$id}
			AND visibility = 1";
	if ($result = mysqli_query($conn, $sql)) {
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	return null;
}

function isProductInCart($id) {
	return in_array($id, $_SESSION["cart"]);
}

function array_remove($element, $array) {
    $index = array_search($element, $array);
    array_splice($array, $index, 1);
    return $array;
}

function removeFromCart($id) {
	$_SESSION['cart'] = array_remove($id, $_SESSION['cart']);
}

function getProductsInCart() {
	global $conn;
	$ids = implode(",", $_SESSION["cart"]);
	$sql = "SELECT *
			FROM product
			WHERE id IN ({$ids})";
	if ($result = mysqli_query($conn, $sql)) {
		$products = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$products[] = $row;
		}
		return $products;
	}
	return null;
}

function placeOrder($buyerId, $productsWithQuantities, $dt) {
	$status = false;
	if (createOrder($buyerId, $dt)) {
		$buyerOrderId = lastInsertId();
		if (createOrderDetail($productsWithQuantities, $buyerOrderId)) {
			$status = true;
		}
	}
	return $status;
}

function createOrder($buyerId, $dt) {
	global $conn;
	$sql = "INSERT INTO buyer_order (
			created_date, buyer_id
			) VALUES (
			'{$dt}', {$buyerId}
			)";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) == 1;
}

function createOrderDetail($productsWithQuantities, $buyerOrderId) {
	global $conn;
	$status = 1;
	$sql = "INSERT INTO order_detail 
			(product_id, quantity, status, buyer_order_id) VALUES ";
	foreach ($productsWithQuantities as $productWithQuantity) {
		$productWithQuantity[] = $status;
		$productWithQuantity[] = $buyerOrderId;
		$orderDetail = implode(",", $productWithQuantity);
		$sql .= "({$orderDetail}),";
	}
	$sql = rtrim($sql, ",");
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) >= 1;
}

function getBuyerOrders($buyerId) {
	global $conn;
	$sql = "SELECT buyer_order.id orderId, buyer_order.created_date orderDate, product.product_name productName, order_detail.quantity quantity,  order_detail.status orderStatus, manufacturer.business_name businessName, manufacturer.email email, manufacturer.contact_no contactNo
			FROM buyer_order, product, order_detail, manufacturer
			WHERE buyer_order.buyer_id = {$buyerId}
			AND order_detail.buyer_order_id = buyer_order.id
			AND order_detail.product_id = product.id
			AND product.manufacturer_id = manufacturer.id
			ORDER BY buyer_order.id DESC";
	if ($result = mysqli_query($conn, $sql)) {
		$orders = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$orders[] = $row;
		}
		return $orders;
	}
	return null;
}

function getBuyerOrdersByManufacturer($manufacturerId) {
	global $conn;
	$sql = "SELECT buyer_order.id orderId, buyer_order.created_date orderDate, product.product_name productName, product.quantity stock, order_detail.quantity orderedQuantity, order_detail.status orderStatus, CONCAT(buyer.first_name, ' ', buyer.last_name) buyerName, buyer.email email, buyer.contact_no contactNo, CONCAT(address.street, ', ', address.city, ', ', address.state, ', ', address.country) buyerAddress
			FROM buyer_order, order_detail, buyer, address, user, manufacturer, product
			WHERE manufacturer.id = {$manufacturerId}
			AND user.role = 3
			AND buyer_order.buyer_id = buyer.id
			AND order_detail.product_id = product.id
			AND product.manufacturer_id = manufacturer.id
			AND buyer_order.id = order_detail.buyer_order_id
			AND user.role_id = buyer.id
			AND user.address_id = address.id
			ORDER BY buyer_order.id DESC";
	if ($result = mysqli_query($conn, $sql)) {
		$orders = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$orders[] = $row;
		}
		return $orders;
	}
	return null;
}

function getBuyerOrdersByIdByManufacturer($orderId, $manufacturerId) {
	global $conn;
	$sql = "SELECT buyer_order.id orderId, buyer_order.created_date orderDate, order_detail.id orderDetailId, product.product_name productName, product.quantity stock, order_detail.quantity orderedQuantity, order_detail.status orderStatus, CONCAT(buyer.first_name, ' ', buyer.last_name) buyerName, buyer.email email, buyer.contact_no contactNo, CONCAT(address.street, ', ', address.city, ', ', address.state, ', ', address.country) buyerAddress
			FROM buyer_order, order_detail, buyer, address, user, manufacturer, product
			WHERE buyer_order.id = {$orderId}
			AND manufacturer.id = {$manufacturerId}
			AND user.role = 3
			AND buyer_order.buyer_id = buyer.id
			AND order_detail.product_id = product.id
			AND product.manufacturer_id = manufacturer.id
			AND buyer_order.id = order_detail.buyer_order_id
			AND user.role_id = buyer.id
			AND user.address_id = address.id
			ORDER BY buyer_order.id DESC";
	if ($result = mysqli_query($conn, $sql)) {
		$orders = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$orders[] = $row;
		}
		return $orders;
	}
	return null;
}

function updateOrderDetailStatus($orderDetailId, $orderStatus) {
	global $conn;
	$sql = "UPDATE order_detail SET
			status = {$orderStatus}
			WHERE id = {$orderDetailId}";
	$result = mysqli_query($conn, $sql);
	return mysqli_affected_rows($conn) == 1;
}