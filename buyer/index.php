<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateBuyer()) {
  redirect("../login.php");
}

$perPage = 3;

$currentPage = 1;
if (isset($_GET["currentPage"]) && !empty($_GET["currentPage"])) {
  $currentPage = $_GET["currentPage"];
}
$totalCount = getCountOfProducts();
$offset = ($currentPage - 1) * $perPage;
$totalPages = ceil($totalCount / $perPage);

$products = getAllVisibleProducts($perPage, $offset);

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
  <nav aria-label="Page navigation" class="text-center">
    <ul class="pagination">
      <?php if ($currentPage - 1 >= 1) { ?>
      <?php $previousPage = $currentPage - 1; ?>
        <li>
          <a href="<?php echo $_SERVER["PHP_SELF"] . "?currentPage={$previousPage}"; ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
      <?php } ?>
      <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
        <li class="<?php echo $currentPage == $i ? "active" : ""; ?>"><a href="<?php echo $_SERVER["PHP_SELF"] . "?currentPage={$i}"; ?>"><?php echo $i; ?></a></li>
      <?php } ?>
      <?php if ($currentPage + 1 <= $totalPages) { ?>
        <?php $nextPage = $currentPage + 1; ?>
        <li>
          <a href="<?php echo $_SERVER["PHP_SELF"] . "?currentPage={$nextPage}"; ?>" aria-label="Next">
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