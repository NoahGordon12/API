<?php
include("../../config/database_handler.php");
include("../../objects/Products.php");

if(empty($_GET['id'])) {
    $error = new stdClass();
    $error->message = "No id specified!";
    $error->code = "0004";
    print_r(json_encode($error));
    die();
}

$post = new Products($pdo); 
echo $post->deleteproduct($_GET['id']);

