<?php

require_once "../src/session.php";
require_once "../src/db_connection.php";
require_once "../src/functions.php";

if (!authenticateManufacturer()) {
  redirect("../login.php");
}

?>

<?php require_once "header.php"; ?>

<h3>Welcome Manufacturer: <?php echo $_SESSION["username"]; ?></h3>

<?php require_once "footer.php"; ?>