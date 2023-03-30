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

    function getById($x=null) {
        $linkpdo = connection();
        if (isset($x)) {
            $query = 'SELECT * FROM article WHERE login =' . '\'' . $x . '\'' ;
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            return $res->fetchAll();
        } else {
            $query = 'SELECT * FROM article';
            $res = $linkpdo->prepare($query);
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            return $res->fetchAll();
        }
    }

    function getLikeById($id) {
        $linkpdo = connection();
            $query = 'SELECT COUNT(*) FROM consulte WHERE id_article = ' . $id . ' AND vote = 1 ';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            foreach($res as $values) {
                return $values[0];
            }
    }

    function getDislikeById($id) {
        $linkpdo = connection();
            $query = 'SELECT COUNT(*) FROM consulte WHERE id_article = ' . $id . ' AND vote = 0 ';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            foreach($res as $values) {
                return $values[0];
            }
    }

    function insert($contenu, $login) {
        $linkpdo = connection();     
        $query = 'INSERT INTO article (contenu, login) VALUES (:contenu, :login)';
        $res = $linkpdo->prepare($query);
        if(!$res) {
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }
        $resExec=$res->execute(array("contenu" => $contenu, "login" => $login ));
        if(!$resExec)
            die('Erreur Exécution Requête : ' . $e->getMessage());
        return $res;
    }

    function deleteId($x) {
        $linkpdo = connection();
        $query = 'DELETE FROM article WHERE id_article =' . $x . ';';
        $res = $linkpdo->prepare($query); 
        if(!$res){
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }        
        $res->execute();
        return $res->fetchAll();
    }

    function getAuthor($id) {
            $linkpdo = connection();
            $query = 'SELECT login FROM article WHERE id_article =' .  $id  ;
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute(); 
          
            foreach($res as $values) {
                return $values['login'];
            }            
    }

    function update($contenu, $id) {
        $linkpdo = connection();     
        $query = 'UPDATE article SET contenu = :contenu WHERE id_article = :id' ;
        $res = $linkpdo->prepare($query);
        if(!$res) {
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }
        $resExec=$res->execute(array("contenu" => $contenu, "id" => $id ));
        if(!$resExec)
            die('Erreur Exécution Requête : ' . $e->getMessage());
        return $res;
    }

    function vote($login, $id_article, $vote) {
        $linkpdo = connection();     
        $query = 'UPDATE consulte SET vote = :vote WHERE id_article = :id_article AND login= :login';
        $res = $linkpdo->prepare($query);
        if(!$res) {
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }
        $resExec=$res->execute(array( "login" => $login, "id_article" => $id_article, "vote" => $vote ));
        if(!$resExec)
            die('Erreur Exécution Requête : ' . $e->getMessage());
        return $res;
    }

    function isNewVote($login, $id_article) {
        $linkpdo = connection();  
        $query = 'SELECT vote FROM consulte WHERE id_article = ' . $id_article . ' AND login = \'' .  $login . '\'';
        $res = $linkpdo->prepare($query);
        if(!$res) {
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }
        $res->execute();
        if(!$res)
            die('Erreur Exécution Requête : ' . $e->getMessage());
        foreach($res as $values) {
            $vote = $values['vote'];
        }
        if (isset($vote)) {
            return true;
        } else {
            return false;
        } 
    }

    function insertNewVote($login, $id_article, $vote) {
        $linkpdo = connection();     
        $query = 'INSERT INTO consulte (login, id_article, vote) VALUES ( \''. $login . '\', ' . $id_article . ', '.$vote . ') ';
        $res = $linkpdo->prepare($query);
        if(!$res) {
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }
        $res->execute();
        var_dump($res);
        return ;
    }
?>