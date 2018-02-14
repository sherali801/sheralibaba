<?php
require_once __DIR__ . "/src/db_connect.php";

$product_id = empty($_GET['product_id']) ? 0 : $_GET['product_id'];
$product_id = (int) $db->real_escape_string($product_id);
$product = "";
$sql = "SELECT p.product_name, p.description, p.price, c.category_name, i.image_name, m.business_name, m.contact_no, m.email, m.url,
        CONCAT(a.street, ', ', a.city, ', ', a.state, ', ', a.country, ', ', a.zip) AS address
        FROM product p, category c, image i, user u, manufacturer m, address a
        WHERE (p.id = {$product_id})
        AND (p.visibility = 1)
        AND (u.role = 2)
        AND (u.role_id = m.id)
        AND (u.address_id = a.id)
        AND (p.category_id = c.id)
        AND (p.image_id = i.id)
        AND (p.manufacturer_id = m.id)
        LIMIT 1";
$result = $db->query($sql);
if ($result) {
  $product = $result->fetch_assoc();
}

$sql = "SELECT r.message, r.rating, CONCAT(b.first_name, ' ', b.last_name) AS reviewer
        FROM review r, product p, buyer b
        WHERE (r.product_id = {$product_id})
        AND (p.visibility = 1)
        AND (r.buyer_id = b.id)
        AND (r.product_id = p.id)";
$reviews = $db->query($sql);
if (!$reviews) {
  $no_of_reviews = 0;
}

$sql = "SELECT COUNT(*) AS no_of_reviews, ROUND(AVG(rating), 1) AS rating
        FROM review r
        WHERE (r.product_id = {$product_id})";
$rating = $db->query($sql);
if (!$rating) {
  $no_of_reviews = 0;
}
$row = $rating->fetch_assoc();
$no_of_reviews = $row["no_of_reviews"];
$rating = $row["rating"];

?>

<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <title>sheralibaba</title>
  <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet">
  <link type="text/css" href="css/styles.css" rel="stylesheet">
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
          <li><a href="advance_search.php">Advance Search</a></li>
          <li><a href="login.php">Log In</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sign Up <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="manufacturer_signup.php">Manufacturer</a></li>
              <li><a href="buyer_signup.php">Buyer</a></li>
            </ul>
          </li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
</div>
<div class="container container-fluid">
  <?php if ($product) { ?>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="panel panel-default text-center">
            <div class="panel-heading">
              <a href="product.php?product_id=<?php echo urlencode($product_id); ?>">
                <h3><?php echo strtoupper(htmlentities($product["product_name"])); ?></h3></a>
            </div>
            <div class="panel-body">
              <a href="product.php?product_id=<?php echo urlencode($product_id); ?>"><img src="images/<?php echo $product["image_name"]; ?>"></a>
            </div>
            <div class="panel-footer">
              <ul class="list-group text-left">
                <li class="list-group-item"><b>Description:</b> <?php echo htmlentities($product["description"]); ?></li>
                <li class="list-group-item"><b>Price:</b> $<?php echo htmlentities($product["price"]); ?></li>
                <li class="list-group-item"><b>Category:</b> <?php echo htmlentities($product["category_name"]); ?></li>
                <li class="list-group-item"><b>Manufacturer:</b> <?php echo htmlentities($product["business_name"]); ?></li>
                <li class="list-group-item"><b>Manufacturer's Email:</b> <?php echo htmlentities($product["email"]); ?></li>
                <li class="list-group-item"><b>Manufacturer's Contact No:</b> <?php echo htmlentities($product["contact_no"]); ?></li>
                <li class="list-group-item"><b>Manufacturer's Address:</b> <?php echo htmlentities($product["address"]); ?>
                </li>
                <?php
                if (!empty($product["url"])) {
                  echo "<li class=\"list-group-item\"><b>Manufacturer's WebSite:</b> <a href='{$product['url']}' target='_blank'>" . htmlentities($product['url']) . "</a></li>";
                }
                ?>
              </ul>
              <a href="login.php"><button type="button" class="btn btn-info">Buy</button></a>
            </div>
          </div>
        </div>
      </div>
    <?php if ($no_of_reviews > 0) { ?>
      <h2 class="text-center">Reviews</h2>
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
    <?php } ?>
  <?php } else { ?>
    <h2 class="text-center">No Product Found</h2>
  <?php } ?>
</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php $db->close(); ?>
