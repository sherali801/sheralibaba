<?php
require_once __DIR__ . "/src/db_connect.php";
require_once __DIR__ . "/src/functions.php";

if (isset($_POST["submit"])) {
  $username = $db->real_escape_string($_POST["username"]);
  $pwd = password_hash($db->real_escape_string($_POST["pwd"]), PASSWORD_DEFAULT);
  $business_name = $db->real_escape_string($_POST["business_name"]);
  $email = $db->real_escape_string($_POST["email"]);
  $contact_no = $db->real_escape_string($_POST["contact_no"]);
  $url = $db->real_escape_string($_POST["url"]);
  $street = $db->real_escape_string($_POST["street"]);
  $city = $db->real_escape_string($_POST["city"]);
  $state = $db->real_escape_string($_POST["state"]);
  $country = $db->real_escape_string($_POST["country"]);
  $zip = $db->real_escape_string($_POST["zip"]);
  $description = $db->real_escape_string($_POST["description"]);
  $terms = $db->real_escape_string($_POST["terms"]);
  $role = 2;
  $messages = [];
  $dt = MySqlFormattedTime(time());
  $db->autocommit(false);
  $sql = "SELECT COUNT(*) FROM user WHERE username = '{$username}'";
  $result = $db->query($sql);
  if (!$result) {
    die("Please Try Again Later.");
  }
  $result = $result->fetch_row();
  $result = array_shift($result);
  if (!$result) {
    $sql = "INSERT INTO address (
            street, city, state, country, zip, created_date, modified_date
            ) VALUES (
            '{$street}', '{$city}', '{$state}', '{$country}', '{$zip}', '{$dt}', '{$dt}'
            )";
    $db->query($sql);
    if ($db->affected_rows == 1) {
      $address_id = $db->insert_id;
      $sql = "INSERT INTO manufacturer (
              business_name, contact_no, email, url, description, created_date, modified_date
              ) VALUES (
              '{$business_name}', '{$contact_no}', '{$email}', '{$url}', '{$description}', '{$dt}', '{$dt}'
              )";
      $db->query($sql);
      if ($db->affected_rows == 1) {
        $role_id = $db->insert_id;
        $sql = "INSERT INTO user (
                username, pwd, created_date, modified_date, role, role_id, address_id
                ) VALUES (
                '{$username}', '{$pwd}', '{$dt}', '{$dt}', $role, $role_id, $address_id
                )";
        $db->query($sql);
        if ($db->affected_rows == 1) {
          $messages["success"] = "Account has been created.";
          $db->commit();
        } else {
          $messages[] = "Account was not created.";
          $db->rollback();
        }
      } else {
        $messages[] = "Account was not created.";
        $db->rollback();
      }
    } else {
      $messages[] = "Account was not created.";
      $db->rollback();
    }
  } else {
    $messages[] = "username \"{$username}\" already exists.";
    $messages[] = "Account was not created.";
    $db->rollback();
  }
  $db->autocommit(true);
} else {
  $username = "";
  $business_name = "";
  $email = "";
  $contact_no = "";
  $url = "";
  $street = "";
  $city = "";
  $state = "";
  $country = "";
  $zip = "";
  $description = "";
  $terms = "";
  $messages = [];
}

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
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-horizontal">
    <h2 class="text-center">Manufacturer Sign Up</h2>
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
      $username = "";
      $business_name = "";
      $email = "";
      $contact_no = "";
      $url = "";
      $street = "";
      $city = "";
      $state = "";
      $country = "";
      $zip = "";
      $description = "";
      $terms = "";
    }
    $messages = [];
    ?>
    <div class="form-group">
      <label class="col-sm-4 control-label">Username</label>
      <div class="col-sm-4">
        <input type="text" name="username" value="<?php echo htmlentities($username); ?>" class="form-control" placeholder="Username" maxlength="15" pattern="^[\w]{1,15}$" required autofocus data-toggle="tooltip" data-placement="right" title="15 character long">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Password</label>
      <div class="col-sm-4">
        <input type="password" name="pwd" class="form-control" placeholder="Password" minlength="8" maxlength="15" pattern="^(?=.*\d)(?=.*[~!@#$%^&*()_\-+=|\\{}[\]:;<>?/])(?=.*[A-Z])(?=.*[a-z])\S{8,15}$" required data-toggle="tooltip" data-placement="right" title="min length 8, max length 15, 1 lowercase, 1 uppercase, 1 digit, 1 special character">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Business Name</label>
      <div class="col-sm-4">
        <input type="text" name="business_name" value="<?php echo htmlentities($business_name); ?>" class="form-control" placeholder="Business Name" maxlength="255" required data-toggle="tooltip" data-placement="right" title="255 character long">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Email</label>
      <div class="col-sm-4">
        <input type="email" name="email" value="<?php echo htmlentities($email); ?>" class="form-control" placeholder="user@domain.com" maxlength="255" pattern="^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,6}$" required>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Contact No.</label>
      <div class="col-sm-4">
        <input type="text" name="contact_no" value="<?php echo htmlentities($contact_no); ?>" class="form-control" placeholder="Contact No" maxlength="20" pattern="^[\d]{1,20}$" required>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">URL</label>
      <div class="col-sm-4">
        <input type="url" name="url" value="<?php echo htmlentities($url); ?>" class="form-control" placeholder="http(s)://" maxlength="255" pattern="^(?:http|https):\/\/[\w\-_]+(?:\.[\w\-_]+)+[\w\-.,@?^=%&:/~\\+#]*$">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Address</label>
      <div class="col-sm-4">
        <input type="text" name="street" value="<?php echo htmlentities($street); ?>" placeholder="Street" class="form-control" maxlength="255" required>
      </div>
      <div class="col-sm-4 col-sm-offset-4">
        <input type="text" name="city" value="<?php echo htmlentities($city); ?>" placeholder="City" class="form-control" maxlength="255" required>
      </div>
      <div class="col-sm-4 col-sm-offset-4">
        <input type="text" name="state" value="<?php echo htmlentities($state); ?>" placeholder="State" class="form-control" maxlength="255" required>
      </div>
      <div class="col-sm-4 col-sm-offset-4">
        <input type="text" name="country" value="<?php echo htmlentities($country); ?>" placeholder="Country" class="form-control" maxlength="255" required>
      </div>
      <div class="col-sm-4 col-sm-offset-4">
        <input type="text" name="zip" value="<?php echo htmlentities($zip); ?>" placeholder="ZIP" class="form-control" maxlength="10" required>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Business Description</label>
      <div class="col-sm-4">
        <textarea name="description" class="form-control" rows="3" placeholder="Business Description" maxlength="255" data-toggle="tooltip" data-placement="right" title="255 character long"><?php echo htmlentities($description); ?></textarea>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-8 col-sm-offset-4">
        <div class="checkbox">
          <label><input type="checkbox" name="terms" <?php echo ($terms == "on") ? "checked" : ""; ?> required>I Accept <a href="terms.php" target="_blank">Terms and Conditions</a></label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-4 col-sm-offset-4">
        <input type="submit" name="submit" value="Sign Up" class="btn btn-primary btn-block">
      </div>
    </div>
  </form>
</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/functions.js"></script>
</body>
</html>
<?php $db->close(); ?>
