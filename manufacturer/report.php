<?php
session_start();
if (!isset($_SESSION["user_manufacturer_id"])) {
  header("Location: ../login.php");
  exit;
}
require_once __DIR__ . "/../src/db_connect.php";
$manufacturer_id = 0;
$product_id = 0;
$min_price = "";
$max_price = "";
$orders = -1;
$order = "";
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
    $product_id = (int) $db->real_escape_string($_POST["product_id"]);
    $min_price = (float) $db->real_escape_string($_POST["min_price"]);
    $max_price = (float) $db->real_escape_string($_POST["max_price"]);
    $orders = (int) $db->real_escape_string($_POST["orders"]);
    $sql = "SELECT po.orders, p.id AS p_id, p.product_name AS p_name, p.description AS p_desc, p.price AS p_price
            FROM product_order po, product p, manufacturer m
            WHERE (po.product_id = p.id)
            AND (p.manufacturer_id = m.id)
            AND (m.id = {$manufacturer_id}) ";
    if ($product_id) {
      $sql .= "AND (p.id = {$product_id}) ";
    }
    if ($min_price) {
      $sql .= "AND (p.price >= {$min_price}) ";
    }
    if ($max_price) {
      $sql .= "AND (p.price <= {$max_price}) ";
    }
    if ($orders != -1) {
      $sql .= "AND (po.orders = {$orders}) ";
    }
    $sql .= "ORDER BY po.orders DESC";
    $order = $db->query($sql);
    if ($order) {
      if ($order->num_rows <= 0) {
        $order = "";
      }
    } else {
      $order = "";
    }
  }
} else {
  $order = "";
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
</div>
<div class="container container-fluid">
  <h2 class="text-center">Report</h2>
  <h2 class="text-center">Select Criteria</h2>
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-inline">
    <div class="form-group">
      <select class="form-control" name="product_id">
        <option value="0">Product</option>
        <?php
        $sql = "SELECT id, product_name
                FROM product
                WHERE manufacturer_id = {$manufacturer_id}";
        $result = $db->query($sql);
        if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
            if ($product_id == $row["id"]) {
              echo "<option selected value=' " . htmlentities($row["id"]) . " '> " . htmlentities($row["product_name"]) . " </option>";
            } else {
              echo "<option value=' " . htmlentities($row["id"]) . " '> " . htmlentities($row["product_name"]) . " </option>";
            }
          }
        } else {
          die("Please Try Again Later.");
        }
        ?>
      </select>
    </div>
    <div class="form-group">
      <input type="number" name="min_price" value="<?php echo ($min_price != 0) ? htmlentities($min_price) : ""; ?>" class="form-control" step="any" min="0.1" placeholder="Minimum Price">
      <input type="number" name="max_price" value="<?php echo ($max_price != 0) ? htmlentities($max_price) : ""; ?>" class="form-control" step="any" min="0.1" placeholder="Maximum Price">
    </div>
    <div class="form-group">
      <select class="form-control" name="orders">
        <option value="-1">Orders</option>
        <?php
        $sql = "SELECT DISTINCT(po.orders)
                FROM product_order po, product p, manufacturer m
                WHERE (po.product_id = p.id)
                AND (p.manufacturer_id = m.id)
                AND (m.id = {$manufacturer_id})
                ORDER BY orders";
        $result = $db->query($sql);
        if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
            if ($orders == $row["orders"]) {
              echo "<option selected value=' " . htmlentities($row["orders"]) . "'> " . htmlentities($row["orders"]) . "</option>";
            } else {
              echo "<option value=' " . htmlentities($row["orders"]) . " '> " . htmlentities($row["orders"]) . " </option>";
            }
          }
        } else {
          die("Please Try Again Later.");
        }
        ?>
      </select>
    </div>
    <div class="form-group">
      <input type="submit" name="submit" value="Go" class="form-control btn btn-info">
    </div>
  </form>
  <?php if (isset($_POST["submit"])) { ?>
    <?php if ($order) { ?>
      <table class="table table-bordered table-hover text-center" style="margin-top: 10px;">
        <tr>
          <th class="text-center">Name</th>
          <th class="text-center">Description</th>
          <th class="text-center">Price</th>
          <th class="text-center">Order</th>
          <th class="text-center">No. of Reviews</th>
          <th class="text-center">Rating</th>
        </tr>
        <?php while ($row = $order->fetch_assoc()) { ?>
          <tr>
            <td style="vertical-align: middle;"><a href="manage_products.php?q=<?php echo urlencode($row["p_name"]); ?>" target="_blank"><?php echo $row["p_name"]; ?></a></td>
            <td style="vertical-align: middle;"><?php echo $row["p_desc"]; ?></td>
            <td style="vertical-align: middle;">$<?php echo $row["p_price"]; ?></td>
            <td style="vertical-align: middle;"><?php echo $row["orders"]; ?></td>
            <?php
            $sql = "SELECT COUNT(*) AS no_of_reviews, COALESCE(ROUND(AVG(rating), 1), 0) AS rating
                    FROM review r
                    WHERE (r.product_id = {$row["p_id"]})";
            $result = $db->query($sql);
            if ($result) {
              $result = $result->fetch_assoc();
              $no_of_reviews = $result["no_of_reviews"];
              $rating = $result["rating"];
            } else {
              die("Please Try Again Later.");
            }
            ?>
            <td style="vertical-align: middle;"><?php echo $no_of_reviews; ?></td>
            <td style="vertical-align: middle;"><?php echo $rating; ?></td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <h2 class="text-center">No Order Found.</h2>
    <?php } ?>
  <?php } ?>
</div>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
<?php $db->close(); ?>
