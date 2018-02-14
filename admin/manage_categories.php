<?php
session_start();
if (!isset($_SESSION["user_admin_id"])) {
  header("Location: ../login.php");
  exit;
}
require_once __DIR__ . "/../src/db_connect.php";

$user_id = (int) $_SESSION["user_admin_id"];
$sql = "SELECT a.id
        FROM admin a, user u
        WHERE (u.id = {$user_id})
        AND (u.role = 1)
        AND (u.role_id = a.id)
        LIMIT 1";
$result = $db->query($sql);
if ($result) {
  $result = $result->fetch_row();
  $admin_id = array_shift($result);
  $sql = "SELECT id, category_name
          FROM category
          WHERE (admin_id = {$admin_id})";
  $result = $db->query($sql);
  if ($result) {
    if ($result->num_rows <= 0) {
      $result = "";
    }
  } else {
    $result = "";
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
  <?php if ($result) { ?>
    <table class="table table-bordered table-striped">
      <tr>
        <th>Category Name</th>
        <th>Action</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $row["category_name"]; ?></td>
          <td><a href="edit_category.php?category_id=<?php echo urlencode($row["id"]); ?>"><button type="button" class="btn btn-info">Edit Category</button></a></td>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <h2 class="text-center">No Category Found.</h2>
  <?php } ?>
</div>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
<?php $db->close(); ?>
