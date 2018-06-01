<?php

require_once "include.php";

$orderId = $_GET["orderId"];
$orderDetailId = $_GET["orderDetailId"];
$orderStatus = $_GET["orderStatus"];

if (updateOrderDetailStatus($orderDetailId, $orderStatus)) {
  $_SESSION["successes"][] = "Order Status has been updated.";
} else {
  $_SESSION["errors"][] = "Order Status was not updated.";
}

redirect("viewOrder.php?orderId={$orderId}");