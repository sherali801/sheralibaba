<?php

$manufacturers = getAllManufacturers();
$categories = getAllCategories();

?>

<nav class="navbar navbar-default">
	<!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">sheralibaba</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manufacturers <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <?php foreach ($manufacturers as $manufacturer) { ?>
              <li><a href="productsByManufacturer.php?id=<?php echo $manufacturer["id"]; ?>"><?php echo $manufacturer["business_name"]; ?></a></li>
            <?php } ?>
          </ul>
		    </li>
		    <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <?php foreach ($categories as $category) { ?>
              <li><a href="productsByCategory.php?id=<?php echo $category["id"]; ?>"><?php echo $category["category_name"]; ?></a></li>
            <?php } ?>
          </ul>
        </li>
      </ul>
      <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get" class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" name="q" value="<?php echo $q; ?>" class="form-control" placeholder="Search">
        </div>
        <input type="submit" value="Submit" class="btn btn-primary">
      </form>
      <ul class="nav navbar-nav navbar-right">
	  	<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="editProfile.php">Edit Profile</a></li>
            <li><a href="viewCart.php">View Cart</a></li>
            <li><a href="viewOrders.php">View Orders</a></li>
          </ul>
        </li>
        <li><a href="editProfile.php">Hi! <?php echo $_SESSION["username"]; ?></a></li>
        <li><a href="../logout.php">Logout</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
</nav>