<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateManufacturer()) {
  redirect("../login.php");
}

$id = $_SESSION["id"];
$manufacturer = getManufacturerProfile($id);
$manufacturerId = $manufacturer["manufacturerId"];

$orderId = $_GET["orderId"];
$orders = getBuyerOrdersByIdByManufacturer($orderId, $manufacturerId);

?>

<?php require_once "header.php"; ?>

<?php if ($orders != null) { ?>
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
	<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<table class="table table-bordered table-striped">
		<h3 class="text-center"><a href="viewOrder.php?orderId=<?php echo $orderId; ?>">Order # <?php echo $orderId; ?></a></h3>
		<tr>
			<th>Date</th>
			<th>Product</th>
			<th>Quantity</th>
			<th>Stock</th>
			<th>Buyer</th>
			<th>Email</th>
			<th>Contact No.</th>
			<th>Address</th>
			<th>Status</th>
		</tr>
		<?php foreach ($ordersById as $orderById) { ?>
			<tr>
			<td><?php echo $orderById["orderDate"]; ?></td>
			<td><?php echo $orderById["productName"]; ?></td>
			<td><?php echo $orderById["orderedQuantity"]; ?></td>
			<td><?php echo $orderById["stock"]; ?></td>
			<td><?php echo $orderById["buyerName"]; ?></td>
			<td><?php echo $orderById["email"]; ?></td>
			<td><?php echo $orderById["contactNo"]; ?></td>
			<td><?php echo $orderById["buyerAddress"]; ?></td>
			<td>
				<?php
					$orderDetailId = $orderById["orderDetailId"];
					switch($orderById["orderStatus"]) {
						case 1:
							echo "Pending<br>";
							echo "<a class='btn btn-primary' href='processOrder.php?orderId={$orderId}&orderDetailId={$orderDetailId}&orderStatus=2'>Accept</a><br>";
							echo "<a class='btn btn-danger' href='processOrder.php?orderId={$orderId}&orderDetailId={$orderDetailId}&orderStatus=3'>Reject</a>";
							break;
						case 2:
							echo "Accepted<br>";
							echo "<a class='btn btn-info' href='processOrder.php?orderId={$orderId}&orderDetailId={$orderDetailId}&orderStatus=4'>Delivered</a>";
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
	</form>
  <?php } ?>
<?php } else { ?>
  <h3 class="text-center">No Order Found.</h3>
<?php } ?>

<?php require_once "footer.php"; ?>