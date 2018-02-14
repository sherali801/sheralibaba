<?php
session_start();
$_SESSION["user_buyer_id"] = null;
$_SESSION["username"] = null;
$_SESSION["cart"] = [];
header("Location: ../login.php");
exit;
?>