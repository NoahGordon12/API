<?php
    include('../../config/database_handler.php');
    include('../../objects/Users.php');

    if(isset($_GET['username']) && isset($_GET['email']) && isset($_GET['password'])){
        $username = $_GET['username'];
        $email = $_GET['email'];
        $password = $_GET['password'];
    } else{
        $error = new stdClass();
        $error->message="Not enough data to register";
        $error->code="0006";
        print_r(json_encode($error));
        die();
    }
    
    $user = new Users($pdo);
    print_r(json_encode($user->createUser($username, $email, $password)));

?>