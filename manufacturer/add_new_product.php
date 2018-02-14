<?php
session_start();
if (!isset($_SESSION["user_manufacturer_id"])) {
  header("Location: ../login.php");
  exit;
}
require_once __DIR__ . "/../src/db_connect.php";
require_once __DIR__ . "/../src/functions.php";
require_once __DIR__ . "/../src/UploadFile.php";
$user_id = (int) $_SESSION["user_manufacturer_id"];
$max = 50 * 1024;
$messages = [];
if (isset($_POST["submit"])) {
  $product_name = $db->real_escape_string($_POST["product_name"]);
  $quantity = (int) $db->real_escape_string($_POST["quantity"]);
  $price = (float) $db->real_escape_string($_POST["price"]);
  $visibility = (int) $db->real_escape_string($_POST["visibility"]);
  $category_id = (int) $db->real_escape_string($_POST["category_id"]);
  $description = $db->real_escape_string($_POST["description"]);
  $image_name = $db->real_escape_string(basename($_FILES["filename"]["name"]));
  $image_type = $db->real_escape_string($_FILES["filename"]["type"]);
  $size = (int) $db->real_escape_string($_FILES["filename"]["size"]);
  $dt = MySqlFormattedTime(time());
  $messages = [];
  $db->autocommit(false);
  $destination = __DIR__ . '/../images/';
  try {
    $upload = new UploadFile($destination);
    $upload->upload();
    $result = $upload->getMessages();
    foreach ($result as $message) {
      if (strpos($message, "success")) {
        $messages["success"] = $message;
        if (preg_match("/renamed (.+)$/", $message, $matches) == 1) {
          $image_name = rtrim($matches[1], ".");
        }
      } else {
        $messages[] = $message;
      }
    }
    if (array_key_exists("success", $messages)) {
      $sql = "INSERT INTO image (
              image_name, image_type, size, created_date, modified_date
              ) VALUES (
              '{$image_name}', '{$image_type}', {$size}, '{$dt}', '{$dt}'
              )";
      $db->query($sql);
      if ($db->affected_rows == 1) {
        $image_id = $db->insert_id;
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
          $sql = "INSERT INTO product (product_name, description, price, quantity, visibility, created_date,
                  modified_date, category_id, image_id, manufacturer_id
                  ) VALUES (
                  '{$product_name}', '{$description}', {$price}, {$quantity}, {$visibility}, '{$dt}', '{$dt}', {$category_id},
                  {$image_id}, {$manufacturer_id}
                  )";
          $db->query($sql);
          if ($db->affected_rows == 1) {
            $db->commit();
            $messages["success"] = "Product \"{$product_name}\" has been added.";
          } else {
            $db->rollback();
            $messages[] = "Product \"{$product_name}\" was not added.";
          }
        } else {
          $db->rollback();
          $messages[] = "Product \"{$product_name}\" was not added.";
        }
      } else {
        $db->rollback();
        $messages[] = "Product \"{$product_name}\" was not added.";
      }
    } else {
      $db->rollback();
      $messages[] = "Product \"{$product_name}\" was not added.";
    }
    $db->autocommit(true);
  } catch (Exception $e) {
    $messages[] = "Product \"{$product_name}\" was not added.";
  }
} else {
  $product_name = "";
  $quantity = "";
  $price = "";
  $visibility = "";
  $category_id = "";
  $description = "";
  $messages = [];
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
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
    <h2 class="text-center">Add New Product</h2>
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
    if (array_key_exists("success", $messages)) {
      $product_name = "";
      $quantity = "";
      $price = "";
      $visibility = "";
      $category_id = "";
      $description = "";
    }
    $messages = [];
    ?>
    <div class="form-group">
      <label class="col-sm-4 control-label">Product Name</label>
      <div class="col-sm-4">
        <input type="text" name="product_name" value="<?php echo htmlentities($product_name); ?>" class="form-control" placeholder="Product Name" maxlength="20" required autofocus data-toggle="tooltip" data-placement="right" title="20 character long">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Image</label>
      <div class="col-sm-4">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max; ?>">
        <input type="file" name="filename" required>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Quantity</label>
      <div class="col-sm-4">
        <input type="number" name="quantity" value="<?php echo htmlentities($quantity); ?>" class="form-control" placeholder="Quantity" min="1" required>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Price</label>
      <div class="col-sm-4">
        <input type="number" step="any" name="price" value="<?php echo htmlentities($price); ?>" class="form-control" placeholder="$00.00" min="0.5" pattern="^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,6}$" required>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Category</label>
      <div class="col-sm-4">
        <select class="form-control" name="category_id">
          <?php
          $sql = "SELECT id, category_name FROM category";
          $result = $db->query($sql);
          if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
              if ($category_id == $row["id"]) {
                echo "<option selected value='{$row["id"]}'>{$row["category_name"]}</option>";
              } else {
                echo "<option value='{$row["id"]}'>{$row["category_name"]}</option>";
              }
            }
          }
          ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Visibility</label>
      <div class="col-sm-4">
        <label class="radio-inline"><input type="radio" name="visibility" value="1" <?php echo ($visibility == 1) ? "checked" : ""; ?>> Yes</label>
        <label class="radio-inline"><input type="radio" name="visibility" value="0" <?php echo ($visibility == 0) ? "checked" : ""; ?>> No</label>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Product Description</label>
      <div class="col-sm-4">
        <textarea name="description" class="form-control" rows="3" placeholder="Product Description" maxlength="255" required data-toggle="tooltip" data-placement="right" title="255 character long"><?php echo htmlentities($description); ?></textarea>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-4 col-sm-offset-4">
        <input type="submit" name="submit" value="Add New Product" class="btn btn-primary btn-block">
      </div>
    </div>
  </form>
</div>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/functions.js"></script>
</body>
</html>
<?php $db->close(); ?>
