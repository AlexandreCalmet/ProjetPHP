<?php

    function connection() {
            $login = 'root';
            $mdp = null;
            $server = 'localhost';
            $db = 'authentification';
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

    function getById($x=null) {
        $linkpdo = connection();
        if (isset($x)) {
            $query = 'SELECT * FROM utilisateur WHERE id_user = ' . $x ;
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

?>