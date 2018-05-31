<?php

require_once "session.php";
require_once "functions.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$status = "false";

if (isset($_POST["flag"])) {
	if ($_POST["flag"] == "addToCart") {
		if (isset($_POST["id"]) && !empty($_POST["id"])) {
			$id = $_POST["id"];
			if (!isProductInCart($id)) {
				$_SESSION['cart'][] = $id;
				$status = "true";
			}
		}
	} else if ($_POST["flag"] == "removeFromCart") {
		if (isset($_POST["id"]) && !empty($_POST["id"])) {
			$id = $_POST["id"];
			if (isProductInCart($id)) {
				removeFromCart($id);
				$status = "true";
			}
		}
	}
}

echo json_encode($status);