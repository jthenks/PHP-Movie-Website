<?php
session_start();

require "query-helper.php";
$config = include "../config.php";
require "../database/Connection.php";

// $foundMovies = $_SESSION['foundMovies'];
$favMovies = $_SESSION["favMovies"];

//check login status
$loginStatus = $_SESSION["isLoggedIn"];

function isLoggedIn()
{
  $isUserLoggedIn = $_SESSION["isLoggedIn"];

  if (isset($_SESSION["isLoggedIn"]) &&  $isUserLoggedIn == true) {
    return include "../partials/nav_logged_in.php";
  } else {
    return include "../partials/nav.header.php";
  }
}

// handle fav/unfav button
if (isset($_POST['favourite'])) {
  // echo "favourite?";
  if ($_POST['favourite'] != "") {
    if (isset($_SESSION["isLoggedIn"]) && isset($_SESSION['favMovies'])) {
      // echo $_POST['favourite'];
      $userID = $_SESSION['user']['id'];
      // echo $userID;
      $movieID = $_POST['favourite'];
      $pdo = Connection::connect($config['database']);
      addToFaves($userID, $movieID, $pdo);
      $_SESSION["favMovies"] = getFavorites($userID, $pdo);
      $favMovies = $_SESSION['favMovies'];
    }
  }
}

if (isset($_POST['unfavourite'])) {
  if ($_POST['unfavourite'] != "") {
    $length = count($_SESSION["favMovies"]);
    for ($i = 0; $i < $length; $i++) {
      if ($_SESSION["favMovies"][$i]['id'] == $_POST['unfavourite']) {
        $userID = $_SESSION['user']['id'];
        $movieID = $_POST['unfavourite'];
        $pdo = Connection::connect($config['database']);
        removeFromFaves($userID, $movieID, $pdo);
        $_SESSION["favMovies"] = getFavorites($userID, $pdo);
        $favMovies = $_SESSION['favMovies'];
      }
    }
  }
}

function createIdString($favourites)
{
  $favId = "";
  foreach ($favourites as $mov) {
    $favId .= $mov['id'] . " ";
  }
  return $favId;
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
</head>

<body>
  <?php isLoggedIn() ?>
  <header id="defaultHeader">
    <h1 class="title" id="mainTitle">Browse Movies</h1>
  </header>
  <div id="container">
    <div id="aside">
      <div id="filterForm">
        <section id="titleSection">
          <h2 id="browserDisplay">Movie Filters</h2>
          <h4 id="titleText">Movie Title</h4>
          <label>
            <input type="search" name="searchbar" id="searchBar" placeholder="Enter Movie Title">
          </label>
        </section>
        <section id="year">
          <h4 id="yearText">Year</h4>
          <label id="beforeYearLabel">
            <input type="radio" name="year" value="beforeYear">Before
          </label>
          <label>
            <input type="number" class="textbox" id="beforeYear" min="0" max="9999">
          </label>
          <br>
          <label id="afterYearLabel">
            <input type="radio" name="year" value="afterYear">After
          </label>
          <label>
            <input type="number" class="textbox" id="afterYear" min="0" max="9999">
          </label>
          <br>
          <label id="betweenYear">
            <input type="radio" name="year" value="betweenYear">Between
          </label>
          <label>
            <input type="number" class="textbox" id="betweenFrom" min="0" max="9999">
          </label>
          <label>
            <input type="number" class="textbox" id="betweenTill" min="0" max="9999">
          </label>
        </section>
        <section id="rate">
          <h4 id="rateText">Rating</h4>
          <label id="belowRateLabel">
            <input type="radio" name="rating" value="belowRate">Below
          </label>
          <label>
            <input type="range" min="0" max="10" class="slider" id="belowRate">
          </label>
          <label>
            <output id="belowOutput" class="outputs"></output>
          </label>
          <br>
          <label id="aboveRateLabel">
            <input type="radio" name="rating" value="aboveRate">Above
          </label>
          <label>
            <input type="range" min="0" max="10" class="slider" id="aboveRate">
          </label>
          <label>
            <output id="aboveOutput" class="outputs"></output>
          </label>
          <br>
          <label id="betweenRate">
            <input type="radio" name="rating" value="betweenRate">Between
          </label>
          <label>
            <input type="range" min="0" max="10" class="slider" id="betweenFromRate">
          </label>
          <label>
            <output id="betweenFromOutput" class="outputs"></output>
          </label>
          <label>
            <input type="range" min="0" max="10" class="slider" id="betweenTillRate">
          </label>
          <label>
            <output id="betweenTillOutput" class="outputs"></output>
          </label>
        </section>

        <button type="submit" id="filterMovies" class="buttons">Filter</button>
        <button type="reset" id="clearFilters" class="buttons">Clear</button>
      </div>
    </div>
    <button type="button" id="toggle"> &#9776; </button>
    <div id="main">
      <h2 class="title" id="mainHeading">List / Matches</h2>
      <div class="movieListingHeaders">
        <div class="headings" id="movieTitleDefault">Title
          <button type="button" class="sortButtons" id="titleAsc">🠕</button>
          <button type="button" class="sortButtons" id="titleDsc">🠗</button>
        </div>
        <div class="headings" id="movieYearDefault">Year
          <button type="button" class="sortButtons" id="yearAsc">🠕</button>
          <button type="button" class="sortButtons" id="yearDsc">🠗</button>
        </div>
        <div class="headings" id="movieRatingDefault">Rating
          <button type="button" class="sortButtons" id="rateAsc">🠕</button>
          <button type="button" class="sortButtons" id="rateDsc">🠗</button>
        </div>
        <div class="headings" id="movieFavouriteDefault">Favourite</div>
      </div>
      <div id="movieListingMovies" class="movieListing">

      </div>
      <div id="message"></div>
    </div>
  </div>
  <?= "<div class='hiddenIds' data-log='$loginStatus'> " . createIdString($_SESSION['favMovies']) . "</div>"; ?>
  <?= "<div class='hideFavs' data-log='" . $_SESSION['isLoggedIn'] . "'></div>"; ?>

  <script src="javascript/clearStorage.js"></script>
  <script src="javascript/browse-movies.js"></script>
  <script src="javascript/nav.header.js"></script>
</body>
