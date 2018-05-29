<?php

require_once "src/session.php";

if (isset($_SESSION["user_admin_id"])) {
  header("Location: admin/index.php");
  exit;
} else if (isset($_SESSION["user_buyer_id"])) {
  header("Location: buyer/index.php");
  exit;
} else if (isset($_SESSION["user_manufacturer_id"])) {
  header("Location: manufacturer/index.php");
  exit;
}
require_once __DIR__ . "/src/db_connect.php";

if (isset($_POST["submit"])) {
  $username = $db->real_escape_string($_POST["username"]);
  $pwd = $db->real_escape_string($_POST["pwd"]);
  $messages = [];
  $sql = "SELECT id, username, pwd, role
          FROM user
          WHERE username = '{$username}'
          LIMIT 1";
  $result = $db->query($sql);
  if ($result) {
    $user = $result->fetch_assoc();
    if ($username == $user["username"] && password_verify($pwd, $user["pwd"])) {
      $_SESSION["username"] = $user["username"];
      switch ($user["role"]) {
        case 1:
          $_SESSION["user_admin_id"] = $user["id"];
          header("Location: admin/index.php");
          exit;
          break;
        case 2:
          $_SESSION["user_manufacturer_id"] = $user["id"];
          header("Location: manufacturer/index.php");
          exit;
          break;
        case 3:
          $_SESSION["user_buyer_id"] = $user["id"];
          header("Location: buyer/index.php");
          exit;
          break;
        default:
          $messages[] = "Username/Password combination does not match.";
      }
    } else {
      $messages[] = "Username/Password combination does not match.";
    }
  } else {
    $messages[] = "Username/Password combination does not match.";
  }
} else {
  $username = "";
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
  <h2 class="text-center">Login</h2>
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-horizontal">
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
    ?>
    <div class="form-group">
      <label class="col-sm-4 control-label">Username</label>
      <div class="col-sm-4">
        <input type="text" name="username" value="<?php echo htmlentities($username); ?>" class="form-control" placeholder="Username" maxlength="15" pattern="^[\w]{1,15}$" required autofocus>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-4 control-label">Password</label>
      <div class="col-sm-4">
        <input type="password" name="pwd" class="form-control" placeholder="Password" minlength="8" maxlength="15" pattern="^(?=.*\d)(?=.*[~!@#$%^&*()_\-+=|\\{}[\]:;<>?/])(?=.*[A-Z])(?=.*[a-z])\S{8,15}$" required>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-4 col-sm-offset-4">
        <input type="submit" name="submit" value="Log In" class="btn btn-primary btn-block">
      </div>
    </div>
  </form>
</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php $db->close(); ?>
