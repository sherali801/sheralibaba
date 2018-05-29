<?php

require_once "src/session.php";
require_once "src/functions.php";

$_SESSION["id"] = null;
$_SESSION["username"] = null;
$_SESSION["role"] = null;

redirect("login.php");