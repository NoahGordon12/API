<?php
include("../../config/database_handler.php");
include("../../objects/Products.php");

$productname = "";
$description = "";
$image = "";
$price = "";

if(isset($_GET['id'])){
    $id = $_GET['id'];
} else {
    $error = new stdClass();
    $error->message = "Id not specified";
    $error->code = "0002";
    echo json_encode($error);
    die();
}

if(isset($_GET['productname'])) {
    $productname = $_GET['productname'];
}
if(isset($_GET['description'])) {
    $description = $_GET['description'];
}
if(isset($_GET['image'])) {
    $image = $_GET['image'];
}
if(isset($_GET['price'])) {
    $price = $_GET['price'];
}

$product = new Products($pdo);
echo json_encode($product->UpdateProduct($id, $productname, $description, $image, $price));