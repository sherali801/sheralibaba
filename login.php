<?php

require_once "include.php";

$q = "";

$username = "";

if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];
  if (validateUser($username, $password)) {
    switch ($_SESSION["role"]) {
      case 1:
        redirect("admin/index.php");
      case 2:
        redirect("manufacturer/index.php");
      case 3:
        redirect("buyer/index.php");
    }
  }
}

?>

<?php require_once $rootPath . "/header.php"; ?>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-horizontal">
  <h3 class="text-center">Login</h2>
  <div class="form-group">
    <label for="username" class="col-sm-offset-2 col-sm-2 control-label">Username</label>
    <div class="col-sm-4">
      <input type="text" name="username" value="<?php echo $username; ?>" class="form-control" id="username" placeholder="Username" required autofocus>
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-sm-offset-2 col-sm-2 control-label">Password</label>
    <div class="col-sm-4">
      <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-4">
      <input type="submit" name="submit" value="Submit" class="btn btn-block btn-primary">
    </div>
  </div>
</form>

<?php require_once $rootPath . "/footer.php"; ?>