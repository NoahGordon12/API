<?php

    include('../../config/database_handler.php');
    include('../../objects/Users.php');

/*     $username = $_GET['username'];
    $password = $_GET['password'];

    $user = new Users($pdo);
    $return = stdClass();
    $return->token = $user->loginUser($username, $password);
    print_r(json_encode($return));   */
 

    if(isset($_GET['username'])&&isset($_GET['password'])){
        $username = $_GET['username'];
        $password = $_GET['password'];

        $user = new Users($pdo);
        $return = new stdClass();
        $return->token = $user->loginUser($username, $password);
        print_r(json_encode($return));
    } else {
        $error = new stdClass();
        $error->message="Username & password not specified!";
        $error->code="0002";
        print_r(json_encode($error));
        die();
    } 