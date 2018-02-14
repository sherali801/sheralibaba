<?php
session_start();
if (!isset($_SESSION["user_manufacturer_id"])) {
  header("Location: ../login.php");
  exit;
}
require_once __DIR__ . "/../src/db_connect.php";
require_once __DIR__ . "/../src/functions.php";
$user_id = (int) $_SESSION["user_manufacturer_id"];
if (isset($_POST["submit"])) {
  $manufacturer_id = (int) $_POST["manufacturer_id"];
  $username = $db->real_escape_string($_POST["username"]);
  $pwd = password_hash($db->real_escape_string($_POST["pwd"]), PASSWORD_DEFAULT);
  $business_name = $db->real_escape_string($_POST["business_name"]);
  $email = $db->real_escape_string($_POST["email"]);
  $contact_no = $db->real_escape_string($_POST["contact_no"]);
  $url = $db->real_escape_string($_POST["url"]);
  $description = $db->real_escape_string($_POST["description"]);
  $add_id = (int) $_POST["add_id"];
  $street = $db->real_escape_string($_POST["street"]);
  $city = $db->real_escape_string($_POST["city"]);
  $state = $db->real_escape_string($_POST["state"]);
  $country = $db->real_escape_string($_POST["country"]);
  $zip = $db->real_escape_string($_POST["zip"]);
  $role = 2;
  $messages = [];
  $dt = MySqlFormattedTime(time());
  $db->autocommit(false);
  $sql = "SELECT COUNT(*) FROM user WHERE (username = '{$username}') AND (id != {$user_id})";
  $result = $db->query($sql);
  $result = $result->fetch_row();
  $result = array_shift($result);
  if (!$result) {
    $sql = "UPDATE address SET
            street = '{$street}',
            city = '{$city}',
            state = '{$state}',
            country = '{$country}',
            modified_date = '{$dt}'
            WHERE (id = {$add_id})
            LIMIT 1";
    $db->query($sql);
    if ($db->affected_rows >= 0) {
      $sql = "UPDATE manufacturer SET
              business_name = '{$business_name}',
              contact_no = '{$contact_no}',
              email = '{$email}',
              url = '{$url}',
              description = '{$description}',
              modified_date = '{$dt}'
              WHERE (id = {$manufacturer_id})
              LIMIT 1";
      $db->query($sql);
      if ($db->affected_rows >= 0) {
        $sql = "UPDATE user SET
                username = '{$username}',
                pwd = '{$pwd}',
                modified_date = '{$dt}'
                WHERE (id = {$user_id})
                LIMIT 1";
        $db->query($sql);
        if ($db->affected_rows >= 0) {
          $messages["success"] = "Account has been updated.";
          $db->commit();
        } else {
          $messages[] = "Account was not updated.";
          $db->rollback();
        }
      } else {
        $messages[] = "Account was not updated.";
        $db->rollback();
      }
    } else {
      $messages[] = "Account was not updated.";
      $db->rollback();
    }
  } else {
    $messages[] = "username \"{$username}\" already exists.";
    $messages[] = "Account was not updated.";
    $db->rollback();
  }
  $db->autocommit(true);
} else {
  $messages = [];
  $sql = "SELECT u.username, m.id AS m_id, m.business_name, m.contact_no, m.email, m.url, m.description,
          a.id AS add_id, a.street, a.city, a.state, a.country, a.zip
          FROM user u, manufacturer m, address a
          WHERE (u.id = {$user_id})
          AND (u.role = 2)
          AND (u.role_id = m.id)
          AND (u.address_id = a.id)
          LIMIT 1";
  $result = $db->query($sql);
  if ($result) {
    $result = $result->fetch_assoc();
    $username = $result["username"];
    $manufacturer_id = $result["m_id"];
    $business_name = $result["business_name"];
    $contact_no = $result["contact_no"];
    $email = $result["email"];
    $url = $result["url"];
    $description = $result["description"];
    $add_id = $result["add_id"];
    $street = $result["street"];
    $city = $result["city"];
    $state = $result["state"];
    $country = $result["country"];
    $zip = $result["zip"];
    $messages = [];
  } else {
    $messages[] = "No Manufacturer Found.";
  }
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
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-horizontal">
    <h2 class="text-center">Edit Profile</h2>
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
    <div class="form-group">
      <label class="col-sm-4 control-label">Username</label>
      <div class="col-sm-4">
        <input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>">
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
        <input type="hidden" name="add_id" value="<?php echo $add_id; ?>">
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
      <div class="col-sm-4 col-sm-offset-4">
        <input type="submit" name="submit" value="Edit Profile" class="btn btn-primary btn-block">
      </div>
    </div>
  </form>
</div><script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/functions.js"></script>
</body>
</html>
<?php $db->close(); ?>
