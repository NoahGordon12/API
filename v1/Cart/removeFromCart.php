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
        if(isset($_GET['userid']) & isset($_GET['productid'])){
            $userId =$_GET['userId'];
            $productId =$_GET['productId'];
            $cart = new Cart($pdo);
            print_r(json_encode($cart->removeFromCart($userid, $productid)));
        } else {
            $error = new stdClass();
            $error->message = "Product id or User id not specified";
            $error->code = "0002";
            echo json_encode($error);
            die();
        }
        
    
    } else {
        $error = new stdClass();
        $error->message = "Invalid token! Login to create a new token.";
        $error->code = "0010";
        print_r(json_encode($error));
    }

 
?>