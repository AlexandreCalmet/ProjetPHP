<?php

    include('jwt_utils.php');
    include('myAuthentificationFunctions.php');

    header("Content-Type:application/json");

    $http_method = $_SERVER['REQUEST_METHOD'];
    
    switch ($http_method){
        case "POST" :
            $data = (array) json_decode(file_get_contents('php://input'), TRUE);
            if (isValidUser($data['username'], $data['password'])) {
                $username = $data['username'];
                $headers = array('alg'=>'HS256', 'typ'=>'JWT');
                $role = setRole($username);
                $payload = array('username'=>$username, 'exp'=>(time()+60), 'role'=>$role);
                $jwt = generate_jwt($headers, $payload);

                #deliver_response(200, "Authentification réussie ", getPasswordByName($username));        
                deliver_data(getPasswordByName($username));        

            } else {
                deliver_response(405, "L'utilisateur n'est pas valide.", NULL);
            }
            break;
        default :
            deliver_response(405, "L'opération choisie n'est pas valide.", NULL);
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

    #Définit un rôle en fonction du login entré
    function setRole($username) {
        switch ($username){
            case "AlexM" :
                return "Moderator";
                break;
            case "AlexC" :
                return "Publisher";
                break;
            default:
                return "Authentifie";
                break;
            }
    }

    #Vérifie dans la BDD si l'utilisateur existe et si le mot de passe correspond à celui de l'utilisateur dans la BDD
    function isValidUser($username, $password) {
        return true;
    }

?>