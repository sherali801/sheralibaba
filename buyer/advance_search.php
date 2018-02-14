<?php
session_start();
if (!isset($_SESSION["user_buyer_id"])) {
  header("Location: ../login.php");
  exit;
}
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}
require_once __DIR__ . "/../src/db_connect.php";

$product_name = "";
$manufacturer_id = 0;
$category_id = 0;
$min_price = 0;
$max_price= 0;
$products = "";

if (isset($_POST["submit"])) {
  $product_name = $db->real_escape_string($_POST["product_name"]);
  $manufacturer_id = (int) $db->real_escape_string($_POST["manufacturer_id"]);
  $category_id = (int) $db->real_escape_string($_POST["category_id"]);
  $min_price = (float) $db->real_escape_string($_POST["min_price"]);
  $max_price = (float) $db->real_escape_string($_POST["max_price"]);
  $sql = "SELECT p.id AS p_id, p.product_name, p.price, i.image_name
          FROM product p, image i
          WHERE (p.image_id = i.id)
          AND (p.visibility = 1) ";
  if (!empty($product_name)) {
    $sql .= "AND (product_name LIKE '%{$product_name}%' OR description LIKE '%{$product_name}%') ";
  }
  if ($manufacturer_id) {
    $sql .= "AND (p.manufacturer_id = {$manufacturer_id}) ";
  }
  if ($category_id) {
    $sql .= "AND (p.category_id = {$category_id}) ";
  }
  if ($min_price) {
    $sql .= "AND (p.price >= {$min_price}) ";
  }
  if ($max_price) {
    $sql .= "AND (p.price <= {$max_price}) ";
  }
  $products = $db->query($sql);
  if ($products->num_rows <= 0) {
    $products = "";
  }
} else {
  $products = "";
}
function is_in_cart($id) {
  return in_array($id, $_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <title>sheralibaba</title>
  <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet">
  <link type="text/css" href="../css/styles.css" rel="stylesheet">
</head>
<body>
<div class="container container-fluid">
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">sheralibaba</a>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manufacturers <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <?php
              $sql = "SELECT id, business_name FROM manufacturer";
              $result = $db->query($sql);
              if (!$result) {
                die("Please Try Again Later.");
              }
              while ($row = $result->fetch_assoc()) {
                echo "<li><a href='product_by_manufacturer.php?manufacturer_id={$row["id"]}'>" . htmlentities($row["business_name"]) . "</a></li>";
              }
              ?>
            </ul>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <?php
              $sql = "SELECT id, category_name FROM category";
              $result = $db->query($sql);
              if (!$result) {
                die("Please Try Again Later.");
              }
              while ($row = $result->fetch_assoc()) {
                echo "<li><a href='product_by_category.php?category_id={$row["id"]}'>" . htmlentities($row["category_name"]) . "</a></li>";
              }
              ?>
            </ul>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="edit_profile.php">Edit Profile</a></li>
              <li><a href="cart.php">My Cart</a></li>
              <li><a href="orders.php">My Orders</a></li>
              <li><a href="advance_search.php">Advance Search</a></li>
            </ul>
          </li>
          <a class="navbar-brand">Hi! <?php echo htmlentities($_SESSION["username"]); ?></a>
          <a href="logout.php"><button type="button" class="btn navbar-btn btn-info">Log Out</button></a>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-inline">
    <h2 class="text-center">Select Criteria</h2>
    <div class="form-group">
      <input type="text" name="product_name" value="<?php echo htmlentities($product_name); ?>" class="form-control input-sm" placeholder="Product Name">
    </div>
    <div class="form-group">
      <select class="form-control input-sm" name="manufacturer_id">
        <option value="0">Manufacturer</option>
        <?php
        $sql = "SELECT id, business_name FROM manufacturer";
        $result = $db->query($sql);
        if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
            if ($manufacturer_id == $row["id"]) {
              echo "<option selected value='{$row["id"]}'>" . htmlentities($row["business_name"]) . "</option>";
            } else {
              echo "<option value='{$row["id"]}'>" . htmlentities($row["business_name"]) . "</option>";
            }
          }
        }
        ?>
      </select>
    </div>
    <div class="form-group">
      <select class="form-control input-sm" name="category_id">
        <option value="0">Category</option>
        <?php
        $sql = "SELECT id, category_name FROM category";
        $result = $db->query($sql);
        if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
            if ($category_id == $row["id"]) {
              echo "<option selected value='{$row["id"]}'>" . htmlentities($row["category_name"]) . "</option>";
            } else {
              echo "<option value='{$row["id"]}'>" . htmlentities($row["category_name"]) . "</option>";
            }
          }
        }
        ?>
      </select>
    </div>
    <div class="form-group">
      <input type="number" name="min_price" value="<?php echo ($min_price != 0) ? htmlentities($min_price) : ""; ?>" class="form-control input-sm" step="any" min="0.1" placeholder="Minimum Price">
      <input type="number" name="max_price" value="<?php echo ($max_price != 0) ? htmlentities($max_price) : ""; ?>" class="form-control input-sm" step="any" min="0.1" placeholder="Maximum Price">
    </div>
    <div class="form-group">
      <input type="submit" name="submit" value="Go" class="form-control btn btn-sm btn-info">
    </div>
  </form>
</div>
<?php if (isset($_POST["submit"])) { ?>
  <?php if ($products) { ?>
    <div class="container container-fluid text-center">
      <h2>Products</h2>
      <div class = "row">
        <?php while ($row = $products->fetch_assoc()) { ?>
          <div class = "col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class = "panel panel-default text-center">
              <div class = "panel-heading">
                <a href="product.php?product_id=<?php echo $row["p_id"]; ?>"><h3><?php echo strtoupper(htmlentities($row["product_name"])); ?></h3></a>
              </div>
              <div class = "panel-body">
                <a href="product.php?product_id=<?php echo $row["p_id"]; ?>"><img src = "../images/<?php echo $row["image_name"]; ?>"></a>
              </div>
              <div class = "panel-footer">
                <h3>$<?php echo $row["price"]; ?></h3>
                <button type="button" class="btn <?php echo (is_in_cart($row["p_id"])) ? "btn-success in_cart" : "btn-info add_to_cart"; ?>" id="<?php echo $row["p_id"]; ?>"><?php echo is_in_cart($row["p_id"]) ? "In Cart" : "Add to Cart"; ?></button>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  <?php } else { ?>
    <div class="container container-fluid text-center">
      <h2>No Product Found</h2>
    </div>
  <?php } ?>
<?php } ?>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/functions.js"></script>
</body>
</html>
<?php $db->close(); ?>
