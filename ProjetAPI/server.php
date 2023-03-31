<?php

    include('serverFunctions.php');
    include('jwt_utils.php');
    
    header("Content-Type:application/json");

    $http_method = $_SERVER['REQUEST_METHOD'];

    $jwt = get_bearer_token();

    if (isset($jwt) ) {
        $payload = base64_decode(split_jwt($jwt)['payload']);
        $login = json_decode($payload, true)['username'];
        $exp = json_decode($payload, true)['exp'];
        $role = json_decode($payload, true)['role'];
    } else {
        $role = "non authentifie";
        $exp = time() + 3600;
        $login = "non authentifie";
    }
    
    switch ($http_method){
        case "GET" :
            if(is_jwt_valid($jwt)) {
                if (isset($_GET['login'])) {
                    $username = $_GET['login'];
                    $matchingData = getWithLikesById($username);
                } else {
                    $matchingData = getWithLikesById();
                }
                if (isset($_GET['id_article'])) {
                    $id = $_GET['id_article'];
                    $matchingData = (getLikeById($id)-getDislikeById($id));
                }
            } else {
                if (isset($_GET['login'])) {
                    $username = $_GET['login'];
                    $matchingData = getById($username);
                } else {
                    $matchingData = getById();
                }
            }
            deliver_response(201, "La requête a bien été traitée par le server.", $matchingData);
            break;
        case "DELETE" :            
            $id = $_GET['id_article'];            
            if (!isset($id)){
                deliver_response(405, "L'article n'existe pas.", null);
            }
            $author = getAuthor($id);
            if ( ($login === $author || $role == "Moderator") ) {
                if (is_jwt_valid($jwt) ) {
                    deleteId($id);
                    deliver_response(200, "L'article a bien ete supprime.", null);
                } else {
                    deliver_response(498, "Le token est invalide.", null);
                }
            } else {
                deliver_response(401, "Autorisation necessaire.", null);
            }
            break;
        case "POST" :
            $request_body = file_get_contents('php://input');
            $data = json_decode($request_body, true);
            $contenu = $data['contenu'];
            if (is_jwt_valid($jwt)){
                insert($contenu, $login);                  
                deliver_response(204, "Requête insert traitée avec succès mais pas d’information à renvoyer.", null);
            } else {
                deliver_response(498, "Jeton invalide", null);
            }
            break;
        case "PUT" :
            $request_body = file_get_contents('php://input');
            $data = json_decode($request_body, true);
            $id = $data['id_article'];
            if (!isset($id)){
                deliver_response(405, "L'article n'existe pas.", null);
            }
            if (isset($data['contenu'])) {
                $contenu = $data['contenu'];
                $author = getAuthor($id);
                if (($login === $author )) {
                    if ( is_jwt_valid($jwt) ) {
                        update($contenu, $id);                  
                        deliver_response(204, "Requête update traitée avec succès mais pas d’information à renvoyer.", null);
                    } else {
                        deliver_response(498, "Jeton invalide.", null);
                    }
                } else {
                    deliver_response(401, "Autorisation necessaire.", null);
                }
            }
            if (isset($data['vote'])) {
                $vote = $data['vote'];  
                $author = getAuthor($id);
                if (isNewVote($login, $id)) {
                    if ( is_jwt_valid($jwt) ) {
                        vote($login, $id, $vote);                  
                        deliver_response(204, "Requête update traitée avec succès mais pas d’information à renvoyer.", null);
                    } else {
                        deliver_response(498, "Jeton invalide", null);
                    }
                } else {
                    if ( is_jwt_valid($jwt) ) {
                        insertNewVote($login, $id, $vote);                
                        deliver_response(204, "Requête update traitée avec succès mais pas d’information à renvoyer.", null);
                    } else {
                        deliver_response(498, "Jeton invalide", null);
                    }
                }                
            }
            break;
        default :
            deliver_response(405, "Invalid method " . $http_method . ".", NULL);
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

?>