<?php

function get($x=null) {
    if (isset($x)) {
        $result = file_get_contents('http://localhost/R4C01/ProjetREST/ProjetPHP/server.php?login='.$x,
        false, stream_context_create(array('http' => array('method' => 'GET'))));
        return $result;
    } else {
        $result = file_get_contents('http://localhost/R4C01/ProjetREST/ProjetPHP/server.php',
        false, stream_context_create(array('http' => array('method' => 'GET'))));
        return $result;
    }
}


function insertArticle($string, $login, $jwt) {
    $data = array("contenu" => $string, "login" => $login, "jwt" => $jwt); 
    $data_string = json_encode($data);
    file_get_contents(
        'http://localhost/R4C01/ProjetREST/ProjetPHP/server.php',
    null, 
        stream_context_create(array(
            'http' => array('method' => 'POST',
            'content' => $data_string,
            'header' => array('Content-Type: application/json'."\r\n"
                    .'Content-Length: '.strlen($data_string)."\r\n"))))
    );
}

function delete($x) { 
    $result = file_get_contents('http://localhost/R4C01/ProjetREST/ProjetPHP/server.php?id_article='.$x,
    false, stream_context_create(array('http' => array('method' => 'DELETE'))));
    header("Location: clientIndex.php");
}





?>