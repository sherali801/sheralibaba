<?php

require_once "include.php";

$q = "";

if (isset($_GET["id"]) && !empty($_GET["id"])) {
  $id = $_GET["id"];
} else {
  $id = 0;
}

$product = getVisibleProductById($id);

?>

<?php require_once $buyerPath . "/header.php"; ?>

<?php if ($product != null) { ?>
  <h3 class="text-center">Product</h3>
  <div class="row">
    <div class="text-center">
      <div class="panel panel-default">
        <div class="panel-heading"><a class="link" href="product.php?id=<?php echo $product["id"]; ?>"><h3><?php echo $product["productName"]; ?></h3></a></div>
        <div class="panel-body"><img src="<?php echo $product["imageURL"]; ?>" heght="300" width="300"></div>
        <ul class="list-group">
          <li class="list-group-item"><b>Price: </b>$<?php echo $product["price"]; ?></li>
          <li class="list-group-item"><b>Description: </b><?php echo $product["description"]; ?></li>
          <li class="list-group-item">
            <b>Category: </b>
            <?php
              $category = getCategoryById($product["categoryId"]);
              echo $category["category_name"];
            ?>
          </li>
          <?php $manufacturer = getManufactuerById($product["manufacturerId"]); ?>
          <li class="list-group-item">
            <b>Manufacturer: </b>
            <?php 
              echo $manufacturer["business_name"]; 
            ?>
          </li>
          <li class="list-group-item"><b>Email: </b><?php echo $manufacturer["email"]; ?></li>
        </ul>
        <div class="panel-footer">
          <button id=<?php echo $product["id"]; ?> class="btn <?php echo isProductInCart($product["id"]) ? "btn-success inCart" : "btn-primary addToCart"; ?>"><?php echo isProductInCart($product["id"]) ? "In Cart" : "Add to Cart"; ?></button>
        </div>
      </div>
    </div>
  </div>
<?php } else { ?>
  <h3 class="text-center">No Product Found.</h3>
<?php } ?>

<?php require_once $buyerPath . "/footer.php"; ?>