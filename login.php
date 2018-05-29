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

<?php require_once "header.php"; ?>

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

<?php require_once "footer.php"; ?>