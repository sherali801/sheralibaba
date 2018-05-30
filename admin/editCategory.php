<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateAdmin()) {
  redirect("../login.php");
}

$id = $_GET["id"];
$admin = getAdminProfile($_SESSION["id"]);
$adminId = $admin["adminId"];
$category = getCategoryByIdWithAdminId($id, $adminId);
extract($category);

if (isset($_POST["submit"])) {
  $dt = MySqlFormattedTime(time());
  $categoryName = $_POST["categoryName"];
  if (duplicateCategoryNameWithId($categoryName, $id)) {
    if (updateCategory($id, $categoryName, $adminId, $dt)) {
      $_SESSION["successes"][] = "Category has been updated.";
    } else {
      $_SESSION["errors"][] = "Category was not updated.";
    }
  } else {
    $_SESSION["errors"][] = "Category Name already exists.";
  }
}

?>

<?php require_once "header.php"; ?>

<form action="<?php echo $_SERVER["PHP_SELF"] . "?id={$id}"; ?>" method="post" class="form-horizontal">
  <h3 class="text-center">Edit Category</h2>
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