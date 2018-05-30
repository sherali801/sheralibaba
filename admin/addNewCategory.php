<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateAdmin()) {
  redirect("../login.php");
}

$categoryName = "";

if (isset($_POST["submit"])) {
  $dt = MySqlFormattedTime(time());
  $categoryName = $_POST["categoryName"];
  $admin = getAdminProfile($_SESSION["id"]);
  $adminId = $admin["adminId"];
  if (addNewCategory($categoryName, $adminId, $dt)) {
    $_SESSION["successes"][] = "Category has been added.";
  } else {
    $_SESSION["errors"][] = "Category was not added.";
  }
}

?>

<?php require_once "header.php"; ?>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-horizontal">
  <h3 class="text-center">Add New Category</h2>
  <div class="form-group">
    <label for="categoryName" class="col-sm-offset-2 col-sm-2 control-label">Category Name</label>
    <div class="col-sm-4">
      <input type="text" name="categoryName" value="<?php echo $categoryName; ?>" class="form-control" id="categoryName" placeholder="Category Name" required autofocus>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-4">
      <input type="submit" name="submit" value="Submit" class="btn btn-block btn-primary">
    </div>
  </div>
</form>

<?php require_once "footer.php"; ?>