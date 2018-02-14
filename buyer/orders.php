<?php
session_start();
if (!isset($_SESSION["user_buyer_id"])) {
  header("Location: ../login.php");
  exit;
}
$buyer_id = "";
$product_id = "";
$category_id = "";
$min_date = "";
$max_date = "";
$status = "";
require_once __DIR__ . "/../src/db_connect.php";
$user_id = (int) $db->real_escape_string($_SESSION["user_buyer_id"]);
$sql = "SELECT b.id
        FROM user u, buyer b
        WHERE (u.id = {$user_id})
        AND (u.role = 3)
        AND (u.role_id = b.id)
        LIMIT 1";
$result = $db->query($sql);
if ($result) {
  $result = $result->fetch_row();
  $buyer_id = (int) array_shift($result);
  if (isset($_POST["submit"])) {
    $order_id = (int)$db->real_escape_string($_POST["order_id"]);
    $product_id = (int)$db->real_escape_string($_POST["product_id"]);
    $category_id = (int)$db->real_escape_string($_POST["category_id"]);
    $min_date = $db->real_escape_string($_POST["min_date"]);
    $max_date = $db->real_escape_string($_POST["max_date"]);
    $status = (int)$db->real_escape_string($_POST["status"]);
    $sql = "SELECT bo.id AS bo_id, bo.created_date AS bo_d, od.status,
            p.id AS p_id, p.product_name, od.quantity AS od_q,
            m.business_name, m.email AS m_email, m.contact_no AS m_contact_no,
            CONCAT(a.street, ' ', a.city, ' ', a.state, ' ', a.country, ' ', a.zip) AS m_address
            FROM buyer_order bo, order_detail od, manufacturer m, user u, address a, buyer b, product p
            WHERE (b.id = {$buyer_id})
            AND (bo.buyer_id = b.id)
            AND (od.product_id = p.id)
            AND (od.manufacturer_id = m.id)
            AND (bo.id = od.buyer_order_id)
            AND (u.role = 3)
            AND (u.role_id = b.id)
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
        <form class="navbar-form navbar-left" action="index.php" method="get">
          <div class="form-group">
            <input type="search" name="q" class="form-control" placeholder="Search">
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
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-inline">
    <h2 class="text-center">Selection Criteria</h2>
    <div class="form-group">
      <select class="form-control input-sm" name="order_id">
        <option value="0">Order #</option>
        <?php
        $sql = "SELECT DISTINCT(bo.id) AS bo_id
                FROM buyer_order bo
                WHERE (bo.buyer_id = {$buyer_id})
                ORDER BY bo.id DESC";
        $result = $db->query($sql);
        if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
            if ($order_id == $row["bo_id"]) {
              echo "<option selected value='{$row["bo_id"]}'>{$row["bo_id"]}</option>";
            } else {
              echo "<option value='{$row["bo_id"]}'>{$row["bo_id"]}</option>";
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
        $sql = "SELECT DISTINCT(p.id) AS p_id, product_name
                FROM product p, buyer_order bo, order_detail od, buyer b
                WHERE (bo.id = od.buyer_order_id)
                AND (bo.buyer_id = b.id)
                AND (p.id = od.product_id)
                AND (b.id = {$buyer_id})";
        $result = $db->query($sql);
        if ($result) {
          while ($row = mysqli_fetch_assoc($result)) {
            if ($product_id == $row["p_id"]) {
              echo "<option selected value='{$row["p_id"]}'>" . strtoupper(htmlentities($row["product_name"])) . "</option>";
            } else {
              echo "<option value='{$row["p_id"]}'>" . strtoupper(htmlentities($row["product_name"])) . "</option>";
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
  <?php if (isset($_POST["submit"])) { ?>
    <?php if ($orders) { ?>
      <table class="table table-bordered table-hover text-center" style="margin-top: 10px;">
        <tr>
          <th class="text-center">Order No</th>
          <th class="text-center">Order Date</th>
          <th class="text-center">Product Name</th>
          <th class="text-center">Order Quantity</th>
          <th class="text-center">Manufacturer Name</th>
          <th class="text-center">Manufacturer Email</th>
          <th class="text-center">Manufacturer Contact No.</th>
          <th class="text-center">Manufacturer Address</th>
          <th class="text-center">Order Status</th>
        </tr>
        <?php while ($row = $orders->fetch_assoc()) { ?>
          <tr>
            <td><?php echo htmlentities($row["bo_id"]); ?></td>
            <td><?php echo htmlentities($row["bo_d"]); ?></td>
            <td><a href="product.php?product_id=<?php echo urlencode($row["p_id"]); ?>" target="_blank"><?php echo strtoupper(htmlentities($row["product_name"])); ?></a></td>
            <td><?php echo htmlentities($row["od_q"]); ?></td>
            <td><?php echo htmlentities($row["business_name"]); ?></td>
            <td><?php echo htmlentities($row["m_email"]); ?></td>
            <td><?php echo htmlentities($row["m_contact_no"]); ?></td>
            <td><?php echo htmlentities($row["m_address"]); ?></td>
            <td>
              <?php
              switch ($row["status"]) {
                case 1:
                  echo "<button type='button' class='btn btn-info'>Pending</button>";
                  break;
                case 2:
                  echo "<button type='button' class='btn btn-success'>Accepted</button>";
                  break;
                case 3:
                  echo "<button type='button' class='btn btn-danger'>Rejected</button>";
                  break;
                case 4:
                  echo "<button type='button' class='btn btn-warning'>Delivered</button>";
                  break;
              }
              ?>
            </td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <h2 class="text-center">No Order Found.</h2>
    <?php } ?>
  <?php } ?>
</div>

<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/functions.js"></script>
</body>
</html>
<?php $db->close(); ?>
