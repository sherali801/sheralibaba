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
                <li><a href="index.php?manufacturerId=<?php echo $manufacturer["id"]; ?>"><?php echo $manufacturer["business_name"]; ?></a></li>
              <?php } ?>
            </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <?php foreach ($categories as $category) { ?>
              <li><a href="index.php?categoryId=<?php echo $category["id"]; ?>"><?php echo $category["category_name"]; ?></a></li>
            <?php } ?>
          </ul>
        </li>
      </ul>
      <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get" class="navbar-form navbar-left">
        <div class="form-group">
          <?php if (isset($_GET["manufacturerId"]) && !empty($_GET["manufacturerId"])) { ?>
            <input type="hidden" name="manufacturerId" value="<?php echo $manufacturerId; ?>">
          <?php } ?>
          <?php if (isset($_GET["categoryId"]) && !empty($_GET["categoryId"])) { ?>
            <input type="hidden" name="categoryId" value="<?php echo $categoryId; ?>">
          <?php } ?>
          <input type="text" name="q" value="<?php echo $q; ?>" class="form-control" placeholder="Search">
        </div>
        <input type="submit" value="Submit" class="btn btn-primary">
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="advanceSearch.php">Advance Search</a></li>
        <li><a href="login.php">Login</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Signup <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="manufacturerSignup.php">Manufacturer</a></li>
            <li><a href="buyerSignup.php">Buyer</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
</nav>