<?php
session_start();
if (!isset($_SESSION["user_manufacturer_id"])) {
header("Location: ../login.php");
exit;
}
require_once __DIR__ . "/../src/db_connect.php";
$order_id = "";
$product_id = "";
$category_id = "";
$min_date = "";
$max_date = "";
$status = 1;
$orders = "";
$manufacturer_id = "";
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
  if (isset($_POST["submit"])) {
    $order_id = (int) $db->real_escape_string($_POST["order_id"]);
    $product_id = (int) $db->real_escape_string($_POST["product_id"]);
    $category_id = (int) $db->real_escape_string($_POST["category_id"]);
    $min_date = $db->real_escape_string($_POST["min_date"]);
    $max_date = $db->real_escape_string($_POST["max_date"]);
    $status = (int) $db->real_escape_string($_POST["status"]);
    $sql = "SELECT bo.created_date AS bo_d, bo.id AS bo_id, od.id AS od_id, od.status, od.quantity AS od_q,
            p.product_name, p.quantity AS p_q,
            CONCAT(b.first_name, ' ', b.last_name) AS b_name, b.email AS b_email, b.contact_no AS b_contact_no,
            CONCAT(a.street, ' ', a.city, ' ', a.state, ' ', a.country, ' ', a.zip) AS b_address
            FROM buyer_order bo, order_detail od, buyer b, address a, user u, manufacturer m, product p
            WHERE (m.id = {$manufacturer_id})
            AND (bo.buyer_id = b.id)
            AND (od.product_id = p.id)
            AND (od.manufacturer_id = m.id)
            AND (bo.id = od.buyer_order_id)
            AND (u.role = 2)
            AND (u.role_id = m.id)
            AND (u.address_id = a.id) ";
    if ($order_id) {
      $sql .= "AND (bo.id = {$order_id}) ";
    }
    if ($product_id) {
      $sql .= "AND (p.id = {$product_id}) ";
    }
    if ($min_date) {
      $sql .= "AND (DATE(bo.created_date) >= '{$min_date}') ";
    }
    if ($max_date) {
      $sql .= "AND (DATE(bo.created_date) <= '{$max_date}') ";
    }
    if ($status) {
      $sql .= "AND (od.status = {$status}) ";
    }
    $sql .= "ORDER BY bo_d DESC";
    $orders = $db->query($sql);
    if ($orders) {
      if (!$orders->num_rows) {
        $orders = "";
      }
    } else {
      $orders = "";
    }
  } else {
    $sql = "SELECT bo.created_date AS bo_d, bo.id AS bo_id, od.id AS od_id, od.status, od.quantity AS od_q,
            p.product_name, p.quantity AS p_q,
            CONCAT(b.first_name, ' ', b.last_name) AS b_name, b.email AS b_email, b.contact_no AS b_contact_no,
            CONCAT(a.street, ' ', a.city, ' ', a.state, ' ', a.country, ' ', a.zip) AS b_address
            FROM buyer_order bo, order_detail od, buyer b, address a, user u, manufacturer m, product p
            WHERE (m.id = {$manufacturer_id})
            AND (bo.buyer_id = b.id)
            AND (od.product_id = p.id)
            AND (od.manufacturer_id = m.id)
            AND (bo.id = od.buyer_order_id)
            AND (u.role = 2)
            AND (u.role_id = m.id)
            AND (u.address_id = a.id)
            AND (od.status = 1)
            ORDER BY bo_d DESC";
    $orders = $db->query($sql);
    if ($orders) {
      if ($orders->num_rows <= 0) {
        $orders = "";
      }
    } else {
      $orders = "";
    }
  }
} else {
  $orders = "";
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <title>sheralibaba</title>
  <link type="text/css" href="../css/bootstrap.min.css" rel="stylesheet">
  <link type="text/css" href="../css/jquery-ui.min.css" rel="stylesheet">
  <link type="text/css" href="../css/jquery-ui.theme.min.css" rel="stylesheet">
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
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-inline">
    <h2 class="text-center">Selection Criteria</h2>
    <div class="form-group">
      <select class="form-control input-sm" name="order_id">
        <option value="0">Order #</option>
        <?php
        $sql = "SELECT DISTINCT(bo.id)
                FROM buyer_order bo, order_detail od, manufacturer m
                WHERE (od.buyer_order_id = bo.id)
                AND (od.manufacturer_id = m.id)
                AND (m.id = {$manufacturer_id})
                ORDER BY bo.id DESC";
        $result = $db->query($sql);
        if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
            if ($order_id == $row["id"]) {
              echo "<option selected value='{$row["id"]}'>{$row["id"]}</option>";
            } else {
              echo "<option value='{$row["id"]}'>{$row["id"]}</option>";
            }
          }
        } else {
          die("Please Try Again Later.");
        }
        ?>
      </select>
    </div>
    <div class="form-group">
      <select class="form-control input-sm" name="product_id">
        <option value="0">Product</option>
        <?php
        $sql = "SELECT id, product_name
                FROM product
                WHERE manufacturer_id = {$manufacturer_id}";
        $result = $db->query($sql);
        if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
            if ($product_id == $row["id"]) {
              echo "<option selected value='{$row["id"]}'> " . strtoupper(htmlentities($row["product_name"])) .  " </option>";
            } else {
              echo "<option value='{$row["id"]}'> " . strtoupper(htmlentities($row["product_name"])) .  " </option>";
            }
          }
        } else {
          die("Please Try Again Later.");
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
        } else {
          die("Please Try Again Later.");
        }
        ?>
      </select>
    </div>
    <div class="form-group">
      <input type="text" name="min_date" value="<?php echo $min_date; ?>" placeholder="Min Date" class="form-control input-sm datepicker">
    </div>
    <div class="form-group">
      <input type="text" name="max_date" value="<?php echo $max_date; ?>" placeholder="Max Date" class="form-control input-sm datepicker">
    </div>
    <div class="form-group">
      <select class="form-control input-sm" name="status">
        <option value="0" <?php echo ($status == 0) ? "selected" : ""; ?>>Status</option>
        <option value="1" <?php echo ($status == 1) ? "selected" : ""; ?>>Pendding</option>
        <option value="2" <?php echo ($status == 2) ? "selected" : ""; ?>>Accepted</option>
        <option value="3" <?php echo ($status == 3) ? "selected" : ""; ?>>Rejected</option>
        <option value="4" <?php echo ($status == 4) ? "selected" : ""; ?>>Delivered</option>
      </select>
    </div>
    <div class="form-group">
      <input type="submit" name="submit" value="Go" class="form-control btn btn-sm btn-info">
    </div>
  </form>
</div>
<div class="container container-fluid">
  <?php if ($orders) { ?>
  <table class="table table-bordered table-striped text-center" style="margin-top: 10px;">
    <tr>
      <th class="text-center">Order Date</th>
      <th class="text-center">Order No</th>
      <th class="text-center">Product Name</th>
      <th class="text-center">Order Quantity</th>
      <th class="text-center">Stock</th>
      <th class="text-center">Buyer Name</th>
      <th class="text-center">Buyer Email</th>
      <th class="text-center">Buyer Contact No.</th>
      <th class="text-center">Buyer Address</th>
      <th class="text-center">Action</th>
    </tr>
    <?php while ($row = $orders->fetch_assoc()) { ?>
      <tr>
        <td style="vertical-align: middle;"><?php echo $row["bo_d"]; ?></td>
        <td style="vertical-align: middle;"><?php echo $row["bo_id"]; ?></td>
        <td style="vertical-align: middle;"><a href="manage_products.php?q=<?php echo urlencode($row["product_name"]); ?>" target="_blank"><?php echo strtoupper(htmlentities($row["product_name"])); ?></a></td>
        <td style="vertical-align: middle;"><?php echo htmlentities($row["od_q"]); ?></td>
        <td style="vertical-align: middle;"><?php echo htmlentities($row["p_q"]); ?></td>
        <td style="vertical-align: middle;"><?php echo htmlentities($row["b_name"]); ?></td>
        <td style="vertical-align: middle;"><?php echo htmlentities($row["b_email"]); ?></td>
        <td style="vertical-align: middle;"><?php echo htmlentities($row["b_contact_no"]); ?></td>
        <td style="vertical-align: middle;"><?php echo htmlentities($row["b_address"]); ?></td>
        <td style="vertical-align: middle;">
          <?php if ($row["status"] == 1) { ?>
            <a href="order_process.php?order_detail_id=<?php echo $row["od_id"]; ?>&status=2"><button type="button" class="btn btn-success">Accept</button></a>
            <a href="order_process.php?order_detail_id=<?php echo $row["od_id"]; ?>&status=3"><button type="button" class="btn btn-danger">Reject</button></a>
          <?php } else if ($row["status"] == 2) { ?>
            <a href="order_process.php?order_detail_id=<?php echo $row["od_id"]; ?>&status=4"><button type="button" class="btn btn-info">Deliver</button></a>
          <?php } else if ($row["status"] == 3) { ?>
            <button type="button" class="btn btn-danger">Rejected</button>
          <?php } else { ?>
            <button type="button" class="btn btn-warning">Delivered</button>
          <?php }?>
        </td>
      </tr>
    <?php } ?>
  </table>
</div>
<?php } else { ?>
  <h2 class="text-center">No Order Found.</h2>
<?php } ?>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/functions.js"></script>
</body>
</html>
<?php $db->close(); ?>
