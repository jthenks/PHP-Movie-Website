
document.addEventListener("DOMContentLoaded", () => {

  document.querySelector("#searchMatching").addEventListener("click", () => {
    let search = document.querySelector(".title").value;

    if(search === "") {
      document.querySelector("#name").style.color='red';
    }
    else {
      getMovies(search);
    }

  });

});

function getMovies(title) {
  let getTitle = title;

  let movieEndpoint = `https://comp-3512-w22-team-02.herokuapp.com/api/movies.php?title=${getTitle}`;   
  fetch(movieEndpoint)
    .then((response) => response.json())
    .then((movie) => {
      searchMovie(movie);
    });

    function searchMovie(movies) {
      sessionStorage.clear();
      localStorage.clear();
      sessionStorage.setItem("searchMovies", JSON.stringify(movies));
      localStorage.setItem("searchFiltered", JSON.stringify(movies));
      window.location.href = "browse-movies.php";
    }
}


