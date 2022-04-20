<?php

session_start();
// creating connection to db and adding the searched movies into session storage
require "query-helper.php";
$config = include "../config.php";
require "../database/Connection.php";
include "userInfo/UserObject.php";
include "presenter/favMoviePresenter.php";

$pdo = Connection::connect($config['database']);

//search the user session
if (isset($_SESSION["user"])) {
  $results = $_SESSION["user"];
  $userObject = new UserObject($results['firstname'], $results['lastname'], $results['city'], $results['country'], $results['id']);

  //create connection to db and fetch fav movies
  $userID = $_SESSION["user"]["id"];
  $_SESSION["favMovies"] = getFavorites($userID, $pdo);
}

$foundMovies = $_SESSION["favMovies"];
$GLOBALS['found'] = false;

function moviesUserMayLike($pdo, $foundMovies)
{

  //if user does not have any favourites go to displaySugg function
  if (count($foundMovies) == 0) {
    displaySugg($pdo, 15);
  } else if (count($foundMovies) > 0) {
    fromReleaseDate($pdo, $foundMovies[0]);
  }
}

function displaySugg($pdo, $num)
{
  $i = 0;

  if ($GLOBALS['found'] == false) {

    $suggested_movie =  getHighestRating($pdo, $num);

    $count = count($suggested_movie);

    while ($i < $count) {
      $suggPresenter = new favMoviePresenter($suggested_movie[$i]);
      $i++;
      echo $suggPresenter->displayCard();
    }
  }
}

function fromReleaseDate($pdo, $foundMovies)
{

  $rate = $foundMovies['vote_average'];
  $plusTwentyFive = $rate + 0.25;

  $minTwentyFive = $rate - 0.25;

  $orderdate = explode('-', $foundMovies['release_date']);
  $year = $orderdate[0];
  $year = intVal($year);

  $sameYrMov = findSameReleaseDate($pdo, $year,  $minTwentyFive, $plusTwentyFive, $foundMovies['id']);
  $movSize = count($sameYrMov);

  // $newArr = removeFavMov($sameYrMov);

  if ($movSize < 10) {

    $num = 15 - $movSize;
    $addMov = getHighestRating($pdo, $num);
    $movSuggestions =  array_merge($sameYrMov, $addMov);
    displayMovSuggestions($movSuggestions);
  } else {
    displayMovSuggestions($sameYrMov);
  }
}



function displayMovSuggestions($sameYrMov)
{
  $i = 0;
  $count = count($sameYrMov);

  while ($i < $count) {
    $suggPresenter = new favMoviePresenter($sameYrMov[$i]);
    $i++;
    echo $suggPresenter->displayCard();
  }
}

function notFound($favMovies)
{

  if (count($favMovies) == 0) {

    return "No Favourite Movies Found ";
  } else {
    return;
  }
  // if ($GLOBALS['found'] == false) {
  // } else {
  // }
}

function createFavCard()
{
  $i = 0;
  $userFavMovies = $_SESSION["favMovies"];

  $count = count($userFavMovies);

  while ($i < $count) {
    $favPresenter = new favMoviePresenter($userFavMovies[$i]);
    $i++;
    echo $favPresenter->displayCard();
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "../partials/head.php"; ?>
  <link rel="stylesheet" href="style/homePage.css">
  <link rel="stylesheet" href="style/homeStyleTest.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <?php include "../partials/nav_logged_in.php"; ?>
  <div class=" homeContainer">

    <div class="col1 ">
      <!-- <form method="post"> -->
      <div class="searchBar">
        <div class="form__group field">
          <input list="filterList" type="text" name="movie_search" class="form__field title  " placeholder="Search a title..." required />
          <datalist id="filterList">
          </datalist>
          <label id="name" class="form__label">Search a movie title...</label>
        </div>
      </div>

      <div class="loginButton">
        <!-- <button type="button" class="style-3 bg-slate-100 w-36">Search Movie</button> -->
        <button type="submit" id="searchMatching" class="style-3 bg-slate-100 w-80 h-12">Show Matching Movies</button>
      </div>
      <!-- </form> -->
    </div>

    <div class="shadow col2 bg-slate-800 ">
      <div>User Information</div>
      <div class="user">
        First name: <span><?php echo $userObject->getFName() ?></span>
        <br>
        Last name: <span><?php echo $userObject->getLName() ?></span>
        <br>
        City: <span><?php echo $userObject->getCity() ?></span>
        <br>
        Country: <span><?php echo $userObject->getCountry() ?></span>
      </div>
    </div>

    <div class="shadow col3 bg-slate-800 ">
      <div class="fav-header"> Favourite Movies</div>

      <!-- Check if there's anything in the fav movies, if not then  -->
      <!-- create as many fav-cards depending on the size of the array -->
      <div class="cards">
        <?php
        echo createFavCard(); ?>
        <div class="notFound">

          <?php echo notFound($foundMovies);
          ?>
        </div>

      </div>

    </div>
    <div class="shadow col4 bg-slate-800 ">
      <div class="reco-header"> Movies You May like</div>
      <div class="suggestionCards">
        <?php echo moviesUserMayLike($pdo, $foundMovies) ?>

      </div>


    </div>

  </div>


  <script src="javascript/clearStorage.js"></script>
  <script src="javascript/nav.header.js"></script>
  <script src="javascript/homePage.js"></script>
  <script src="javascript/index.js"></script>
</body>

</html>
