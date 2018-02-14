<?php
session_start();
if (!isset($_SESSION["user_manufacturer_id"])) {
  header("Location: ../login.php");
  exit;
}
require_once __DIR__ . "/../src/db_connect.php";
$order_detail_id = empty($_GET["order_detail_id"]) ? 0 : $_GET["order_detail_id"];
$order_detail_id = (int) $db->real_escape_string($order_detail_id);
$status = empty($_GET["status"]) ? 0 : $_GET["status"];
$status = (int) $db->real_escape_string($status);
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
  $manufacturer_id = array_shift($result);
  $sql = "UPDATE order_detail SET
          status = {$status}
          WHERE (id = {$order_detail_id})
          AND (manufacturer_id = {$manufacturer_id})
          LIMIT 1";
  $db->query($sql);
  if ($db->affected_rows == 1) {
    switch ($status) {
      case 2:
        $messages[] = "Order has been Accepted.";
        break;
      case 3:
        $messages[] = "Order has been Rejected.";
        break;
      case 4:
        $messages[] = "Order has been Delivered.";
        break;
      default:
        $messages[] = "Order was not Processed.";
        break;
    }
  } else {
    $messages[] = "Order was not Processed.";
  }
} else {
  $messages[] = "Order was not Processed.";
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
  <?php
  if ($messages) {
    echo "<div class='form-group'>";
      echo "<div class='col-sm-4 col-sm-offset-4'>";
        echo "<div class='alert alert-info'>";
          echo "<ul>";
          foreach ($messages as $message) {
            echo "<li>{$message}</li>";
          }
          echo "</ul>";
        echo "</div>";
      echo "</div>";
    echo "</div>";
  }
  $messages = [];
  ?>
</div>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
<?php $db->close(); ?>
