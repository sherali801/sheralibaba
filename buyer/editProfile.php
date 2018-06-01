<?php

require_once "include.php";

$q = "";

$id = $_SESSION["id"];
$buyer = getBuyerProfile($id);
extract($buyer);

if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $buyerId = $_POST["buyerId"];
  $firstName = $_POST["firstName"];
  $lastName = $_POST["lastName"];
  $email = $_POST["email"];
  $contactNo = $_POST["contactNo"];
  $addressId = $_POST["addressId"];
  $street = $_POST["street"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $country = $_POST["country"];
  $zip = $_POST["zip"];
  $dt = MySqlFormattedTime(time());
  if (duplicateUsernameWithId($username, $id)) {
    if (updateBuyerProfile($id, $username, $password, $buyerId, $firstName, $lastName, $email, $contactNo, $addressId, $street, $city, $state, $country, $zip, $dt)) {
      $_SESSION["successes"][] = "Profile has been updated.";
    } else {
      $_SESSION["errors"][] = "Profile was not updated.";
    }
  } else {
    $_SESSION["errors"][] = "Login already exists.";
  }
}

?>

<?php require_once $buyerPath . "/header.php"; ?>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-horizontal">
  <h3 class="text-center">Edit Profile</h2>
  <input type="hidden" name="buyerId" value="<?php echo $buyerId; ?>">
  <input type="hidden" name="addressId" value="<?php echo $addressId; ?>">
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
    <label for="firstName" class="col-sm-offset-2 col-sm-2 control-label">Name</label>
    <div class="col-sm-2">
      <input type="text" name="firstName" value="<?php echo $firstName; ?>" class="form-control" id="firstName" placeholder="First" required>
    </div>
    <div class="col-sm-2">
      <input type="text" name="lastName" value="<?php echo $lastName; ?>" class="form-control" id="lastName" placeholder="Last Name" required>
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
    <div class="col-sm-offset-4 col-sm-4">
      <input type="submit" name="submit" value="Submit" class="btn btn-block btn-primary">
    </div>
  </div>
</form>

<?php require_once $buyerPath . "/footer.php"; ?>