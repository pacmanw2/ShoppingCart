<?php
session_start();

// get the product id
//$id = isset($_GET['id']) ? $_GET['id'] : 1;
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = 1;
}

//$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : "";
if(isset($_GET['quantity'])) {
    $quantity = $_GET['quantity'];
} else {
    $quantity = "";
}

// make quantity a minimum of 1
//$quantity = $quantity <= 0 ? 1 : $quantity;
if($quantity <= 0) {
    $quantity = 1;
}

// remove the item from the array
unset($_SESSION['cart'][$id]);

// add the item with updated quantity
$_SESSION['cart'][$id]=array(
    'quantity'=>$quantity
);

// redirect to product list and tell the user it was added to cart
header('Location: cart.php?action=quantity_updated&id=' . $id);
