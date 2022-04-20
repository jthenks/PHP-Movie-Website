<?php
session_start();

if ($_GET['status'] == "logout") {
  $_SESSION["isLoggedIn"] = false;
}

require "query-helper.php";
require "MovieObject.php";
$config = include "../config.php";
include "../database/Connection.php";
include "userInfo/UserObject.php";

$pdo = Connection::connect($config['database']);

$loginStatus = $_SESSION["isLoggedIn"];


// handle fav/unfav button
if (isset($_POST['favourite'])) {

  $userID = $_SESSION['user']['id'];
  $movieID = $_POST['favourite'];
  $pdo = Connection::connect($config['database']);
  addToFaves($userID, $movieID, $pdo);
  $_SESSION["favMovies"] = getFavorites($userID, $pdo);
  $favMovies = $_SESSION['favMovies'];
}

//scanning if movie is in the array
if (isset($_POST['unfavourite'])) {
  if ($_POST['unfavourite'] != "") {

    $userID = $_SESSION['user']['id'];
    $movieID = $_POST['unfavourite'];
    $pdo = Connection::connect($config['database']);
    removeFromFaves($userID, $movieID, $pdo);
    $_SESSION["favMovies"] = getFavorites($userID, $pdo);
    $favMovies = $_SESSION['favMovies'];
  }
}

function determineNavBar($loginStatus)
{

  if ($loginStatus == true) {
    return include "../partials/nav_logged_in.php";
  } else {
    return include "../partials/nav.header.php";
  }
}

function displayFavourite($loginStatus)
{
  if ($loginStatus) {
    return 'Favourite : <span class="fa fa-heart favButton" id="258193"></span>';
  }
}

if (!isValidQuery($pdo)) {
  header("Location: error.php");
} else {
  $movieId = $_GET["id"];

  $statement = $pdo->prepare($sql = "SELECT id, tmdb_id, imdb_id, release_date, title, vote_average, vote_count, runtime, popularity, revenue, 
  poster_path, tagline, overview, production_companies, production_countries, genres, keywords, cast, crew FROM movie WHERE id IN ($movieId)");

  $statement->execute();

  $results = $statement->fetchAll(PDO::FETCH_OBJ);
  $results = json_encode($results[0]);
  $results = json_decode($results, true);

  $movie = new MovieObject(
    $results["id"],
    $results["tmdb_id"],
    $results["imdb_id"],
    $results["release_date"],
    $results["title"],
    $results["vote_average"],
    $results["vote_count"],
    $results["runtime"],
    $results["popularity"],
    $results["revenue"],
    $results["poster_path"],
    $results["tagline"],
    $results["overview"],
    $results["production_companies"],
    $results["production_countries"],
    $results["genres"],
    $results["keywords"],
    $results["cast"],
    $results["crew"]
  );
}

//check if this movie is the user's fav movie
function checkIfFav($pdo, $movie, $loginStatus)
{

  if ($loginStatus) {
    //create connection to db and fetch fav movies
    $userID = $_SESSION["user"]["id"];
    $fav_movies = getFavorites($userID, $pdo);


    $favId = createFavArrId($fav_movies);


    if (isFavourite($movie->id, $favId)) {
      //display red heart 
      //and then when user clicks the red heart - it turns white - and gets removed in session
      return "<input type='hidden' name='unfavourite' value='$movie->id' /><button type='submit' class='favouriteButton checked' '>
      <img alt='favourite-heart' class='favourited' title='taken from: https://www.pinclipart.com/maxpin/hoRbm/' src='images/favourited-heart.png' width='30' height='30'>
      </button>";
    } else {
      //do not display red heart
      return " <input type='hidden' name='favourite' value='$movie->id'/><button type='submit' class='favouriteButton' '>
      <img alt='favourite-heart' class='favourited' title='taken from: https://www.pinclipart.com/maxpin/hoRbm/' src='images/unfavourited-heart.png' width='30' height='30' >
      </button>";
    }
  } else {
    return "";
  }
}
function isFavourite($movieId, $favIds)
{
  $result = false;
  foreach ($favIds as $id) {
    if ($movieId == $id) {
      $result = true;
    }
  }

  return $result;
}

function createFavArrId($foundMov)
{
  $favId = [];
  foreach ($foundMov as $mov) {
    array_push($favId, $mov['id']);
  }
  return $favId;
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "../partials/head.php"; ?>
  <link rel="stylesheet" href="style/homeStyleTest.css">
  <link rel="stylesheet" href="style/single-movieStyle.css">
  <script src="javascript/single-movie.js"></script>
</head>


<body>
  <?php determineNavBar($loginStatus); ?>

  <div id="detailsHeader">
    <h1 id="mainTitleDetails">Movie Details</h1>
  </div>

  <div id="detailsMain">
    <div id="movieDetailSection">
      <div id="movieTitleDiv">
        <h2 id="movieTitle"><?php echo $movie->title ?></h2>
      </div>
      <div id="movieDetailDiv">
        <div class="detailText">
          Release Date: <span id="releaseDate" class="detailValue"><?php echo $movie->relDate ?></span>
        </div>
        <div class="detailText">
          Revenue: <span id="revenue" class="detailValue"><?php echo "$" . $movie->formattedRevenue() ?></span>
        </div>
        <div class="detailText">
          Runtime: <span id="runtime" class="detailValue"><?php echo $movie->runtime . " minutes" ?></span>
        </div>
        <div class="detailText">
          Tagline: <span id="tagline" class="detailValue"><?php echo $movie->tagline ?></span>
        </div>
        <div class="detailText">
          IMDB Link: <span class="detailValue"><a id="imdb" target="_blank" href="<?php echo "https://www.imdb.com/title/$movie->imdbId" ?>"> Click Here to the IMDB Page</a></span>
        </div>
        <div class="detailText">
          TMDB Link: <span class="detailValue"><a id="tmdb" target="_blank" href="<?php echo "https://www.themoviedb.org/movie/$movie->tmdbId" ?>">Click Here to the TMDB Page</a></span>
        </div>
        <div class="detailText">
          Overview: <span id="overview" class="detailValue"><?php echo $movie->overview ?></span>
        </div>
        <div class="detailText">
          Ratings:
          <span id="popularity" class="detailValue"><?php echo $movie->popularity ?></span> <span id="arrow"><img src="images/arrow.png" width="15" height="15" alt="arrow"></span>
          <span id="average" class="detailValue"><?php echo $movie->voteAvg ?></span> <span id="vote"><img src="images/vote.png" width="15" height="15" alt="vote"></span>
          <span id="count" class="detailValue"><?php echo $movie->voteCount ?></span> <span id="star"><img src="images/star.png" width="15" height="15" alt="star"></span>
        </div>
      </div>
      <div id="companyDiv">
        <div>
          <h3>Companies</h3>
        </div>
        <div id="companyNames"> <?php echo $movie->populateDetails("prodCompanies") ?>
        </div>
      </div>
      <div id="countryDiv">
        <div>
          <h3>Countries</h3>
        </div>
        <div id="countryNames"> <?php echo $movie->populateDetails("prodCountries") ?>
        </div>
      </div>
      <div id="keywordDiv">
        <div>
          <h3>Keywords</h3>
        </div>
        <div id="keywords"> <?php echo $movie->populateDetails("keywords") ?>
        </div>
      </div>
      <div id="genreDiv">
        <div>
          <h3>Genres</h3>
        </div>
        <div id="genres"> <?php echo $movie->populateDetails("genres") ?>
        </div>
      </div>
    </div>
    <div id="moviePosterSection">
      <div>
        <picture>
          <source media="(max-width: 500px)" id="sourceMediaPoster" <?php echo "srcset=https://image.tmdb.org/t/p/w342/" . $movie->posterPath ?>>
          <img id="poster" alt="Movie Poster" <?php echo "src=https://image.tmdb.org/t/p/w342/" . $movie->posterPath ?>>
        </picture>
      </div>
      <form method="post">

        <?php //echo displayFavourite($loginStatus)
        echo checkIfFav($pdo, $movie, $loginStatus);

        ?>




      </form>
      <!-- the code for this modal was taken from https://www.w3schools.com/howto/howto_css_modal_images.asp -->
      <div id="posterModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <picture>
            <source media="(min-width: 1000px)" id="sourceMediaPopupPoster" <?php echo "srcset=https://image.tmdb.org/t/p/w780/" . $movie->posterPath ?>>
            <source media="(min-width: 100px)" id="smallSourceMediaPopupPoster" <?php echo "srcset=https://image.tmdb.org/t/p/w780/" . $movie->posterPath ?>>
            <img id="popupPoster" alt="Movie Poster" <?php echo "src=https://image.tmdb.org/t/p/w780/" . $movie->posterPath ?>>
          </picture>
        </div>
      </div>
      <div>
      </div>
    </div>
    <div id="movieCastCrewSection">
      <div id="tab">
        <button type="button" id="castButton" class="activeTab">Cast</button>
        <button type="button" id="crewButton">Crew</button>
      </div>
      <div id="castContent">
        <div id="characterTitle">
          Characters
        </div>
        <div id="actorTitle">
          Actors
        </div>
        <div id="castDetails"> <?php echo $movie->populateCast() ?>
        </div>
      </div>
      <div id="crewContent">
        <div id="departmentTitle">
          Department
        </div>
        <div id="jobTitle">
          Job Title
        </div>
        <div id="crewNameTitle">
          Name
        </div>
        <div id="crewDetails"> <?php echo $movie->populateCrew()  ?>
        </div>
      </div>
    </div>
    <script src="javascript/clearStorage.js"></script>
    <script src="javascript/nav.header.js"></script>

  </div>
</body>

</html>
