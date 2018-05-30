<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateBuyer()) {
  redirect("../login.php");
}

$products = getAllVisibleProducts();

?>

<?php require_once "header.php"; ?>

<?php if ($products != null) { ?>
  <h3 class="text-center">Products</h3>
    <div class="row">
      <?php foreach ($products as $product) { ?>
        <div class="text-center col-lg-4 col-md-4 col-sm-6 col-xs-12">
          <div class="panel panel-default">
            <div class="panel-heading"><a class="link" href="product.php?id=<?php echo $product["id"]; ?>"><h3><?php echo $product["product_name"]; ?></h3></a></div>
            <div class="panel-body"><img src="<?php echo $product["image_url"]; ?>" heght="300" width="300"></div>
            <div class="panel-footer">
              <h3>$<?php echo $product["price"]; ?></h3>
              <button class="btn btn-primary">Add to Cart</button>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
<?php } else { ?>
  <h3 class="text-center">No Product Found.</h3>
<?php } ?>

<?php require_once "footer.php"; ?>