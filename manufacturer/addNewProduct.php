<?php

require_once "include.php";

$productName = "";
$price = "";
$quantity = "";
$visibility = 1;
$imageURL = "";
$categoryId = 0;
$description = "";

$categories = getAllCategories();

if (isset($_POST["submit"])) {
  $productName = $_POST["productName"];
  $price = $_POST["price"];
  $quantity = $_POST["quantity"];
  $visibility = $_POST["visibility"];
  $imageURL = $_POST["imageURL"];
  $categoryId = $_POST["categoryId"];
  $description = $_POST["description"];
  $dt = MySqlFormattedTime(time());

  $id = $_SESSION["id"];
  $manufacturer = getManufacturerProfile($id);
  $manufacturerId = $manufacturer["manufacturerId"];

  if (addNewProduct($productName, $price, $quantity, $visibility, $imageURL, $categoryId, $description, $manufacturerId, $dt)) {
    $_SESSION["successes"][] = "Product has been added.";
    $productName = "";
    $price = "";
    $quantity = "";
    $visibility = 1;
    $imageURL = "";
    $categoryId = 0;
    $description = "";
  } else {
    $_SESSION["errors"][] = "Product was not added.";
  }
}

?>

<?php require_once $manufacturerPath . "/header.php"; ?>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form-horizontal">
  <h3 class="text-center">Add New Product</h3>
  <div class="form-group">
    <label for="productName" class="col-sm-offset-2 col-sm-2 control-label">Product Name</label>
    <div class="col-sm-4">
      <input type="text" name="productName" value="<?php echo $productName; ?>" class="form-control" id="productName" placeholder="Product Name" required autofocus>
    </div>
  </div>
  <div class="form-group">
    <label for="price" class="col-sm-offset-2 col-sm-2 control-label">Price</label>
    <div class="col-sm-4">
      <input type="number" name="price" value="<?php echo $price; ?>" class="form-control" id="price" placeholder="Price" required>
    </div>
  </div>
  <div class="form-group">
    <label for="quantity" class="col-sm-offset-2 col-sm-2 control-label">Quantity</label>
    <div class="col-sm-4">
      <input type="number" name="quantity" value="<?php echo $quantity; ?>" class="form-control" id="quantity" placeholder="Quantity" required>
    </div>
  </div>
  <div class="form-group">
    <label for="visibilityYes" class="col-sm-offset-2 col-sm-2 control-label">Visibility</label>
    <div class="col-sm-4">
      <label class="radio-inline">
        <input type="radio" name="visibility" id="visibilityYes" value="1" <?php echo $visibility == 1 ? "checked" : ""; ?>> Yes
      </label>
      <label class="radio-inline">
        <input type="radio" name="visibility" id="visibilityNo" value="0" <?php echo $visibility == 0 ? "checked" : ""; ?>> No
      </label>
    </div>
  </div>
  <div class="form-group">
    <label for="imageURL" class="col-sm-offset-2 col-sm-2 control-label">Image URL</label>
    <div class="col-sm-4">
      <input type="url" name="imageURL" value="<?php echo $imageURL; ?>" class="form-control" id="imageURL" placeholder="Image URL" required>
    </div>
  </div>
  <div class="form-group">
    <label for="categoryId" class="col-sm-offset-2 col-sm-2 control-label">Category</label>
    <div class="col-sm-4">
    <select name="categoryId" class="form-control">
      <option value="0">--Select--</option>
      <?php foreach ($categories as $category) { ?>
        <option value="<?php echo $category["id"]; ?>" <?php echo $category["id"] == $categoryId ? "selected" : ""; ?>><?php echo $category["category_name"]; ?></option>
      <?php } ?>
    </select>
    </div>
  </div>
  <div class="form-group">
      <label for="description" class="col-sm-4 control-label">Product Description</label>
      <div class="col-sm-4">
        <textarea name="description" class="form-control" id="description" rows="3" placeholder="Product Description"><?php echo $description; ?></textarea>
      </div>
    </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-4">
      <input type="submit" name="submit" value="Submit" class="btn btn-block btn-primary">
    </div>
  </div>
</form>

<?php require_once $manufacturerPath . "/footer.php"; ?>