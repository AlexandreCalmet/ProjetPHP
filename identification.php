<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" id="nameLog" name="username" required minlength="3" size="50">
    <input type="text" id="namePass" name="password" required minlength="3" size="50">
    <input type="submit" id="envoyer" name="envoyer" value="connexion">
</form>

<?php

if(!empty($_POST['username'])&&!empty($_POST['password'])) {
    if(!isset($_GET['connexion'])) {
        echo(identification($_POST['username'], $_POST['password']));
    }
}

function identification($username, $password) {
    #$data =  (array) json_decode(file_get_contents('php://input'), TRUE); 
    $data = array("username" => $username, "password"=>$password); 
    $data_string = json_encode($data);
    $result = file_get_contents(
        'http://localhost/r4C01/TP2_REST/authentification.php',
    null, 
        stream_context_create(array(
            'http' => array('method' => 'POST',
            'content' => $data_string,
            'header' => array('Content-Type: application/json'."\r\n"
                    .'Content-Length: '.strlen($data_string)."\r\n"))))
    );
    return $result;
}
?>