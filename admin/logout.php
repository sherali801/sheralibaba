<?php
session_start();
$_SESSION["user_admin_id"] = null;
$_SESSION["username"] = null;
header("Location: ../login.php");
exit;
?>