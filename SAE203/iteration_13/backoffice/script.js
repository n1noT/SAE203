let V =[]
// recupère les infos d'un film et les place dans les cases
let getIdMovie = async function(){
    let titre = document.querySelector('#titre');
    let annee = document.querySelector('#annee');

    let real = document.querySelector('#real');
 
    let urlImage = document.querySelector('#url_img');
    let urlVideo = document.querySelector('#url_vid');

    let idmovielist = document.querySelector('#movies-list')
    let idml = idmovielist.value
   
    let response = await fetch("../server/script.php?action=getid&idmovieslist="+ idml);
    let data = await response.json();
        console.log(data);
  if(data.length == 1){
    titre.value = data[0].titre;
    annee.value = data[0].annee;
    real.value = data[0].realisateur;
    urlImage.value = data[0].url_img;
    urlVideo.value = data[0].url_vid;
  }
  else{
    titre.value = "";
    annee.value = "";
    real.value = "";
    urlImage.value = "";
    urlVideo.value = "";
  }
  
    
}


//recup les catégories et formate le template  
let getCategories = async function(){

    let response = await fetch("../server/script.php?action=getcategory");
    let data = await response.json();
    if (data.length>0){
      renderTemplate('#category-template', data, '#categorie');
      renderTemplate('#category-template', data, '#categorie2');
    }
    else{
      
    }
  }

let getMovies = async function(){
    let response = await fetch("../server/script.php?action=getinfomovies");
    let data = await response.json();
    if (data.length>0){
        renderMovies('#movie-template', data, '#movies-list');
        renderTemplate('#movie-template', data, '#movies-list-delete');
    }
    else{
        
    }
    
};


  //recup les profils et formate le template  
let getUser = async function(){

  let response = await fetch("../server/script.php?action=getprofiles");
  let data = await response.json();
  if (data.length>0){
      renderTemplate('#user-template', data, '#user');
  }
  else{
      
  }
}


let removeUser= async function(){
  let idp = document.querySelector('#user');
  let profile = idp.value ;
  
let response = await fetch("../server/script.php?action=deleteprofilet&idprofile="+ profile );
let data = await response.json();
    
if (data > 0){
  getUser();
}

};
  
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
  let renderMovies = function(tpl, data, where)
  {
      let template = document.querySelector(tpl);
      let res = "";
  
      let movies = template.innerHTML;
      movies = movies.replaceAll("{{id_movie}}", '0');
      movies = movies.replaceAll("{{titre}}", 'Nouveau film');
      movies = movies.replaceAll("{{annee}}", '');
      
      res+= movies
  
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

getUser();
getCategories();
getMovies();
