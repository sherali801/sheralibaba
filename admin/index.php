<?php

require_once "include.php";

?>

<?php require_once $adminPath . "/header.php"; ?>

<h3>Welcome Admin: <?php echo $_SESSION["username"]; ?></h3>

<?php require_once $adminPath . "/footer.php"; ?>