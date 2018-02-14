<?php
session_start();
if (!isset($_SESSION["user_manufacturer_id"])) {
  header("Location: ../login.php");
  exit;
}
require_once __DIR__ . "/../src/db_connect.php";
$user_id = (int) $db->real_escape_string($_SESSION["user_manufacturer_id"]);
$current_page = 0;
$total_count = 0;
$offset = 0;
$total_pages = 0;
$messages = [];
$q = "";
$sql = "SELECT m.id AS m_id
        FROM manufacturer m, user u
        WHERE (u.id = {$user_id})
        AND (u.role = 2)
        AND (u.role_id = m.id)
        LIMIT 1";
$result = $db->query($sql);
if ($result) {
  $result = $result->fetch_row();
  $manufacturer_id = array_shift($result);
  $current_page = empty($_GET['current_page']) ? 1 : $_GET['current_page'];
  $current_page = (int) $db->real_escape_string($current_page);
  $per_page = 6;
  if (isset($_GET["q"])) {
    $q = $db->real_escape_string(trim($_GET["q"]));
  } else {
    $q = "";
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
    $sql = "SELECT p.id AS p_id, p.product_name, p.price, p.visibility, i.image_name
            FROM product p, image i
            WHERE (p.manufacturer_id = {$manufacturer_id})
            AND (p.image_id = i.id) ";
    if (!empty($q)) {
      $sql .= "AND (product_name LIKE '%{$q}%' OR description LIKE '%{$q}%') ";
    }
    $sql .= "LIMIT {$per_page} ";
    $sql .= "OFFSET {$offset}";
    $products = $db->query($sql);
    if ($products) {
      if ($products->num_rows <= 0) {
        $total_count = 0;
      }
    } else {
      $total_count = 0;
    }
  } else {
    $total_count = 0;
  }
} else {
  $total_count = 0;
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
        <form class="navbar-form navbar-left" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
          <div class="form-group">
            <input type="search" name="q" value="<?php echo htmlentities($q); ?>" class="form-control" placeholder="Search">
          </div>
          <button type="submit" class="btn btn-info">Go</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="edit_profile.php">Edit Profile</a></li>
              <li><a href="add_new_product.php">Add New Product</a></li>
              <li><a href="manage_products.php">Manage Products</a></li>
              <li><a href="view_orders.php">View Orders</a></li>
              <li><a href="report.php">Report</a></li>
            </ul>
          </li>
          <a class="navbar-brand">Hi! <?php echo htmlentities($_SESSION["username"]); ?></a>
          <a href="logout.php"><button type="button" class="btn navbar-btn btn-info">Log Out</button></a>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
</div>
<div class="container container-fluid text-center">
  <?php if ($total_count > 0) { ?>
    <h2 class="text-center">Products</h2>
    <div class = "row">
      <?php while ($row = $products->fetch_assoc()) { ?>
        <div class = "col-lg-4 col-md-4 col-sm-6 col-xs-12">
          <div class = "panel panel-default text-center">
            <div class = "panel-heading">
              <h3><?php echo strtoupper(htmlentities($row["product_name"])); ?></h3>
            </div>
            <div class = "panel-body">
              <a><img src = "../images/<?php echo $row["image_name"]; ?>"></a>
            </div>
            <div class = "panel-footer">
              <a href="edit_product.php?product_id=<?php echo urlencode($row["p_id"]); ?>"><button type="button" class="btn btn-info">Edit</button></a>
              <?php
              if ($row["visibility"] == 0) {
                echo "<button type='button' class='btn btn-danger'>Hidden</button>";
              }
              ?>
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
          echo "<li><a href='manage_products.php?q=". urlencode($q) . "&current_page={$pre_page}'>&laquo;</a></li>";
        }
        for($i=1; $i <= $total_pages; $i++) {
          if ($i == $current_page) {
            echo "<li class='active'><a href='manage_products.php?q=". urlencode($q) . "&current_page={$i}'>{$i}</a></li>";
          } else {
            echo "<li><a href='manage_products.php?q=". urlencode($q) . "&current_page={$i}'>{$i}</a></li>";
          }
        }
        if(($current_page + 1) <= $total_pages) {
          $next_page = $current_page + 1;
          echo "<li><a href='manage_products.php?q=". urlencode($q) . "&current_page={$next_page}'>&raquo;</a></li>";
        }
      }
      ?>
    </ul>
  <?php } else { ?>
    <h2>No Product Found</h2>
  <?php } ?>
</div>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
<?php $db->close(); ?>

