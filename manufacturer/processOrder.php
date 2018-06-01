<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateManufacturer()) {
  redirect("../login.php");
}

$orderId = $_GET["orderId"];
$orderDetailId = $_GET["orderDetailId"];
$orderStatus = $_GET["orderStatus"];

if (updateOrderDetailStatus($orderDetailId, $orderStatus)) {
  $_SESSION["successes"][] = "Order Status has been updated.";
} else {
  $_SESSION["errors"][] = "Order Status was not updated.";
}

redirect("viewOrder.php?orderId={$orderId}");