<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateAdmin()) {
  redirect("../login.php");
}

$admin = getAdminProfile($_SESSION["id"]);
$adminId = $admin["adminId"];
$categories = getAllCategoriesByAdminId($adminId);

?>

<?php require_once "header.php"; ?>

  <?php if ($categories != null) { ?>
    <h3 class="text-center">Manage Categories</h3>
    <table class="table table-bordered table-striped">
      <tr>
        <th>Category Name</th>
        <th>Edit</th>
      </tr>
      <?php foreach ($categories as $category) { ?>
        <tr>
          <td><?php echo $category["category_name"]; ?></td>
          <td><a class="btn btn-primary" href="editCategory.php?id=<?php echo $category["id"]; ?>">Edit</a></td>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <h3 class="text-center">No Category Found.</h3>
  <?php } ?>

<?php require_once "footer.php"; ?>