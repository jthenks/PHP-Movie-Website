<?php
session_start();

require "query-helper.php";
$config = include "../config.php";
require "../database/Connection.php";

$favMovies = $_SESSION["favMovies"];

$GLOBALS['found'] = false;

//check login status
$loginStatus = $_SESSION["isLoggedIn"];

function isLoggedIn()
{
  $isUserLoggedIn = $_SESSION["isLoggedIn"];

  if (isset($_SESSION["isLoggedIn"]) &&  $isUserLoggedIn == true) {
    return include "../partials/nav_logged_in.php";
  } else {
    header("Location: error.php");
  }
}

// if no matches
if ($favMovies == null) {
  $GLOBALS['found'] = false;
} else {
  $GLOBALS['found'] = true;
}

// to handle unfavorite click
if (isset($_POST['favID'])) {
  if ($_POST['favID'] != "") {
    $length = count($_SESSION["favMovies"]);
    for ($i = 0; $i < $length; $i++) {
      if ($_SESSION["favMovies"][$i]['id'] == $_POST['favID']) {
        $userID = $_SESSION['user']['id'];
        $movieID = $_POST['favID'];
        $pdo = Connection::connect($config['database']);
        removeFromFaves($userID, $movieID, $pdo);
        $_SESSION["favMovies"] = getFavorites($userID, $pdo);
        $favMovies = $_SESSION['favMovies'];
        break;
      }
    }
    if (count($favMovies) == 0) {
      $GLOBALS['found'] = false;
      notFound();
    }
  }
}

// to handle poster or title click
if (isset($_POST['favImg']) || isset($_POST['favTitle'])) {
  if ($_POST['favImg'] != "") {
    $id = $_POST['favImg'];
    header("Location: single-movie.php?id=$id");
  } else if ($_POST['favTitle'] != "") {
    $id = $_POST['favTitle'];
    header("Location: single-movie.php?id=$id");
  }
}

// handle unfav all
if (isset($_POST['unfavAll'])) {
  if ($_POST['unfavAll'] != "") {
    // array_splice($_SESSION["favMovies"], 0, count($_SESSION["favMovies"]));
    $userID = $_SESSION['user']['id'];
    $pdo = Connection::connect($config['database']);
    $length = count($_SESSION["favMovies"]);
    for ($i = 0; $i < $length; $i++) {
      removeAllFaves($userID, $pdo);
    }
    $_SESSION["favMovies"] = getFavorites($userID, $pdo);
    $favMovies = $_SESSION['favMovies'];
    if (count($favMovies) == 0) {
      $GLOBALS['found'] = false;
      notFound();
    }
  }
}

function notFound()
{
  if ($GLOBALS['found'] == false) {
    return "<img src='images/img_noresults_movies.png' id='notFound' alt='No movies found' title='image taken from: https://ell.brainpop.com/make-a-map/?topic=86'>";
  } else {
    return;
  }
}

function populateMovies($foundMovies)
{
  $movieList = "";
  $sortedMovies = sortMoviesAlphabetically($foundMovies);
  foreach ($sortedMovies as $movie) {
    $id = $movie['id'];
    $movieList .= "<div>" .
      "<form method='post' id='posterDiv'>" .
      "<input type='hidden' name='favImg' value='$id'/> " .
      "<button type='submit' class='favImg'><img alt='poster' class='clickPoster' src=https://image.tmdb.org/t/p/w92/" . $movie['poster_path'] . "></button>" .
      "</form>" .
      "<form method='post' id='titleDiv'>" .
      "<input type='hidden' name='favTitle' value='$id'/> " .
      "<button type='submit' class='favTitle'>" . $movie['title'] . "</button>" .
      "</form>" .

      "<form method='post'>" .
      "<input type='hidden' name='favID' value='$id'/>" .
      "<button type='submit' class='favouriteButton'><img alt='favourite-heart' class='favourited' title:'taken from: https://www.pinclipart.com/maxpin/hoRbm/' src='images/favourited-heart.png'></button>" .
      "</form>" .
      "</div>";
  }

  return $movieList;
}

function sortMoviesAlphabetically($movies)
{

  //https://blog.martinhujer.cz/clever-way-to-sort-php-arrays-by-multiple-values/
  usort($movies, function ($a,  $b): int {

    return $a['title'] <=> $b['title'];
  });

  return $movies;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "../partials/head.php"; ?>

  <link rel="stylesheet" href="style/homeStyleTest.css">
  <link rel="stylesheet" href="style/browse-moviesStyle.css">
  <link rel="stylesheet" href="style/favorites.css">
</head>

<body>
  <?php isLoggedIn() ?>
  <header id="defaultHeader">
    <h1 class="title" id="mainTitle">Favourite Movies</h1>
  </header>
  <form method="post" id="unfavLaptop">
    <input type='hidden' name='unfavAll' value='unfavAll' />
    <button type="submit" class="headings unfavAll">Unfavorite All</button>
  </form>
  <div id="container">
    <form method="post" id="unfavMobile">
      <input type='hidden' name='unfavAll' value='unfavAll' />
      <button type="submit" class="headings unfavAll">Unfavorite All</button>
    </form>
    <div id="main">
      <h2 class="title" id="mainHeading">List / Matches</h2>
      <div id="favListingMovies" class="favListing">
        <?php
        echo notFound();
        echo populateMovies($_SESSION["favMovies"]);
        ?>
      </div>
      <div id="message"></div>
    </div>
  </div>
  <script src="javascript/clearStorage.js"></script>
  <script src="javascript/nav.header.js"></script>
</body>
