<?php
session_start();
if (!isset($_SESSION["user_buyer_id"])) {
  header("Location: ../login.php");
  exit;
}
require_once __DIR__ . "/../src/db_connect.php";

$manufacturer_id = empty($_GET['manufacturer_id']) ? 0 : $_GET['manufacturer_id'];
$manufacturer_id = (int) $db->real_escape_string($manufacturer_id);
$q = "";
$business_name = "";
$products = "";
$total_pages = "";
$current_page = "";
$sql = "SELECT business_name FROM manufacturer WHERE (id = {$manufacturer_id})";
$result = $db->query($sql);
if ($result) {
  $result = $result->fetch_assoc();
  $business_name = $result["business_name"];
  $current_page = empty($_GET['current_page']) ? 1 : $_GET['current_page'];
  $current_page = (int) $db->real_escape_string($current_page);
  $per_page = 6;
  if (isset($_GET["q"])) {
    $q = $db->real_escape_string(trim($_GET["q"]));
  }
  $sql = "SELECT COUNT(*) FROM product WHERE (manufacturer_id = {$manufacturer_id})";
  if (!empty($q)) {
    $sql .= " AND (product_name LIKE '%{$q}%' OR description LIKE '%{$q}%') ";
  }
  $result = $db->query($sql);
  if ($result) {
    $result = $result->fetch_row();
    $total_count = (int) array_shift($result);
    $offset = ($current_page - 1) * $per_page;
    $total_pages = ceil($total_count / $per_page);
    $sql = "SELECT p.id AS p_id, p.product_name, p.price, i.image_name
            FROM product p, image i
            WHERE (p.image_id = i.id)
            AND (p.visibility = 1)
            AND (p.manufacturer_id = {$manufacturer_id}) ";
    if (!empty($q)) {
      $sql .= "AND (product_name LIKE '%{$q}%' OR description LIKE '%{$q}%') ";
    }
    $sql .= "LIMIT {$per_page} ";
    $sql .= "OFFSET {$offset}";
    $products = $db->query($sql);
    if (!$products) {
      $total_count = 0;
    }
  } else {
    $total_count = 0;
  }
} else {
  $total_count = 0;
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
        <form class="navbar-form navbar-left" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
          <div class="form-group">
            <input type="hidden" name="manufacturer_id" value="<?php echo urlencode($manufacturer_id); ?>">
            <input type="search" name="q" value="<?php echo htmlentities($q); ?>" class="form-control" placeholder="Search">
          </div>
          <button type="submit" class="btn btn-info">Go</button>
        </form>
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
</div>
<?php if ($total_count > 0) { ?>
  <div class="container container-fluid text-center">
    <h2><?php echo htmlentities($business_name); ?> Products</h2>
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
    <ul class="pagination">
      <?php
      if($total_pages > 1) {
        if($current_page - 1 >= 1) {
          $pre_page = $current_page - 1;
          echo "<li><a href='product_by_manufacturer.php?manufacturer_id={$manufacturer_id}&q=". urlencode($q) . "&current_page={$pre_page}'>&laquo;</a></li>";
        }
        for($i=1; $i <= $total_pages; $i++) {
          if ($i == $current_page) {
            echo "<li class='active'><a href='product_by_manufacturer.php?manufacturer_id={$manufacturer_id}&q=". urlencode($q) . "&current_page={$i}'>{$i}</a></li>";
          } else {
            echo "<li><a href='product_by_manufacturer.php?manufacturer_id={$manufacturer_id}&q=". urlencode($q) . "&current_page={$i}'>{$i}</a></li>";
          }
        }
        if(($current_page + 1) <= $total_pages) {
          $next_page = $current_page + 1;
          echo "<li><a href='product_by_manufacturer.php?manufacturer_id={$manufacturer_id}&q=". urlencode($q) . "&current_page={$next_page}'>&raquo;</a></li>";
        }
      }
      ?>
    </ul>
  </div>
<?php } else { ?>
  <div class="container container-fluid text-center">
    <h2>No Product Found</h2>
  </div>
<?php } ?>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/functions.js"></script>
</body>
</html>
<?php $db->close(); ?>
