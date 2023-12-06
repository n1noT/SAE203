<?php

/*

  Dans ce fichier, on se charge de répondre aux requêtes HTTP émises par les applications
  front vitrine et back office.
  Le traitement de la requête, la réponse à retourner est fonction des valeurs paramètres présents
  dans la requête.

*/

require("model.php");

/* - BACKOFFICE - */


if (isset($_REQUEST['action']) && $_REQUEST['action']=='addmovie'){
  $titre = $_REQUEST['title'];
  $real = $_REQUEST['direction'];
  $annee = $_REQUEST['year'];
  $urlImg = $_REQUEST['urlImg'];
  $urlVid = $_REQUEST['urlVid'];
  $idmovie = $_REQUEST['idMovie'];
  $idCategory = $_REQUEST['idcategories'];
  $misenav = $_REQUEST['mav'];
  
  if($idmovie == 0){
      $ok = insertMovie($titre, $real, $annee, $urlImg, $urlVid, $idCategory, $misenav);
    if ($ok>0){
      echo "Les données relatives à $titre ont bien été ajoutées à la base de données. <a href='../backoffice/index.html'> Revenir au backoffice </a>";
    }
    else{
      echo "Un problème est survenu";
    }
  }
  else{
      $ok = updateMovie($titre, $real, $annee, $urlImg, $urlVid, $idmovie, $idCategory, $misenav);
    if ($ok>0){
      echo "Les données relatives à $titre ont bien été modifiées à la base de données. <a href='../backoffice/index.html'> Revenir au backoffice </a>";
    }
    else{
      echo "Un problème est survenu";
    }
  }

  exit(); // termine le script (ce qui est en dessous ne s'exécutera pas)
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='deletemovie'){
  $idm = $_REQUEST['ideletemovie'];
  
  $ok = deleteMovie($idm);
  if ($ok>0){
    echo "Le film a été supprimé. <a href='../backoffice/index.html'> Revenir au backoffice </a>";
  }
  else{
    echo "Un problème est survenu";
  }
  
  echo json_encode($ok);
  
  exit();
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='addcategory'){
  $nomC = $_REQUEST['nomcategory'];
  
  $ok = insertCategory($nomC);
  if ($ok>0){
    echo "La catégorie $nomC a bien été ajoutée à la base de données. <a href='../backoffice/index.html'> Revenir au backoffice </a>";
  }
  else{
    echo "Un problème est survenu";
  }
  exit(); // termine le script (ce qui est en dessous ne s'exécutera pas)
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='deletecategory'){
  $iddc = $_REQUEST['ideletecategorie'];
  
  $ok = removeCategory($iddc);
  if ($ok>0){
    echo "La catégorie a été supprimé. <a href='../backoffice/index.html'> Revenir au backoffice </a>";
  }
  else{
    echo "Un problème est survenu";
  }
  exit();
  echo json_encode($ok);
  
  exit();
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='addprofile'){
  $nom = $_REQUEST['name'];
  
  $ok = insertUser($nom);
  if ($ok>0){
    echo "L'utilisateur $nom a bien été ajouté à la base de données. <a href='../backoffice/index.html'> Revenir au backoffice </a>";
  }
  else{
    echo "Un problème est survenu";
  }
  exit(); // termine le script (ce qui est en dessous ne s'exécutera pas)
}

//obtenir les infos d'un film  dans le backoffice 
if (isset($_REQUEST['action']) && $_REQUEST['action']=='getid'){
$titres= $_REQUEST['idmovieslist'];
if($titres == 0){
  $reset = '';
  echo json_encode($reset);
}
else{
  $movies = getId($titres);
  echo json_encode($movies);
}

exit(); // termine le script (ce qui est en dessous ne s'exécutera pas)
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='deleteprofile'){
  $idp = $_REQUEST['idprofile'];
  
  $ok = removeUser($idp);
  if ($ok>0){
    echo "L'utilisateur a été supprimé. <a href='../backoffice/index.html'> Revenir au backoffice </a>";
  }
  else{
    echo "Un problème est survenu";
  }
  
  echo json_encode($ok);
  
  exit();
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='getinfomovies'){
  $all = getMovies(0);
  echo json_encode($all);
  exit(); // termine le script (ce qui est en dessous ne s'exécutera pas)
}

/* - VITRINE - */

if (isset($_REQUEST['action']) && $_REQUEST['action']=='getmovies'){
  $category = $_REQUEST['idcategory'];
  if ($category == 0){
    $movieS = getMovies(0);
  }
  else{
    $movieS = getMovies($category);
  }
  
  echo json_encode($movieS);
  exit(); // termine le script (ce qui est en dessous ne s'exécutera pas)
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='lookformovies'){
  $titre = $_REQUEST['lookfortitle'];
  $movies = lookForMovies($titre);
  echo json_encode($movies);
  exit(); // termine le script (ce qui est en dessous ne s'exécutera pas)
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='getmovie'){
  $idmovie = $_REQUEST['idmovie'];
  $movie = getMovie($idmovie);
  echo json_encode($movie);
  exit(); // termine le script (ce qui est en dessous ne s'exécutera pas)
}


if (isset($_REQUEST['action']) && $_REQUEST['action']=='getprofiles'){
  $profil = getUser();
  echo json_encode($profil);
  exit(); // termine le script (ce qui est en dessous ne s'exécutera pas)
}


//obtenir la liste des catégories 
if (isset($_REQUEST['action']) && $_REQUEST['action']=='getcategory'){
  $category = getCategory();
  echo json_encode($category);
  exit(); // termine le script (ce qui est en dessous ne s'exécutera pas)
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='addplaylist'){
  $idm = $_REQUEST['idmovie'];
  $idp = $_REQUEST['idprofile'];
  
  $ok = updatePlaylist($idm, $idp);
  exit();
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='getplaylist'){
  $idp = $_REQUEST['idprofile'];
  
  $playlist = getPlaylist($idp);
  echo json_encode($playlist);
  exit();
}

if (isset($_REQUEST['action']) && $_REQUEST['action']=='removefromplaylist'){
  $idm = $_REQUEST['idmovie'];
  $idp = $_REQUEST['idprofile'];
  
  $ok = removePlaylist($idm, $idp);
  echo json_encode($ok);
  
  exit();
}

/* 
    Si on atteint la fin du script sans avoir répondu à la requête, on
    répond un 404 (not found) par défaut
*/
http_response_code(404);

?>