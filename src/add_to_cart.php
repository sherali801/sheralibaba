<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}

if(!is_ajax_request()) { exit; }

// extract $id
$raw_id = isset($_POST['id']) ? $_POST['id'] : '';

if(preg_match("/(\d+)/", $raw_id, $matches)) {
    $id = $matches[1];
    if(!in_array($id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $id;
    }
    echo 'true';
} else {
    echo 'false';
}

?>
