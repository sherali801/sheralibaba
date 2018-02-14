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
require_once __DIR__ . "/../src/functions.php";
$message = "";
if (isset($_POST["submit"])) {
  if (!empty($_SESSION["cart"])) {
    $user_id = (int) $_SESSION["user_buyer_id"];
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
      $status = 1;
      $flag = true;
      $dt = MySqlFormattedTime(time());
      $db->autocommit(false);
      $sql = "INSERT INTO buyer_order (
              created_date, buyer_id
              ) VALUES (
              '{$dt}', {$buyer_id}
              )";
      $db->query($sql);
      if ($db->affected_rows == 1) {
        $buyer_order_id = $db->insert_id;
        foreach ($_SESSION["cart"] as $product_id) {
          $sql = "SELECT m.id
                  FROM manufacturer m, product p
                  WHERE (p.id = {$product_id})
                  AND (p.manufacturer_id = m.id)
                  LIMIT 1";
          $result = $db->query($sql);
          if ($result) {
            $result = $result->fetch_row();
            $manufacturer_id = (int) array_shift($result);
            $quantity = (int) $_POST["{$product_id}"];
            $sql = "INSERT INTO order_detail (
                    quantity, status, product_id, manufacturer_id, buyer_order_id
                    ) VALUES (
                    {$quantity}, {$status}, {$product_id}, {$manufacturer_id}, {$buyer_order_id}
                    )";
            $db->query($sql);
            if ($db->affected_rows != 1) {
              $flag = false;
              $message = "Order was not Placed.";
              $db->rollback();
              break;
            }
          } else {
            $message = "Order was not Placed.";
            $db->rollback();
            break;
          }
        }
        if ($flag) {
          $_SESSION["cart"] = [];
          $message = "Order has been Placed.";
          $db->commit();
        }
      } else {
        $message = "Order was not Placed.";
        $db->rollback();
      }
      $db->autocommit(true);
    } else {
      $message = "Order was not Placed.";
      $db->rollback();
    }
  } else {
    $message = "Cart is Empty.";
  }
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
  <?php if ($message) { ?>
    <h2 class="text-center"><?php echo $message; ?></h2>
  <?php } else if (!empty($_SESSION["cart"])) { ?>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
      <table class="table table-bordered table-hover text-center">
        <tr>
          <th class="text-center">Image</th>
          <th class="text-center">Name</th>
          <th class="text-center">Price</th>
          <th class="text-center">Quantity</th>
          <th class="text-center">Action</th>
        </tr>
        <?php
        foreach ($_SESSION["cart"] as $product_id) {
          $product_id = (int) $product_id;
          $sql = "SELECT p.product_name, p.price, i.image_name
                    FROM product p, image i
                    WHERE (p.id = {$product_id})
                    AND (p.image_id = i.id)
                    LIMIT 1";
          $result = $db->query($sql);
          if ($result) {
            $result = $result->fetch_assoc();
          ?>
          <tr>
            <td><img src="../images/<?php echo $result["image_name"]; ?>"></td>
            <td style="vertical-align: middle;"><?php echo strtoupper(htmlentities($result["product_name"])); ?></td>
            <td style="vertical-align: middle;"><?php echo htmlentities($result["price"]); ?></td>
            <td style="vertical-align: middle;"><input type="number" name="<?php echo htmlentities($product_id); ?>" min="1" required></td>
            <td style="vertical-align: middle;"><button type="button" class="btn <?php echo (is_in_cart($product_id)) ? "btn-success in_cart" : "btn-info add_to_cart"; ?>" id="<?php echo $product_id; ?>"><?php echo is_in_cart($product_id) ? "In Cart" : "Add to Cart"; ?></button></td>
          </tr>
          <?php
          } else {
            die("Please Try Again Later.");
          }
        }
        ?>
      </table>
      <div class="text-center">
        <input type="submit" name="submit" value="Place Order" class="btn btn-info">
      </div>
    </form>
  <?php } else { ?>
    <h2 class="text-center">Cart is Empty</h2>
  <?php } ?>
</div>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/functions.js"></script>
</body>
</html>
<?php $db->close(); ?>
