<?php

    function connection() {
            $login = 'root';
            $mdp = null;
            $server = 'localhost';
            $db = 'projetapi';
            try {
                $linkpdo = new PDO("mysql:host=$server;dbname=$db;charset=UTF8", $login, $mdp);
            }
                catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
            return $linkpdo;
    }

    function getNameByName($username) {
        $linkpdo = connection();
            $query = 'SELECT login FROM utilisateur WHERE login = \'' . $username . '\'';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            return $res->fetchAll();
    }

    function getPasswordByName($username) {
        $linkpdo = connection();
            $query = 'SELECT password FROM utilisateur WHERE login = \'' . $username . '\'';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            $result = $res->fetchAll(PDO::FETCH_COLUMN, 0);
            return $result;

    }
        #Vérifie dans la BDD si l'utilisateur existe et si le mot de passe correspond à celui de l'utilisateur dans la BDD
        function isValidUser($username, $password) {
            $dbPassword =  getPasswordByName($username)[0];
            if (($username==getNameByName($username)) && ($password == $dbPassword)) {
                return true;
            } else {
                return false;
            }        
        }
    
        #Récupére le rôle en fonction du login entré
        function getRole($utilisateur) {
            $linkpdo = connection();
            $query = 'SELECT role FROM utilisateur WHERE login = \'' . $utilisateur . '\'' ;
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            $result = $res->fetchAll();
            return $result[0];
        }    

        function insert($username, $password) {
            $linkpdo = connection();
            $query = 'INSERT INTO utilisateur(login, password, role) VALUES (:username, :password, :Publisher)';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $resExec=$res->execute(array("username" => $username, "password" => hash_hmac('SHA256', $password, true), "Publisher" => 'Publisher'));
            if(!$resExec)
                die('Erreur Exécution Requête : ');
            return $res;
        }

?>