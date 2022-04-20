

document.addEventListener("DOMContentLoaded", () => {
  document.querySelector("#nav-login").addEventListener("click", () => {

    localStorage.removeItem("searchFiltered");
    sessionStorage.removeItem("searchMovies");
  });

});
