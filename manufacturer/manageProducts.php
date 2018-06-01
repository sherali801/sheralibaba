<?php

require_once "include.php";

$manufacturer = getManufacturerProfile($_SESSION["id"]);
$manufacturerId = $manufacturer["manufacturerId"];
$products = getAllProductsByManufacturerId($manufacturerId);

?>

<?php require_once $manufacturerPath . "/header.php"; ?>

  <?php if ($products != null) { ?>
    <h3 class="text-center">Manage Products</h3>
    <table class="table table-bordered table-striped">
      <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Visibility</th>
        <th>Image</th>
        <th>Edit</th>
      </tr>
      <?php foreach ($products as $product) { ?>
        <tr>
          <td><?php echo $product["product_name"]; ?></td>
          <td><?php echo $product["price"]; ?></td>
          <td><?php echo $product["quantity"]; ?></td>
          <td><?php echo $product["visibility"] == 1 ? "Yes" : "No"; ?></td>
          <td><img src="<?php echo $product["image_url"]; ?>" height="100" width="100"></td>
          <td><a class="btn btn-primary" href="editProduct.php?id=<?php echo $product["id"]; ?>">Edit</a></td>
        </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <h3 class="text-center">No Product Found.</h3>
  <?php } ?>

<?php require_once $manufacturerPath . "/footer.php"; ?>