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

    function getById($username=null) {
        $linkpdo = connection();
        if (isset($username)) {
            $query = 'SELECT * FROM article WHERE login =' . '\'' . $username . '\'' ;
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

    function getWithLikesById($username=null) {
        $linkpdo = connection(); 
        if (isset($username)) {
                $query = 'SELECT article.publication, article.contenu,  
                SUM(CASE WHEN consulte.vote = 1 THEN 1 ELSE 0 END) AS up,
                SUM(CASE WHEN consulte.vote = 0 THEN 1 ELSE 0 END) AS down,
                (SUM(CASE WHEN consulte.vote = 1 THEN 1 ELSE 0 END) - SUM(CASE WHEN consulte.vote = 0 THEN 1 ELSE 0 END)) AS total,
                article.login    
                FROM article
                INNER JOIN consulte ON article.id_article = consulte.id_article
                WHERE article.login = ' . '\'' . $username . '\'
                GROUP BY article.id_article'; 
        } else {
            $query = 'SELECT article.publication, article.contenu,  
                SUM(CASE WHEN consulte.vote = 1 THEN 1 ELSE 0 END) AS up,
                SUM(CASE WHEN consulte.vote = 0 THEN 1 ELSE 0 END) AS down,
                (SUM(CASE WHEN consulte.vote = 1 THEN 1 ELSE 0 END) - SUM(CASE WHEN consulte.vote = 0 THEN 1 ELSE 0 END)) AS total,
                article.login    
                FROM article
                INNER JOIN consulte ON article.id_article = consulte.id_article
                GROUP BY article.id_article'; 
        }
        $res = $linkpdo->prepare($query); 
        if(!$res){
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }
        $res->execute();           
        return $res->fetchAll();
    }

    function getLikeById($id_article) {
        $linkpdo = connection();
            $query = 'SELECT COUNT(*) FROM consulte WHERE id_article = ' . $id_article . ' AND vote = 1 ';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            foreach($res as $values) {
                return $values[0];
            }
    }

    function getDislikeById($id_article) {
        $linkpdo = connection();
            $query = 'SELECT COUNT(*) FROM consulte WHERE id_article = ' . $id_article . ' AND vote = 0 ';
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute();
            foreach($res as $values) {
                return $values[0];
            }
    }

    function insert($contenu, $utilisateur) {
        $linkpdo = connection();     
        $query = 'INSERT INTO article (contenu, login) VALUES (:contenu, :login)';
        $res = $linkpdo->prepare($query);
        if(!$res) {
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }
        $resExec=$res->execute(array("contenu" => $contenu, "utilisateur" => $utilisateur ));
        if(!$resExec)
            die('Erreur Exécution Requête : ' . $e->getMessage());
        return $res;
    }

    function deleteId($id_article) {
        $linkpdo = connection();
        $query = 'DELETE FROM article WHERE id_article =' . $id_article . ';';
        $res = $linkpdo->prepare($query); 
        if(!$res){
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }        
        $res->execute();
        return $res->fetchAll();
    }

    function getAuthor($id_article) {
            $linkpdo = connection();
            $query = 'SELECT login FROM article WHERE id_article =' .  $id_article  ;
            $res = $linkpdo->prepare($query); 
            if(!$res){
                die('Erreur Préparation Requête : ' . $e->getMessage());
            }
            $res->execute(); 
          
            foreach($res as $values) {
                return $values['login'];
            }            
    }

    function update($contenu, $id_article) {
        $linkpdo = connection();     
        $query = 'UPDATE article SET contenu = :contenu WHERE id_article = :id' ;
        $res = $linkpdo->prepare($query);
        if(!$res) {
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }
        $resExec=$res->execute(array("contenu" => $contenu, "id_article" => $id_article ));
        if(!$resExec)
            die('Erreur Exécution Requête : ' . $e->getMessage());
        return $res;
    }

    function vote($utilisateur, $id_article, $vote) {
        $linkpdo = connection();     
        $query = 'UPDATE consulte SET vote = :vote WHERE id_article = :id_article AND login= :utilisateur';
        $res = $linkpdo->prepare($query);
        if(!$res) {
            die('Erreur Préparation Requête : ' . $e->getMessage());
        }
        $resExec=$res->execute(array( "utilisateur" => $utilisateur, "id_article" => $id_article, "vote" => $vote ));
        if(!$resExec)
            die('Erreur Exécution Requête : ' . $e->getMessage());
        return $res;
    }

    function isNewVote($utilisateur, $id_article) {
        $linkpdo = connection();  
        $query = 'SELECT vote FROM consulte WHERE id_article = ' . $id_article . ' AND login = \'' .  $utilisateur . '\'';
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