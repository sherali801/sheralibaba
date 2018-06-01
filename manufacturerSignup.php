<?php

require_once "include.php";

$q = "";

$username = "";
$businessName = "";
$email = "";
$contactNo = "";
$url = "";
$description = "";
$street = "";
$city = "";
$state = "";
$country = "";
$zip = "";

if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $businessName = $_POST["businessName"];
  $email = $_POST["email"];
  $contactNo = $_POST["contactNo"];
  $url = $_POST["url"];
  $description = $_POST["description"];
  $street = $_POST["street"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $country = $_POST["country"];
  $zip = $_POST["zip"];
  $dt = MySqlFormattedTime(time());
  if (duplicateUsername($username)) {
    if (duplicateBusinessName($businessName)) {
      if (createManufacturerProfile($username, $password, $businessName, $email, $contactNo, $url, $description, $street, $city, $state, $country, $zip, $dt)) {
        $_SESSION["successes"][] = "Account has been created.";
        $username = "";
        $businessName = "";
        $email = "";
        $contactNo = "";
        $url = "";
        $description = "";
        $street = "";
        $city = "";
        $state = "";
        $country = "";
        $zip = "";
      } else {
        $_SESSION["errors"][] = "Account was not created.";
      }
    } else {
      $_SESSION["errors"][] = "Business Name already exists.";
    }
  } else {
    $_SESSION["errors"][] = "Login already exists.";
  }
}

?>

<?php require_once $rootPath . "/header.php"; ?>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-horizontal">
  <h3 class="text-center">Signup</h3>
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
    <label for="businessName" class="col-sm-offset-2 col-sm-2 control-label">Business Name</label>
    <div class="col-sm-4">
      <input type="text" name="businessName" value="<?php echo $businessName; ?>" class="form-control" id="businessName" placeholder="Business Name" required>
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-sm-offset-2 col-sm-2 control-label">Email</label>
    <div class="col-sm-4">
      <input type="email" name="email" value="<?php echo $email; ?>" class="form-control" id="email" placeholder="Email" required>
    </div>
  </div>
  <div class="form-group">
    <label for="contactNo" class="col-sm-offset-2 col-sm-2 control-label">Contact No.</label>
    <div class="col-sm-4">
      <input type="text" name="contactNo" value="<?php echo $contactNo; ?>" class="form-control" id="contactNo" placeholder="Contact No." required>
    </div>
  </div>
  <div class="form-group">
    <label for="url" class="col-sm-offset-2 col-sm-2 control-label">URL</label>
    <div class="col-sm-4">
      <input type="url" name="url" value="<?php echo $url; ?>" class="form-control" id="url" placeholder="URL">
    </div>
  </div>
  <div class="form-group">
    <label for="street" class="col-sm-offset-2 col-sm-2 control-label">Address</label>
    <div class="col-sm-4">
      <input type="text" name="street" value="<?php echo $street; ?>" class="form-control" id="street" placeholder="Street" required>
    </div>
    <div class="col-sm-offset-4 col-sm-4">
      <input type="text" name="city" value="<?php echo $city; ?>" class="form-control" id="city" placeholder="City" required>
    </div>
    <div class="col-sm-offset-4 col-sm-4">
      <input type="text" name="state" value="<?php echo $state; ?>" class="form-control" id="state" placeholder="State" required>
    </div>
    <div class="col-sm-offset-4 col-sm-4">
      <input type="text" name="country" value="<?php echo $country; ?>" class="form-control" id="country" placeholder="Country" required>
    </div>
    <div class="col-sm-offset-4 col-sm-4">
      <input type="text" name="zip" value="<?php echo $zip; ?>" class="form-control" id="zip" placeholder="zip" required>
    </div>
  </div>
  <div class="form-group">
      <label for="description" class="col-sm-4 control-label">Business Description</label>
      <div class="col-sm-4">
        <textarea name="description" class="form-control" id="description" rows="3" placeholder="Business Description"><?php echo $description; ?></textarea>
      </div>
    </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-4">
      <input type="submit" name="submit" value="Submit" class="btn btn-block btn-primary">
    </div>
  </div>
</form>

<?php require_once $rootPath . "/footer.php"; ?>