$(function() {
  $('[data-toggle="tooltip"]').tooltip();
});

$(function() {
  $(".datepicker").datepicker({
    firstDay: 1,
    showButtonPanel: true,
    currentText: "Today",
    closeText: "Close",
    constraintInput: true,
    changeMonth: true,
    changeYear: true,
    dateFormat: "yy-mm-dd"
  });
});

function add_to_cart() {
  var current = this;
  current.disabled = true;
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '../src/add_to_cart.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function () {
    if(xhr.readyState == 4 && xhr.status == 200) {
      var result = xhr.responseText;
      if (result == 'true') {
        current.innerHTML = "In Cart";
        current.classList.remove("btn-info");
        current.classList.add("btn-success");
        current.classList.remove("add_to_cart");
        current.classList.add("in_cart");
        buttons = document.getElementsByClassName("add_to_cart");
        for (i = 0; i < buttons.length; i++) {
          buttons.item(i).addEventListener("click", add_to_cart);
        }
        buttons = document.getElementsByClassName("in_cart");
        for(i=0; i < buttons.length; i++) {
          buttons.item(i).addEventListener("click", remove_from_cart);
        }
        current.disabled = false;
      }
    }
  };
  xhr.send("id=" + current.id);
}

var buttons = document.getElementsByClassName("add_to_cart");
for (i = 0; i < buttons.length; i++) {
  buttons.item(i).addEventListener("click", add_to_cart);
}

function remove_from_cart() {
  var current = this;
  current.disabled = true;
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '../src/remove_from_cart.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
  xhr.onreadystatechange = function () {
    if(xhr.readyState == 4 && xhr.status == 200) {
      var result = xhr.responseText;
      if(result == 'true') {
        current.innerHTML = "Add to Cart";
        current.classList.remove("btn-success");
        current.classList.add("btn-info");
        current.classList.remove("in_cart");
        current.classList.add("add_to_cart");
        buttons = document.getElementsByClassName("add_to_cart");
        for (i = 0; i < buttons.length; i++) {
          buttons.item(i).addEventListener("click", add_to_cart);
        }
        buttons = document.getElementsByClassName("in_cart");
        for(i=0; i < buttons.length; i++) {
          buttons.item(i).addEventListener("click", remove_from_cart);
        }
        current.disabled = false;
      }
    }
  };
  xhr.send("id=" + current.id);
}

var buttons = document.getElementsByClassName("in_cart");
for(i=0; i < buttons.length; i++) {
  buttons.item(i).addEventListener("click", remove_from_cart);
}
