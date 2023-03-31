<?php
    include('myAuthentificationFunctions.php');

    header("Content-Type:application/json");

    $http_method = $_SERVER['REQUEST_METHOD'];
    

    switch ($http_method){
        case "POST":
            $data = (array) json_decode(file_get_contents('php://input'), TRUE);  
                     
            if ( getNameByName($data['username']) == null ) {
                $username = $data['username'];
                $password = $data['password'];
                insert($username, $password);
                deliver_response(204, "Creation de compte reussie.", null );
            }  else {
                deliver_response(403, "Utilisateur " . $data['username'] ." existe deja.", NULL);
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

?>