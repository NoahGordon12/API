<?php
include('../../config/database_handler.php');
include('../../objects/Cart.php');
include('../../objects/Users.php');

$token = "";
    if(isset($_GET['token'])) {
        $token = $_GET['token'];
    } else {
        $error = new stdClass();
        $error->message = "No token specified!";
        $error->code = "0005";
        print_r(json_encode($error));
        die();
    }

$user = new Users($pdo);
$cart = new Cart($pdo);
    if($user->IsTokenValid($token)) {
        $carts = $cart->checkoutCart($token);
        print_r(json_encode($carts));
    
    } else {
        $error = new stdClass();
        $error->message = "Invalid token! Login to create a new token.";
        $error->code = "0010";
        print_r(json_encode($error));
    }
?>