
let cards = document.querySelectorAll(".cards");
document.querySelector(".cards").addEventListener("click", favs );
document.querySelector(".suggestionCards").addEventListener("click", favs);


  function favs(e){

   
    if (e.target && e.target.classList=="title" || e.target.nodeName=="IMG" ) {
     location.replace("https://comp-3512-w22-team-02.herokuapp.com/single-movie.php?id=" + e.target.dataset.id);
    }

  }


