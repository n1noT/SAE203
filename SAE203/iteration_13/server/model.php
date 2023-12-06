<?php

/*
    Dans ce fichier, on écrira des fonctions "outils" qui réaliseront chacune des
    opérations spécifiques sur la base de données.
    C'est depuis le fichier script.php qu'on appelera telle ou telle fonction "outil"
    en fonction de la requête HTTP à traiter. C'est pour cela que le fichier model.php
    est inclus dans le fichier script.php : pour pouvoir appeler les fonctions "outils".
*/


function getMovies($c){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    if($c == 0){
        $answer = $cnx->query("select id_movie, titre, realisateur, annee, url_img, mis_en_avant from Movies"); 
    }
    else{
        $answer = $cnx->query("select id_movie, titre, realisateur, annee, url_img from Movies WHERE id_category= $c"); 
    }
    $res = $answer->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function getMovie($id){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("select titre, realisateur, annee, url_vid from Movies where id_movie=$id"); 
    $res = $answer->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function lookForMovies($t){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("select id_movie, titre, realisateur, annee, url_img, mis_en_avant from Movies where titre LIKE '%$t%'"); 
    $res = $answer->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function getId($id){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("select id_movie, titre, realisateur, annee, url_img, url_vid, id_category from Movies where id_movie=$id"); 
    $res = $answer->fetchAll(PDO::FETCH_OBJ);
    return $res; 
}


function getCategory(){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("select id_category, nom_category from Category"); 
    $res = $answer->fetchAll(PDO::FETCH_OBJ);
    return $res; 
}

// function getOneCategory($c){
//     $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
//     $answer = $cnx->query("select nom_category from Category where id_category= $c"); 
//     $res = $answer->fetchAll(PDO::FETCH_OBJ);
//     return $res; 
// }

function insertCategory($nom){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("insert into Category set nom_category='$nom' "); 
    $res = $answer->rowCount();
    return $res;
}

function removeCategory($idc){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("delete from Category where id_category=$idc"); 
    $res = $answer->rowCount();
    return $res;
}

function insertMovie($t, $r, $da, $ui, $uv, $ca, $mav){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("insert into Movies set titre='$t', realisateur='$r', annee= $da, url_img='$ui', url_vid='$uv',id_category=$ca, mis_en_avant=$mav"); 
    $res = $answer->rowCount();
    return $res;
}

function updateMovie($t, $r, $da, $ui, $uv, $id, $ca, $mav){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("update Movies set titre='$t', realisateur='$r', annee= $da, url_img='$ui', url_vid='$uv',id_category=$ca, mis_en_avant=$mav where id_movie=$id "); 
    $res = $answer->rowCount();
    return $res;
}

function insertUser($name){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("insert into UserProfile set nom_profil='$name'"); 
    $res = $answer->rowCount();
    return $res;
}

function getUser(){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("select id_profil, nom_profil from UserProfile"); 
    $res = $answer->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function removeUser($idp){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("delete from UserProfile where id_profil=$idp; delete from Playlist where id_profil=$idp"); 
    $res = $answer->rowCount();
    return $res;
}

function updatePlaylist($idm, $idp){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("replace into Playlist set id_movie=$idm, id_profil=$idp"); 
    $res = $answer->rowCount();
    return $res;
}

function getPlaylist($idp){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("select Movies.id_movie, titre, realisateur, annee, url_img from Movies INNER JOIN Playlist ON Movies.id_movie = Playlist.id_movie INNER JOIN UserProfile ON UserProfile.id_profil = Playlist.id_profil WHERE UserProfile.id_profil=$idp;"); 
    $res = $answer->fetchAll(PDO::FETCH_OBJ);
    return $res;
}

function removePlaylist($idm, $idp){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("delete from Playlist where id_movie=$idm and id_profil=$idp"); 
    $res = $answer->rowCount();
    return $res;
}


function deleteMovie($idm){
    $cnx = new PDO("mysql:host=localhost;dbname=********", "********", "********");
    $answer = $cnx->query("DELETE FROM Movies WHERE id_movie=$idm"); 
    $res = $answer->rowCount();
    return $res;
}
