<?php
session_start();
if (!isset($_SESSION["user_admin_id"])) {
  header("Location: ../login.php");
  exit;
}
require_once __DIR__ . "/../src/db_connect.php";
require_once __DIR__ . "/../src/functions.php";

$category = "";
$messages = [];
if (isset($_POST["submit"])) {
  $user_id = (int) $_SESSION["user_admin_id"];
  $sql = "SELECT adm.id as adm_id
          FROM user u, admin adm
          WHERE (u.id = {$user_id})
          AND (u.role = 1)
          AND (u.role_id = adm.id)
          LIMIT 1";
  $result = $db->query($sql);
  if ($result) {
    $result = $result->fetch_row();
    $admin_id = array_shift($result);
    $dt = MySqlFormattedTime(time());
    $category = $db->real_escape_string($_POST["category"]);
    $messages = [];
    $db->autocommit(false);
    $sql = "INSERT INTO category (
            category_name, created_date, modified_date, admin_id
            ) VALUES (
            '{$category}', '{$dt}', '{$dt}', {$admin_id}
            )";
    $result = $db->query($sql);
    if ($db->affected_rows == 1) {
      $messages["success"] = "Category \"{$category}\" has been added.";
      $db->commit();
    } else {
      $messages["failure"] = "Category \"{$category}\" was not added.";
      $db->rollback();
    }
    $db->autocommit(true);
  } else {
    $messages["failure"] = "Category \"{$category}\" was not added.";
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
              <li><a href="add_new_category.php">Add New Category</a></li>
              <li><a href="manage_categories.php">Manage Categories</a></li>
            </ul>
          </li>
          <a class="navbar-brand">Hi! <?php echo htmlentities($_SESSION["username"]); ?></a>
          <a href="logout.php"><button type="button" class="btn navbar-btn btn-info">Log Out</button></a>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-horizontal">
    <h2 class="text-center">Add New Category</h2>
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
      $category = "";
    }
    $messages = [];
    ?>
    <div class="form-group">
      <label class="col-sm-4 control-label">Category Name</label>
      <div class="col-sm-4">
        <input type="text" name="category" value="<?php echo htmlentities($category); ?>" class="form-control" placeholder="Category" maxlength="100" required autofocus data-toggle="tooltip" data-placement="right" title="100 character long">
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-4 col-sm-offset-4">
        <input type="submit" name="submit" value="Add New Category" class="btn btn-primary btn-block">
      </div>
    </div>
  </form>
</div><script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/functions.js"></script>
</body>
</html>
<?php $db->close(); ?>
