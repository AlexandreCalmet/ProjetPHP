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

    function getByName($x=null) {
        $linkpdo = connection();
        if (isset($x)) {
            $query = 'SELECT * FROM utilisateur WHERE login = \'' . $x . '\'';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            return $res->fetchAll();
        } else {
            $query = 'SELECT * FROM utilisateur';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            return $res->fetchAll();
        }
    }

    function getNameByName($x) {
        $linkpdo = connection();
            $query = 'SELECT login FROM utilisateur WHERE login = \'' . $x . '\'';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            return $res->fetchAll();
    }

    function getPasswordByName($x) {
        $linkpdo = connection();
            $query = 'SELECT password FROM utilisateur WHERE login = \'' . $x . '\'';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            $result = $res->fetchAll(PDO::FETCH_COLUMN, 0);
            return $result;

    }
    
        #Récupére le rôle en fonction du login entré
        function setRole($username) {
            $linkpdo = connection();
            $query = 'SELECT role FROM utilisateur WHERE login = \'' . $username . '\'' ;
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            $result = $res->fetchAll();
            return $result[0];
        }    

        #Récupérer le login d'un nom d'utilisateur en entrée
        function doesntExist($username) {
            $linkpdo = connection();
            $query = 'SELECT login FROM utilisateur WHERE login = \'' . $username . '\'' ;
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            $result = $res->fetchAll();
            return $result;
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