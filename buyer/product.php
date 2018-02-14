<?php
session_start();
if (!isset($_SESSION["user_buyer_id"])) {
  header("Location: ../login.php");
  exit;
}
require_once __DIR__ . "/../src/db_connect.php";
require_once __DIR__ . "/../src/functions.php";
$product_id = empty($_GET['product_id']) ? 0 : $_GET['product_id'];
$product_id = (int) $db->real_escape_string($product_id);
$message = "";
$review_message = "";
$review_rating = "";
if (isset($_POST["submit"])) {
  $user_id = (int) $db->real_escape_string($_SESSION["user_buyer_id"]);
  $sql = "SELECT b.id
          FROM user u, buyer b, buyer_order bo, order_detail od, product p
          WHERE (u.id = {$user_id})
          AND (p.id = {$product_id})
          AND (u.role = 3)
          AND (u.role_id = b.id)
          AND (od.status = 4)
          AND (bo.buyer_id = b.id)
          AND (od.buyer_order_id = bo.id)
          AND (od.product_id = p.id)
          LIMIT 1";
  $result = $db->query($sql);
  if ($result) {
    $result = $result->fetch_row();
    $buyer_id = (int) array_shift($result);
    $review_message = $db->real_escape_string($_POST["review_message"]);
    $review_rating = (int) $db->real_escape_string($_POST["review_rating"]);
    $dt = MySqlFormattedTime(time());
    $sql = "INSERT INTO review (
            message, rating, created_date, modified_date, product_id, buyer_id
            ) VALUES (
            '{$review_message}', {$review_rating}, '{$dt}', '{$dt}', {$product_id}, {$buyer_id}
            )";
    $result = $db->query($sql);
    if ($db->affected_rows == 1) {
      $message = "Review has been Posted.";
    } else {
      $message = "Review was not Posted.";
    }
  }
}
$sql = "SELECT p.id AS p_id, p.product_name, p.description, p.price, c.category_name, i.image_name, m.business_name, m.contact_no, m.email, m.url,
        CONCAT(a.street, ', ', a.city, ', ', a.state, ', ', a.country, ', ', a.zip) AS address
        FROM product p, category c, image i, manufacturer m, address a, user u
        WHERE (p.id = {$product_id})
        AND (p.visibility = 1)
        AND (p.category_id = c.id)
        AND (p.image_id = i.id)
        AND (p.manufacturer_id = m.id)
        AND (u.role = 2)
        AND (u.role_id = m.id)
        AND (u.address_id = a.id)";
$result = $db->query($sql);
if ($result) {
  if ($result->num_rows) {
    $product = $result->fetch_assoc();
  }
} else {
  $product = "";
}

$sql = "SELECT r.message, r.rating, CONCAT(b.first_name, ' ', b.last_name) AS reviewer
        FROM review r, product p, buyer b
        WHERE (p.id = {$product_id})
        AND (p.visibility = 1)
        AND (r.buyer_id = b.id)
        AND (r.product_id = p.id)";
$reviews = $db->query($sql);
if (!$reviews) {
  $no_of_reviews = "";
}

$sql = "SELECT COUNT(*) AS no_of_reviews, ROUND(AVG(rating), 1) AS rating ";
$sql .= "FROM review r ";
$sql .= "WHERE (r.product_id = {$product_id})";
$rating = $db->query($sql);
if (!$rating) {
  $no_of_reviews = "";
}
$row = $rating->fetch_assoc();
$no_of_reviews = $row["no_of_reviews"];
$rating = $row["rating"];

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
</div>
<?php if ($product) { ?>
  <div class="container container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-default text-center">
          <div class="panel-heading">
            <a href="product.php?product_id=<?php echo urlencode($product_id); ?>">
              <h3><?php echo strtoupper(htmlentities($product["product_name"])); ?></h3></a>
          </div>
          <div class="panel-body">
            <a href="product.php?product_id=<?php echo urlencode($product_id); ?>"><img src="../images/<?php echo $product["image_name"]; ?>"></a>
          </div>
          <div class="panel-footer">
            <ul class="list-group text-left">
              <li class="list-group-item"><b>Description:</b> <?php echo htmlentities($product["description"]); ?></li>
              <li class="list-group-item"><b>Price:</b> $<?php echo htmlentities($product["price"]); ?></li>
              <li class="list-group-item"><b>Category:</b> <?php echo htmlentities($product["category_name"]); ?></li>
              <li class="list-group-item"><b>Manufacturer:</b> <?php echo htmlentities($product["business_name"]); ?></li>
              <li class="list-group-item"><b>Manufacturer Email:</b> <?php echo htmlentities($product["email"]); ?></li>
              <li class="list-group-item"><b>Manufacturer Contact No:</b> <?php echo htmlentities($product["contact_no"]); ?></li>
              <li class="list-group-item"><b>Manufacturer Address:</b> <?php echo htmlentities($product["address"]); ?>
              </li>
              <?php
              if (!empty($product["url"])) {
                echo "<li class=\"list-group-item\"><b>Manufacturer's WebSite:</b> <a href='{$product['url']}' target='_blank'>" . htmlentities($product['url']) . "</a></li>";
              }
              ?>
            </ul>
            <button type="button" class="btn <?php echo (is_in_cart($product["p_id"])) ? "btn-success in_cart" : "btn-info add_to_cart"; ?>" id="<?php echo $product["p_id"]; ?>"><?php echo is_in_cart($product["p_id"]) ? "In Cart" : "Add to Cart"; ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php if ($no_of_reviews > 0) { ?>
    <div class="container container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading"><b>Reviews (<?php echo $rating; ?>)</b></div>
        <ul class="list-group">
          <?php
          while ($review = mysqli_fetch_assoc($reviews)) {
            echo "<li class=\"list-group-item\"><b>{$review['reviewer']}:</b> " . htmlentities($review["message"]) . "</li>";
          }
          ?>
        </ul>
      </div>
    </div>
  <?php } ?>
  <?php
  $user_id = (int) $db->real_escape_string($_SESSION["user_buyer_id"]);
  $can_review = false;
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
    $sql = "SELECT status
            FROM order_detail od, buyer_order bo, buyer b, product p
            WHERE (b.id = {$buyer_id})
            AND (p.id = {$product_id})
            AND (bo.buyer_id = b.id)
            AND (od.product_id = p.id)
            AND (od.buyer_order_id = bo.id)
            LIMIT 1";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
      $result = $result->fetch_row();
      $status = (int) array_shift($result);
      if ($status == 4) {
        $can_review = true;
      }
    }
  }
  ?>
  <?php if ($can_review) { ?>
    <div class="container container-fluid">
      <form action="<?php echo $_SERVER["PHP_SELF"] . "?product_id={$product_id}"; ?>" method="post" class="form-horizontal">
        <h2 class="text-center">Post Review</h2>
        <?php if ($message) { ?>
          <h2 class="text-center">
            <?php
            echo $message;
            if (strpos($message, "has been")) {
              $review_message = "";
              $review_rating = "";
            }
            ?>
          </h2>
        <?php } ?>
        <div class="form-group">
          <label class="col-sm-4 control-label">Review</label>
          <div class="col-sm-4">
            <textarea name="review_message" class="form-control" rows="3" placeholder="Review" maxlength="255"><?php echo htmlentities($review_message); ?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Rating</label>
          <div class="col-sm-4">
            <input type="number" name="review_rating" value="<?php echo htmlentities($review_rating); ?>" class="form-control" placeholder="1-5" min="1" max="5">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-4">
            <input type="submit" name="submit" value="Post Review" class="btn btn-primary btn-block">
          </div>
        </div>
      </form>
    </div>
  <?php } ?>
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
