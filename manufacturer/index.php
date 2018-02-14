<?php
session_start();
if (!isset($_SESSION["user_manufacturer_id"])) {
  header("Location: ../login.php");
  exit;
}
require_once __DIR__ . "/../src/db_connect.php";
$user_id = (int) $db->real_escape_string($_SESSION["user_manufacturer_id"]);
$sql = "SELECT m.id
        FROM user u, manufacturer m
        WHERE (u.id = {$user_id})
        AND (u.role = 2)
        AND (u.role_id = m.id)
        LIMIT 1";
$result = $db->query($sql);
if ($result) {
  $result = $result->fetch_row();
  $manufacturer_id = (int) array_shift($result);
  $sql = "SELECT COUNT(*)
          FROM order_detail
          WHERE (manufacturer_id = {$manufacturer_id})
          AND (status = 1)";
  $result = $db->query($sql);
  if ($result) {
    $result = $result->fetch_row();
    $result = (int) array_shift($result);
  } else {
    $result = "";
  }
} else {
  $result = "";
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
  <div class="text-center">
    <h2>Menu</h2>
    <div class="btn-group" role="group">
      <a href="edit_profile.php"><button type="button" class="btn btn-info">Edit Profile</button></a>
      <a href="add_new_product.php"><button type="button" class="btn btn-info">Add New Product</button></a>
      <a href="manage_products.php"><button type="button" class="btn btn-info">Manage Products</button></a>
      <a href="view_orders.php"><button type="button" class="btn btn-info">View Orders <span class="badge"><?php echo $result; ?></span></button></a>
      <a href="report.php"><button type="button" class="btn btn-info">Report</button></a>
    </div>
  </div>
</div>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
<?php $db->close(); ?>
