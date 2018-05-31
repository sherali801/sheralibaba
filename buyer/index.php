<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateBuyer()) {
  redirect("../login.php");
}

if (!isset($_SESSION["cart"])) {
  $_SESSION["cart"] = [];
}

$perPage = 3;

if (isset($_GET["currentPage"]) && !empty($_GET["currentPage"])) {
  $currentPage = $_GET["currentPage"];
} else {
  $currentPage = 1;
}

if (isset($_GET["q"]) && !empty($_GET["q"])) {
  $q = $_GET["q"];
} else {
  $q = "";
}

if (isset($_GET["manufacturerId"]) && !empty($_GET["manufacturerId"])) {
  $manufacturerId = $_GET["manufacturerId"];
} else {
  $manufacturerId = 0;
}
if (isset($_GET["categoryId"]) && !empty($_GET["categoryId"])) {
  $categoryId = $_GET["categoryId"];
} else {
  $categoryId = 0;
}

$totalCount = getCountOfProducts($q, $manufacturerId, $categoryId);
$offset = ($currentPage - 1) * $perPage;
$totalPages = ceil($totalCount / $perPage);

$products = getAllVisibleProducts($q, $manufacturerId, $categoryId, $perPage, $offset);

?>

<?php require_once "header.php"; ?>

<?php if ($products != null) { ?>
  <h3 class="text-center">Products</h3>
  <div class="row">
    <?php foreach ($products as $product) { ?>
      <div class="text-center col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="panel panel-default">
          <div class="panel-heading"><a href="product.php?id=<?php echo $product["id"]; ?>"><h3><?php echo $product["product_name"]; ?></h3></a></div>
          <div class="panel-body"><a href="product.php?id=<?php echo $product["id"]; ?>"><img src="<?php echo $product["image_url"]; ?>" heght="300" width="300"></a></div>
          <div class="panel-footer">
            <h3>$<?php echo $product["price"]; ?></h3>
            <button id=<?php echo $product["id"]; ?> class="btn <?php echo isProductInCart($product["id"]) ? "btn-success inCart" : "btn-primary addToCart"; ?>"><?php echo isProductInCart($product["id"]) ? "In Cart" : "Add to Cart"; ?></button>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
  <nav aria-label="Page navigation" class="text-center">
    <ul class="pagination">
      <?php if ($currentPage - 1 >= 1) { ?>
      <?php $previousPage = $currentPage - 1; ?>
        <li>
          <a href="<?php echo $_SERVER["PHP_SELF"] . "?q={$q}&manufacturerId={$manufacturerId}&categoryId={$categoryId}&currentPage={$previousPage}"; ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
      <?php } ?>
      <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
        <li class="<?php echo $currentPage == $i ? "active" : ""; ?>"><a href="<?php echo $_SERVER["PHP_SELF"] . "?q={$q}&manufacturerId={$manufacturerId}&categoryId={$categoryId}&currentPage={$i}"; ?>"><?php echo $i; ?></a></li>
      <?php } ?>
      <?php if ($currentPage + 1 <= $totalPages) { ?>
        <?php $nextPage = $currentPage + 1; ?>
        <li>
          <a href="<?php echo $_SERVER["PHP_SELF"] . "?q={$q}&manufacturerId={$manufacturerId}&categoryId={$categoryId}&currentPage={$nextPage}"; ?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      <?php } ?>
    </ul>
  </nav>
<?php } else { ?>
  <h3 class="text-center">No Product Found.</h3>
<?php } ?>

<?php require_once "footer.php"; ?>