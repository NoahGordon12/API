<?php
/// Enklaste sättet att logga ut är att ta bort tokens från session i databasen! Men inget krav i uppgiften att logga ut?
class Users {

    private $database_connection;
    /* private $userid;
    private $username;
    private $email;
    private $password;
    private $role; */

    function __construct($db) {
        $this->database_connection = $db;
    }

    function createUser($username_IN, $email_IN, $password_IN) {
        if(!empty($username_IN) && !empty($email_IN) && !empty($password_IN)) {

            $sql = "SELECT id FROM users WHERE username=:username_IN OR email=:email_IN";
            $statement = $this->database_connection->prepare($sql);
            $statement ->bindParam(":username_IN", $username_IN); 
            $statement ->bindParam(":email_IN", $email_IN);

            if(!$statement->execute()) {
                echo "Could not execute query!";
                die();
            }

            $num_rows = $statement->rowCount();  //antal rader som matchar/påverkas av vår $sql SELECT
            if($num_rows > 0) {
                echo "The user already exists";
                die();
            }

            $sql = "INSERT INTO users (username, email, password, role) VALUES(:username_IN, :email_IN, :password_IN, 'user')";
            $statement = $this->database_connection->prepare($sql);
            $statement->bindParam(":username_IN", $username_IN);
            $statement->bindParam(":email_IN", $email_IN);
            $salt1 = "aGsdf45L"; 
            $salt2 = "Suasg25R";
            $pw = md5($salt1.$password_IN.$salt2); //Krypterar lösenord
            $statement->bindParam(":password_IN", $pw);

            if(!$statement->execute()) {
                echo "Could not create user!";    
            } else {
                $response = new stdClass();
                $response->text = "User added!";
                return $response;
            }

            $this->username = $username_IN;
            $this->email = $email_IN; 
            die();

        } else {
            $error = new stdClass();  //errorkoder man skapar för sin api
            $error->message = "All arguments need a value!";
            $error->code = "0001";
            print_r(json_encode($error));
            die();
        }
    }

    function loginUser($username_IN, $password_IN) {
        $sql = "SELECT id, username, email, role FROM users WHERE username=:username_IN AND password=:password_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":username_IN", $username_IN);
        $salt1 = "aGsdf45L";
        $salt2 = "Suasg25R";
        $pw = md5($salt1.$password_IN.$salt2); //krypterar lösenord
        $statement->bindParam(":password_IN", $pw);

        $statement->execute();
       
        if($statement->rowCount() == 1) { 
            $row = $statement->fetch();
            return $this->createToken($row['id'], $row['username']);
        } else {
            $error = new stdClass();
                $error->message = "Wrong username or password!";
                $error->code = "0004";
                echo json_encode($error);
                die();
        } 

    }

    /* function createToken($id, $username){

        $checked_token = $this->CheckToken($id);

        if($checked_token != false) {
            return $checked_token;
        }

        $token = md5(time() . $id . $username);

        $sql = "INSERT INTO sessions (userid, toke, last_used) VALUES(:user_IN, :token_IN, :last_used_IN)"; // här sätter vi placeholders vid VALUES
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":userid_IN", $id); // här sätter vi värdet
        $statement->bindParam(":token_IN", $token);
        $time = time();
        $statement->bindParam(":last_used_IN", $time);

        $statement->execute();
        return $token;
    }

    function checkToken($id) {
        $sql = "SELECT token, last_used FROM sessions WHERE userid=:userid_IN AND last_used > :active_time_IN";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":userid_IN", $id); 
        $active_time = time() - (60*60);

        $statement->bindParam(":active_time_IN", $active_time);

        $statement->execute();

        $return = $statement->fetch();
        
        if(isset($return['token'])) {
            return $return['token'];
        } else {
            return false;
        }

    }

    function validToken($token) {
        $sql = "SELECT token, last_used FROM sessions WHERE token=:token_IN AND last_used > :active_time_IN LIMIT 1";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":token_IN", $token);
        $active_time = time() - (60*60);

        $statement->bindParam(":active_time_IN", $active_time);

        
        $statement->execute();
        
        $return = $statement->fetch();

        if(isset($return['token'])) {

            $this->UpdateToken($return['token']);

            return true;
            
        } else {
            return false;
        }
    }

    function UpdateToken($token) {
        $sql = "UPDATE sessions SET last_used=:last_used_IN WHERE token=:token_IN";
        $statement = $this->database_connection->prepare($sql);
        $time = time();
        $statement->bindParam(":last_used_IN", $time);
        $statement->bindParam(":token_IN", $token);
        $statement->execute();
    } */
  




 function CreateToken($id, $username) {

    $checked_token = $this->checkToken($id);

    if($checked_token != false) {
        return $checked_token;
    }
        $token = md5(time() . $id . $username);

        $sql = "INSERT INTO sessions (userid, token, last_used) VALUES(:userid_IN, :token_IN, :last_used_IN)";
        $statement = $this->database_connection->prepare($sql);
        $statement->bindParam(":userid_IN",$id);
        $statement->bindParam(":token_IN",$token);
        $time = time();
        $statement->bindParam(":last_used_IN",$time);

        $statement->execute();

        return $token;

}

function CheckToken($id) {
    $sql = "SELECT token, last_used FROM sessions WHERE userId=:userid_IN AND last_used > :active_time_IN";
    $statement =$this->database_connection->prepare($sql);
    $statement->bindParam(":userid_IN",$id);
    $active_time = time() - (60*60);
    $statement->bindParam(":active_time_IN", $active_time);

    $statement->execute();

    $return = $statement->fetch();

    if(isset($return['token'])){
        return $return['token'];
    }
    else {
        return false;
    }
}

function IsTokenValid($token) {
    $sql = "SELECT token, last_used FROM sessions WHERE token=:token_IN AND last_used > :active_time_IN";
    $statement =$this->database_connection->prepare($sql);
    $statement->bindParam(":token_IN",$token);
    $active_time= time() - (60*60);
    $statement->bindParam(":active_time_IN",$active_time);

    $statement->execute();

    $return = $statement->fetch();

    if(isset($return['token'])){
        $this->UpdateToken($return['token']);

        return true;
    }
    else {
        return false;
    }
}

function UpdateToken($token) {
    $sql = "UPDATE sessions SET last_used=:last_used_IN WHERE token=:token_IN";
    $statement = $this->database_connection->prepare($sql);
    $time = time();
    $statement->bindParam(":last_used_IN", $time);
    $statement->bindParam(":token_IN", $token);
    $statement->execute();
}
 

}    