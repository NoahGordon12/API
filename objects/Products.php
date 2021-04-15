<?php

class Products {

    private $database_connection;
    private $productname;
    private $description;
    private $image;
    private $price;

    function __construct($db) {
        $this->database_connection = $db;
    }

    function addProduct($productname_IN, $description_IN, $image_IN, $price_IN) {
        if(!empty($productname_IN) && !empty($description_IN) && !empty($image_IN) && !empty($price_IN)){

            $sql = "SELECT productname, description, image, price FROM products WHERE productname = :productname_IN AND description = :description_IN AND image = :image_IN AND price = :price_IN ";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":productname_IN", $productname_IN);
            $stmt->bindParam(":description_IN", $description_IN);
            $stmt->bindParam(":image_IN", $image_IN);
            $stmt->bindParam(":price_IN", $price_IN);
            $stmt->execute();
            $response = new stdClass();
            if($stmt->rowCount() > 0) {
                $response->text = "This product is already added!";
                return $response;
            }

            $sql = "INSERT INTO products (productname, description, image ,price) VALUES(:productname_IN, :description_IN, :image_IN, :price_IN)";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":productname_IN", $productname_IN);
            $stmt->bindParam(":description_IN", $description_IN);
            $stmt->bindParam(":image_IN", $image_IN);
            $stmt->bindParam(":price_IN", $price_IN);

            if($stmt->execute()) {
                $response->text = "Product added!";
                return $response;
            } else {
                $error = new stdClass();
                    $error->message ="Couldn't create post!";
                    $error->code="0008";
                    print_r(json_encode($error));
                    die();
            }
            
        } else {
            $error = new stdClass();
                $error->message="Not enough data to add a product";
                $error->code="0006";
                print_r(json_encode($error));
                die();
        }

    }

   /*  function DeleteProduct($id) {

    /*     $sql = "SELECT id FROM cart WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $id);
        $stmt->execute();

            if($stmt->rowCount() > 0) {
                $error = new stdClass();
                    $error->message = "This product can't be deleted because its added by a user to their shoppingcart!";
                    $error->code="0011";
                    print_r(json_encode($error));
                    die();
            } 

            $sql = "DELETE FROM products WHERE id = :Id_IN";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":id_IN", $id);
            $stmt->execute();

        
        
            if($stmt->rowCount() > 0) {
                $response = new stdClass();
                    $response->text = "Product with id $id removed!";
                    return $response;
            }
            else {
                $error = new stdClass();
                    $error->message = "No product with id=$id was found!";
                    $error->code = "0003";
                    print_r(json_encode($error));
                    die();   
            }     

    } */

    function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id=:id_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":id_IN", $id);
        if($statement->execute()) {
            $message = new stdClass();
            $message->message = "Product deleted!";
            return json_encode($message); 
        }
    }

    function listProducts() {
        $sql = "SELECT productname, description, image, price FROM products";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function updateProduct($id, $productname ="", $description = "", $image = "", $price = "") {

        $sql = "SELECT productname, description, image, price FROM products WHERE productname = :productname_IN OR description = :description_IN OR image = :image_IN OR price = :price_IN ";
            $stmt = $this->database_connection->prepare($sql);
            $stmt->bindParam(":productname_IN", $productname);
            $stmt->bindParam(":description_IN", $description);
            $stmt->bindParam(":image_IN", $image);
            $stmt->bindParam(":price_IN", $price);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $error = new stdClass();
                $error->message = "Atleast one value has to be new!";
                $error->code = "0012";
                echo json_encode($error);
                die();
            
            }

            $error = new stdClass();
            if(!empty($productname)) {
                $error->message = $this->UpdateProductname($id, $productname);
            }
            if(!empty($description)) {
                $error->message = $this->UpdateDescription($id, $description);
            }
            if(!empty($image)) {
                $error->message = $this->UpdateImage($id, $image);
            }
            if(!empty($price)) {
                $error->message = $this->UpdatePrice($id, $price);
            }
            
            return $error;
    }

    function UpdateProductName($id, $productname) {
        $sql = "UPDATE products SET productname = :productname_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $id);
        $stmt->bindParam(":productname_IN", $productname);
        $stmt->execute();

        if($stmt->rowCount() < 1) {
            return "No product with id=$id was found!";
        }
        else {
            return "Successfully updated!";
        }
    }

    function UpdateDescription($id, $description) {
        $sql = "UPDATE products SET description = :description_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $id);
        $stmt->bindParam(":description_IN", $description);
        $stmt->execute();

        if($stmt->rowCount() < 1) {
            return "No product with id=$id was found!";
        }
        else {
            return "Successfully updated!";
        }
    }   

    function UpdateImage($id, $image) {
        $sql = "UPDATE products SET image = :image_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $id);
        $stmt->bindParam(":image_IN", $image);
        $stmt->execute();

        if($stmt->rowCount() < 1) {
            return "No product with id=$id was found!";
        }
        else {
            return "Successfully updated!";
        }
    }

    function UpdatePrice($id, $price) {
        $sql = "UPDATE products SET price = :price_IN WHERE id = :id_IN";
        $stmt = $this->database_connection->prepare($sql);
        $stmt->bindParam(":id_IN", $id);
        $stmt->bindParam(":price_IN", $price);
        $stmt->execute();

        if($stmt->rowCount() < 1) {
            return "No product with id=$id was found!";
        }
        else {
            return "Successfully updated!";
        }
    }



    
}