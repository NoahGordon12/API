<?php

class Cart {

    private $database_connection;


    function __construct($db){
        $this->database_connection = $db;
    }

    function addToCart($productid_IN, $userid_IN, $token_IN,) {

        $sql = "SELECT * FROM cart WHERE userid = :userid_IN AND productid = :productid_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":userid_IN", $userid_IN);
        $stmt->bindParam(":productid_IN", $productid_IN);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $error = new stdClass();
                $error->message = "You have already added this product to your cart!";
                $error->code = "0009";
                echo json_encode($error);
                die();
        }

        $sql = "SELECT * FROM users WHERE id = :userId_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":userId_IN", $userId_IN);
        $stmt->execute();
        $userCount = $stmt->rowCount();
        if($userCount < 1) {
            $error = new stdClass();
                $error->message = "User not found!";
                $error->code = "0003";
                echo json_encode($error);
                die();
        }
        

        $sql = "SELECT * FROM products WHERE id = :productId_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":productid_IN", $productid_IN);
        $stmt->execute();
        $productCount = $stmt->rowCount();
        if($productCount < 1) {
            $error = new stdClass();
                $error->message = "Product id not found!";
                $error->code = "0003";
                echo json_encode($error);
                die();
        }

        $sql = "INSERT INTO cart (productid, userid, token) VALUES(:productId_IN, :userId_IN, :token_IN, NOW())";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":productid_IN",$productid_IN);
        $stmt->bindParam(":userid_IN",$userid_IN);
        $stmt->bindParam(":token_IN",$token_IN);
        
      /*   if($stmt->execute()){
            $response =  new stdClass();
            $response->text= "$quantity_IN x of the product with id $productId_IN was added to your cart!";
            return $response;
        } */

    }

    function RemoveFromCart($userid_IN, $productid_IN){

        $sql = "SELECT * FROM cart WHERE userid = :userid_IN AND productid = :productid_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":productid_IN", $productid_IN);
        $stmt->bindParam(":userid_IN", $userid_IN);
        $stmt->execute();
        if($stmt->rowCount() < 1) {
            $error = new stdClass();
                $error->message = "The user with id $userid_IN doesnt have the product with id $productid_IN in their cart!";
                $error->code = "0003";
                echo json_encode($error);
                die();
        }

        $sql = "DELETE FROM cart WHERE userid = :userid_IN AND productid = :productid_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":userid_IN", $userid_IN);
        $stmt->bindParam(":productid_IN", $productid_IN);
        
        if($stmt->execute()){
            $response =  new stdClass();
            $response->text= "Product with id $productid_IN was removed from your cart!";
            return $response;
        }  
    }

    function CheckoutCart($token_IN) {
        $sql ="DELETE FROM cart WHERE token = :token_IN";
        $stmt=$this->database_connection->prepare($sql);
        $stmt->bindParam(":token_IN",$token_IN);

        if($stmt->execute()) {
            $response =  new stdClass();
            $response->text = "Purchase complete!";
            return $response;
        }
    }
    





}