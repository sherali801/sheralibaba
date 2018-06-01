<?php

require_once "src/session.php";

$successes = isset($_SESSION["successes"]) ? $_SESSION["successes"] : null;

if (!empty($successes)) {
	echo "<div class='alert alert-success'>";

		foreach ($successes as $success) {
			echo $success . "<br>";
		}

	echo "</div>";
}

$_SESSION["successes"] = [];