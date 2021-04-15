<?php
include("../../config/database_handler.php");
include("../../objects/Products.php");

if(isset($_GET['productname']) && isset($_GET['description']) && isset($_GET['image']) && isset($_GET['price']) ){
    $productname = $_GET['productname'];
    $description = $_GET['description'];
    $image = $_GET['image'];
    $price = $_GET['price'];
    $product  = new Products($pdo);
    print_r(json_encode($product->AddProduct($productname, $description, $image, $price)));
} else  {
    $error = new stdClass();
    $error->message = "Not enough data for addition of product!";
    $error->code = "0006";
    print_r(json_encode($error));
    die();

}
