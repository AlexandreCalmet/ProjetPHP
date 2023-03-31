# ProjetPHP
# Requêtes postman de la collection “ProjetAPI” :

$rouge : variables

vert : valeurs prédéfinies

## GET (x5):

getPublicationsAsNonAuthentified: Retourne toutes les publications.
Codes : 201 si réussie, 405 si méthode non reconnue.
URL : http://localhost/ProjetAPI/server.php 

getSomeonesPublicationsAsNonAuthentified(param=(login=$login)): Retourne toutes les publications postées par l’auteur associé à son $login.
Codes : 201 si réussie, 405 si méthode non reconnue.
URL : http://localhost/ProjetAPI/server.php?login=$login 

Token valide nécessaire :
getPublications : Retourne toutes les publications avec leurs likes, dislikes et total (likes - dislikes)
Codes : 201 si réussie, 405 si méthode non reconnue.
URL : http://localhost/ProjetAPI/server.php

getSomeonesPublications(param=(login=$login)): Retourne toutes les publications avec leurs likes, dislikes et total (likes-dislikes) 
postées par l’auteur associé à son $login.
Codes : 201 si réussie, 405 si méthode non reconnue.
URL : http://localhost/ProjetAPI/server.php?login=$login 

getPublicationsLikes(param=(id_article=$id_article)): Retourne le nombre de likes d’une publication associée à l’identifiant $id_article.
Codes : 201 si réussie, 405 si méthode non reconnue.
URL : http://localhost/ProjetAPI/server.php?id_article=$id_article 

## POST (x3):

createLogin(body={“username”:$login, “password:”$password}): Créer un utilisateur dont le $login et $password sont ceux que l’utilisateur a transmis et lui attribue le rôle “Publisher”.
Codes : 204 si réussie,403 si le login utilisateur existe déjà, 405 si méthode non reconnue.
URL : http://localhost/ProjetAPI/creationCompte.php

authentification(body={“username”:$login, “password:”$password}): Génère et retourne un token associé au $login comprenant son rôle, son $login, d’une validité de 1 heure.
Codes : 201 si réussie, 404 si le login utilisateur n’existe pas, 405 si méthode non reconnue.

URL : http://localhost/ProjetAPI/authentification.php
IMPORTANT : Pour obtenir le rôle “Moderator” : authentification(body={“username”:AlexM, “password:”6559})

### Token valide nécessaire :
createPublication(body={“contenu”:$contenu}): Créer une publication dont l’identifiant $id_article est un nombre auto-incrémenté, $publication est la date/heure au moment de sa publication, $contenu est celui enregistré par l’utilisateur, $login est l’auteur de cette nouvelle publication.
Codes : 201 si réussie, 405 si méthode non reconnue, 498 si le jeton est invalide.
URL : http://localhost/ProjetAPI/server.php


## DELETE (x1):

### Token valide nécessaire :
deletePublication(param=(id_article=$id_article)): Supprime la publication associée à l’identifiant $id_article.
Codes : 204 si réussie, 401 si non autorisé, 405 si méthode non reconnue, 498 si jeton invalide.
URL : http://localhost/ProjetAPI/server.php?id_article=$id_article

## PUT (x3):

### Token valide nécessaire :
updatePublicationContenu(body={“contenu”:$contenu, “id_article”:$id_article}): Change le contenu de la publication associée à l’identifiant $id_article par le nouveau contenu $contenu.
Codes : 204 si réussie, 401 si non autorisé, 405 si méthode non reconnue, 498 si jeton invalide.
URL : http://localhost/ProjetAPI/server.php

upVote(body={“id_article”:$id_article, “vote”:1}): Ajoute un like à la publication associée à l’identifiant $id_article.
Codes : 204 si réussie, 401 si non autorisé, 405 si méthode non reconnue, 498 si jeton invalide.
URL : http://localhost/ProjetAPI/server.php

downVote(body={“id_article”:$id_article, “vote”:0}): Ajoute un dislike à la publication associée à l’identifiant $id_article.
Codes : 204 si réussie, 401 si non autorisé, 405 si méthode non reconnue, 498 si jeton invalide.
URL : http://localhost/ProjetAPI/server.php
