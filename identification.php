<!DOCTYPE html>
<html>
  <head>
    <title>Number Maker</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap');
        * {
            box-sizing: border-box;
        }
        body {
            font-family: "DM Sans", sans-serif;
            line-height: 1.5;
            background-color: #f1f3fb;
            padding: 0 2rem;
        }
        img {
            max-width: 100%;
            display: block;
        }
        .card {
            margin: 2rem auto;
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 425px;
            background-color: #FFF;
            border-radius: 10px;
            box-shadow: 0 10px 20px 0 rgba(#999, .25);
            padding: .75rem;
        }
        .card-image {
            border-radius: 8px;
            overflow: hidden;
            padding-bottom: 65%;
            background-image: url('https://images.pexels.com/photos/796602/pexels-photo-796602.jpeg?auto=compress&cs=tinysrgb&w=1600');
            background-repeat: no-repeat;
            background-size: 100%;
            background-position: 0 5%;
            position: relative;
        }
        .card-heading {
            position: absolute;
            left: 10%;
            top: -3%;
            right: 10%;
            font-size: 1.75rem;
            font-weight: 700;
            color: #72828d;
            line-height: 1.222;
        }
        .card-form {
            padding: 2rem 1rem 0;
        }
        .input {
            display: flex;
            flex-direction: column-reverse;
            position: relative;
            padding-top: 1.5rem;
            &+.input {
                margin-top: 1.5rem;
            }
        }
        .input-label {
            color: #8597a3;
            position: absolute;
            top: 0rem;
            transition: .25s ease;
        }
        .input-field {
            border: 0;
            z-index: 1;
            background-color: transparent;
            border-bottom: 2px solid #eee; 
            font: inherit;
            font-size: 1.125rem;
            padding: .25rem 0;
        }
        .action {
            margin-top: 2rem;
        }
        .action-button {
            font: inherit;
            font-size: 1.25rem;
            padding: 1em;
            width: 100%;
            font-weight: 500;
            background-color: #6658d3;
            border-radius: 6px;
            color: #FFF;
            border: 0;
        }
        .action-button:hover {
            background-color: #76b5c5;
            transition: 0.5s;
            transform:scale(1.03,1.03);
        }
        .action-button:active {
            background-color: grey;
            transform:scale(0.97,0.97);
        }

    </style>
  </head>

<script type="text/JavaScript">
    function togglePassword() {
        var x = document.getElementById("namePass");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }    

</script>

<?php

    if( !empty($_POST['username']) && !empty($_POST['password'] )) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (isset($_POST['Connect'])) {
            $data = identification( $username, $password );
            $json = json_decode($data, true);
            switch ($json['status']){
                case 200 :
                $jwt = $json['data'];            
                if (!isset($_SESSION)) {
                    session_start();
                    $_SESSION['jwt'] = $jwt;
                }
                echo($_SESSION['jwt'] );
                header('Location: http://localhost/r4C01/ProjetREST/ProjetPHP/clientIndex.php');
                break;            
            case 401 :
                echo("User " . $data['username'] ." invalid.");
                break;
            default:
                echo("ERROR");
                break;
            }
        }
        if (isset($_POST['SignIn'])) {
            creationCompte( $username, $password );
            $data = identification( $username, $password );
            $json = json_decode($data, true);
            $jwt = $json['data'];            
            if (!isset($_SESSION)) {
                session_start();
                $_SESSION["jwt"] = $jwt;
            }
            echo($_SESSION["jwt"] );
            header('Location: http://localhost/r4C01/ProjetREST/ProjetPHP/clientIndex.php');
        }
    }

    function identification($username, $password) {
        $data = array("username" => $username, "password"=>$password); 
        $data_string = json_encode($data);
        $result = file_get_contents(
            'http://localhost/r4C01/ProjetREST/ProjetPHP/authentification.php',
        null, 
            stream_context_create(array(
                'http' => array('method' => 'POST',
                'content' => $data_string,
                'header' => array('Content-Type: application/json'."\r\n"
                        .'Content-Length: '.strlen($data_string)."\r\n"))))
        );
        return $result;
    }

    function creationCompte($username, $password) {
        $data = array("username" => $username, "password"=>$password); 
        $data_string = json_encode($data);
        $result = file_get_contents(
            'http://localhost/r4C01/ProjetREST/ProjetPHP/creationCompte.php',
        null, 
            stream_context_create(array(
                'http' => array('method' => 'POST',
                'content' => $data_string,
                'header' => array('Content-Type: application/json'."\r\n"
                        .'Content-Length: '.strlen($data_string)."\r\n"))))
        );
        return ;
    }
?>

<div class="container">
	<div class="card">
		<div  class="card-image">
			<h2 class="card-heading">
				Welcome </br>
				<small>Share your messages</small>
			</h2>
		</div>
            <form class="card-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="input">
                    <label  for="name"class="input-label">Login :</label>
                    <input type="text" class="input-field" id="nameLog"  placeholder="Alexandre" name="username" minlength="3" size="25" required>
                </div>
                <div class="input">
                    <label class="input-label"></label>
                    <input id="namePass" type="password" class="input-field"  placeholder="Password" name="password" minlength="3" size="25" required>
                </div>
                <div class="action">
                    <input class="action-button" value="Connect" type="submit" name="Connect" >
                    <input type="checkbox" onclick="togglePassword()">Show password</br>   
                    <input class="action-button" value="Sign in" type="submit" name="SignIn">                 
                </div>
            </form>    
</div>
