<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateBuyer()) {
  redirect("../login.php");
}

$q = "";

if (isset($_POST["submit"])) {
  $buyer = getBuyerProfile($_SESSION["id"]);
  $buyerId = $buyer["buyerId"];
  $dt = MySqlFormattedTime(time());
  $productsWithQuantities = array();
  $productsIds = $_SESSION["cart"];
  foreach ($productsIds as $productId) {
    $productsWithQuantities[] = array($productId, $_POST["{$productId}"]);
  }
  if (placeOrder($buyerId, $productsWithQuantities, $dt)) {
    $_SESSION["successes"][] = "Order has been placed.";
    $_SESSION["cart"] = [];
  } else {
    $_SESSION["errors"][] = "Order was not placed.";
  }
}

$products = getProductsInCart();

?>

<?php require_once "header.php"; ?>

<?php if ($products != null) { ?>
  <h3 class="text-center">Cart</h3>
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <table class="table table-bordered table-striped">
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Action</th>
      </tr>
      <?php foreach ($products as $product) { ?>
        <tr>
          <td><a href="product.php?id=<?php echo $product["id"]; ?>"><img src="<?php echo $product["image_url"]; ?>" heght="100" width="100"></a></td>
          <td><a href="product.php?id=<?php echo $product["id"]; ?>"><?php echo $product["product_name"]; ?></a></td>
          <td>$<?php echo $product["price"]; ?></td>
          <td><input type="number" name="<?php echo $product["id"]; ?>"></td>
          <td><button id=<?php echo $product["id"]; ?> class="btn <?php echo isProductInCart($product["id"]) ? "btn-success inCart" : "btn-primary addToCart"; ?>"><?php echo isProductInCart($product["id"]) ? "In Cart" : "Add to Cart"; ?></button></td>
        </tr>
      <?php } ?>
    </table>
    <div class="text-center"><input type="submit" name="submit" value="Submit" class="btn btn-primary"></div>
  </form>
<?php } else { ?>
  <h3 class="text-center">Cart is Empty.</h3>
<?php } ?>

<?php require_once "footer.php"; ?>