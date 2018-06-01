<?php

require_once "src/session.php";

$errors = isset($_SESSION["errors"]) ? $_SESSION["errors"] : null;

if (!empty($errors)) {
	echo "<div class='alert alert-danger'>";

		foreach ($errors as $error) {
			echo $error . "<br>";
		}

	echo "</div>";
}

$_SESSION["errors"] = [];