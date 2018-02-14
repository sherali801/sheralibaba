<?php

function MySqlFormattedTime($dt) {
  return strftime("%Y-%m-%d %H:%M:%S", $dt);
}

function array_remove($element, $array) {
  $index = array_search($element, $array);
  array_splice($array, $index, 1);
  return $array;
}

?>