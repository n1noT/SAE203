/*
  Placez dans ce fichier le code Javascript nécessaire au fonctionnement de l'application vitrine
*/
let V = [];

let requestMovies = async function(){
  let idc = document.querySelector('#categorie');
  let c = idc.value ;
    let response = await fetch("../server/script.php?action=getmovies&idcategory="+ c);
    let data = await response.json();
    if (data.length>0){
      if (c == 0){
        renderActu('.actu-template', data, '.slideshow-container');
        // renderTemplate('.title-template', [{nom_category: "Tous les films"}], '.main__title');
        renderTemplate('.card-template', data, '.movies');
        showSlides(slideIndex);
        hideArrow('show');
      }
      else{
        renderActu('.actu-template', [""], '.slideshow-container');
        // renderTemplate('.title-template', data, '.main__title');
        renderTemplate('.card-template', data, '.movies');
        hideArrow('hide');
        
      }
      
    }
    else{
      let response = await fetch("../server/script.php?action=getmovies&idcategory="+ 0);
      let all = await response.json();
      if (all.length>0){
        renderActu('.actu-template', all, '.slideshow-container');
        // renderTemplate('.title-template', data, '.main__title');
        renderTemplate('.card-template', [{annee:"", id_movie: 0, titre: 'Aucun film de cette catégorie', realisateur: "", url_img: "none.jpg"}], '.movies');
        showSlides(slideIndex);
        hideArrow('show');
      }
      else{ 
      } 
    }
};

let lookForMovies= async function(){
  let titre = document.querySelector('#lookfortitle');
  let t = titre.value ;
  
  let response = await fetch("../server/script.php?action=lookformovies&lookfortitle="+t);
  let data = await response.json();
  if (data.length>0){
    renderActu('.actu-template', [""], '.slideshow-container');
    // renderTemplate('.title-template', [{nom_category: t}], '.main__title');
    renderTemplate('.card-template', data, '.movies');
    hideArrow('hide');
  }
  else{
    
  }

};

let requestAll= async function(){
    let response = await fetch("../server/script.php?action=getmovies&idcategory="+ 0);
    let data = await response.json();
    if (data.length>0){
      renderActu('.actu-template', data, '.slideshow-container');
      // renderTemplate('.title-template', [{nom_category: "Tous les films"}], '.main__title');
      renderTemplate('.card-template', data, '.movies');
      showSlides(slideIndex);
      hideArrow('show');
    }
    else{
      
    }

};

let requestPlaylist= async function(){
  let idp = document.querySelector('#user');
  let playlist = idp.value ;
  let response = await fetch("../server/script.php?action=getplaylist&idprofile="+ playlist);
  let data = await response.json();
  if (data.length>0){
    renderActu('.actu-template', [""], '.slideshow-container');
    // renderTemplate('.title-template', [{nom_category: "Ma playlist"}], '.main__title');
    renderTemplate('.playlist-template', data, '.movies');
    hideArrow('hide');
  }
  else{
    
  }
  
};

let addPlaylist= async function(movie){
  let idp = document.querySelector('#user');
  let profile = idp.value ;
 
  let response = await fetch("../server/script.php?action=addplaylist&idmovie="+ movie + "&idprofile="+ profile );
  
};

let removeFromPlaylist= async function(movie){
  let idp = document.querySelector('#user');
  let profile = idp.value ;
  
let response = await fetch("../server/script.php?action=removefromplaylist&idmovie="+ movie + "&idprofile="+ profile );
let data = await response.json();
    
if (data > 0){
  requestPlaylist();

  let response = await fetch("../server/script.php?action=getplaylist&idprofile="+ profile);
  let data = await response.json();

  if (data == 0){
    requestMovies();  
  }
}

};

let requestMovie = async function(idm){

  let response = await fetch("../server/script.php?action=getmovie&idmovie="+ idm);
  let data = await response.json();
  if (data.length>0){
    renderActu('.actu-template', [""], '.slideshow-container');
    // renderTemplate('.title-template', [{nom_category: ""}], '.main__title');
    renderTemplate('.trailer-template', data, '.movies');
    hideArrow('hide');
  }
  else{
    
  }
}

let getCategories = async function(){

  let response = await fetch("../server/script.php?action=getcategory");
  let data = await response.json();
  if (data.length>0){
    renderOption('#category-template', data, '#categorie');
  }
  else{
    
  }
}

getCategories();

let getUser = async function(){

  let response = await fetch("../server/script.php?action=getprofiles");
  let data = await response.json();
  if (data.length>0){
    renderTemplate('#user-template', data, '#user');
  }
  else{
    
  }
}

getUser();

let hideArrow = function (n){
  let i;
  let arrows = document.getElementsByClassName("arrow");
  if(n == 'hide'){
    for (i = 0; i < arrows.length; i++) {
      arrows[i].style.display = "none";
    }
  }
  else{
    if(n == 'show'){
      for (i = 0; i < arrows.length; i++) {
        arrows[i].style.display = "block";
      }
    }
  }
  
}

//
function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");

  if (n > slides.length) {
    slideIndex = 1;
  }
  if (n < 1) {
    slideIndex = slides.length;
  }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[slideIndex - 1].style.display = "block";

}

let slideIndex = 1;


function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}





/* Fonction renderTemplate (c'est cadeau)

   Sous certaines conditions, cette fonction est capable de formater n'importe quel
   template avec n'importe quelles données. Une sorte de rendu de template "universelle".
   A condition que :
    - les données sont structurées dans des objets avec des propriétés qui sont des chaines ou des nombres
      Si vous avez des propriétés qui sont des tableaux ou des sous-objets ça ne marchera pas.
    - les tags dans le template à formater sont les noms des propriétés des objets encadrées de {{ et }}

    Rôle des paramètre : 
    tpl : le selecteur CSS du template à utiliser
    data : un tableau d'objets (chaque objet sera rendu avec le template tpl)
    where : le selecteur CSS de l'élément "conteneur" ou les templates formatés doivent apparaître dans la page

    Note : data est forcément un tableau d'objets. Si vous n'avez qu'un seul objet pour formater, data 
    devra quand même être un tableau avec ce seul objet dedans.

*/

let renderTemplate = function(tpl, data, where)
{
    let template = document.querySelector(tpl);
    let res = "";

    for(let elem of data)
    {
      let html = template.innerHTML;
      for(let prop in elem)
      {
        html = html.replaceAll("{{"+prop+"}}", elem[prop]);
      }
      res += html;
    }
    document.querySelector(where).innerHTML = res;
}

let renderActu = function(tpl, data, where)
{
    let template = document.querySelector(tpl);
    let res = "";
    for(let i = 0; i<data.length; i++ )
    {
      let html = template.innerHTML;

      if(data[i].mis_en_avant == 0){
        for(let prop in data[i])
        {
          html = html.replaceAll("{{"+prop+"}}", data[i][prop]);
        }
        res += html;
      }
      else{

      }
      
    }
    document.querySelector(where).innerHTML = res;
}


let renderOption = function(tpl, data, where)
{
    let template = document.querySelector(tpl);
    let res = "";

    let opt = template.innerHTML;
    opt = opt.replaceAll("{{id_category}}", '0');
    opt = opt.replaceAll("{{nom_category}}", 'All');
    
    res+= opt

    for(let elem of data)
    {
      let html = template.innerHTML;
      for(let prop in elem)
      {
        html = html.replaceAll("{{"+prop+"}}", elem[prop]);
      }
      res += html;
    }
    document.querySelector(where).innerHTML = res;
}

requestAll();


