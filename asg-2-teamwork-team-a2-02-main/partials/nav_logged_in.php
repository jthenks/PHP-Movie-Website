<!-- login needs to change to log out if user is logged in (later milestone), home needs to have a logged in and logged out version, favourites is only visible
 if user is logged in and registration is only visible if user is logged in -->
<header class="primary-header flex">
  <div>
    <img class="icon" src="images/movie-icon.png" alt="Movie Icon https://game-icons.net/1x1/delapouite/film-projector.html#download">
  </div>
  <hr class="line">

  <button class="mobile-nav-toggle" id="nav-toggle" aria-expanded="false">&#9776;</button>

  <nav>
    <ul id="primary-nav" data-visible="false" class="primary-nav flex">
      <li class="active"><a href="index.php" id="nav-home">HOME</a></li>
      <li><a href="about.php" id="nav-about">ABOUT</a></li>
      <li><a href="browse-movies.php" id="nav-browse">BROWSE</a></li>
      <!-- <li><a href=""></a>Favourites</li> -->
      <li><a href="index.php?status=logout" id="nav-login">LOGOUT </a></li>
      <li><a href="favorites.php" id="nav-register">FAVOURITES</a></li>

    </ul>
  </nav>
</header>
