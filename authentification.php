<?php

    include('jwt_utils.php');
    include('myAuthentificationFunctions.php');

    header("Content-Type:application/json");

    $http_method = $_SERVER['REQUEST_METHOD'];
    
    switch ($http_method){
        case "POST":
            $data = (array) json_decode(file_get_contents('php://input'), TRUE);            
            if ( isValidUser($data['username'], hash_hmac('SHA256', $data['password'], true)) ) {
                $username = $data['username'];
                $headers = array('alg'=>'HS256', 'typ'=>'JWT');
                $role = setRole($username);
                $payload = array('username'=>$username, 'exp'=>(time()+3600), 'role'=>$role[0]);
                $jwt = generate_jwt($headers, $payload);
                deliver_response(200, "Authentification reussie", $jwt.get_bearer_token() );
            } else {
                deliver_response(401, "User " . $data['username'] ." invalid.", NULL);
            }
            break;
        default :
            deliver_response(405, "Invalid operation " . $http_method . ".", NULL);
            break; 
        }

    function deliver_response($status, $status_message, $data){
        header("HTTP/1.1 $status $status_message");
        $response['status'] = $status;
        $response['status_message'] = $status_message;
        $response['data'] = $data;
        $json_response = json_encode($response);
        echo $json_response;
    }

    function deliver_data($data){
        $response['data'] = $data;
        $json_response = json_encode($response);
        echo $json_response;
    }

    #Vérifie dans la BDD si l'utilisateur existe et si le mot de passe correspond à celui de l'utilisateur dans la BDD
    function isValidUser($username, $password) {
        $dbPassword =  getPasswordByName($username)[0];
        if ($username!=null && $password == $dbPassword) {
            return true;
        } else {
            return false;
        }        
    }

?>