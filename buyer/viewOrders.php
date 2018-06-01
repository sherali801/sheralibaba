<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateBuyer()) {
  redirect("../login.php");
}

$id = $_SESSION["id"];
$buyer = getBuyerProfile($id);
$buyerId = $buyer["buyerId"];

$orders = getBuyerOrders($buyerId);

?>

<?php require_once "header.php"; ?>

<?php if ($orders != null) { ?>
  <h3 class="text-center">Orders</h3>
  <?php $orderIds = array_unique(array_column($orders, "orderId")); ?>
  <?php foreach ($orderIds as $orderId) { ?>
    <?php
      $ordersById = []; 
      foreach ($orders as $order) {
        if ($order["orderId"] == $orderId) {
          $ordersById[] = $order;
        }
      }
    ?>
    <table class="table table-bordered table-striped">
      <tr>
        <th>Order #</th>
        <th>Date</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Manufacturer</th>
        <th>Email</th>
        <th>Contact No.</th>
        <th>Status</th>
      </tr>
      <?php foreach ($ordersById as $orderById) { ?>
        <tr>
          <td><?php echo $orderById["orderId"]; ?></td>
          <td><?php echo $orderById["orderDate"]; ?></td>
          <td><?php echo $orderById["productName"]; ?></td>
          <td><?php echo $orderById["quantity"]; ?></td>
          <td><?php echo $orderById["businessName"]; ?></td>
          <td><?php echo $orderById["email"]; ?></td>
          <td><?php echo $orderById["contactNo"]; ?></td>
          <td>
            <?php
              switch($orderById["orderStatus"]) {
                case 1:
                  echo "Pending";
                  break;
                case 2:
                  echo "Accepted";
                  break;
                case 3:
                  echo "Rejected";
                  break;
                case 4:
                  echo "Delivered";
                  break;
              }
            ?>
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } ?>
<?php } else { ?>
  <h3 class="text-center">No Order Found.</h3>
<?php } ?>

<?php require_once "footer.php"; ?>