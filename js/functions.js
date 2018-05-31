$(".addToCart").click(addToCart);

function addToCart() {
  var currentButton = this;
  currentButton.disabled = true;
  var id = $(this).attr("id");
  var settings = {
    type: "POST",
    dataType: "json",
    url: "../src/api.php",
    data: {
        flag: "addToCart",
        id: id
    },
    success: function (response) {
      $(currentButton).removeClass("btn-primary");
      $(currentButton).removeClass("addToCart");
      $(currentButton).text("In Cart");
      $(currentButton).addClass("btn-success");
      $(currentButton).addClass("inCart");
      currentButton.disabled = false;
      $(currentButton).unbind("click");
      $(currentButton).click(removeFromCart);
    },
    error: function (response) {
        console.log(response);
    }
  };
  $.ajax(settings);
  return false;
}

$(".inCart").click(removeFromCart);

function removeFromCart() {
  var currentButton = this;
  currentButton.disabled = true;
  var id = $(this).attr("id");
  var settings = {
    type: "POST",
    dataType: "json",
    url: "../src/api.php",
    data: {
        flag: "removeFromCart",
        id: id
    },
    success: function (response) {
      $(currentButton).removeClass("btn-success");
      $(currentButton).removeClass("inCart");
      $(currentButton).text("Add to Cart");
      $(currentButton).addClass("btn-primary");
      $(currentButton).addClass("addToCart");
      currentButton.disabled = false;
      $(currentButton).unbind("click");
      $(currentButton).click(addToCart);
    },
    error: function (response) {
        console.log(response);
    }
  };
  $.ajax(settings);
  return false;
}