<?php

// creating connection to db and adding the searched movies into session storage
require "query-helper.php";
$config = include "../config.php";
require "../database/Connection.php";


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "../partials/head.php"; ?>
  <link rel="stylesheet" href="style/join.css">
  <link rel="stylesheet" href="style/homeStyleTest.css">
</head>


<body>

  <!-- HOMEPAGE -->
  <?php include "../partials/nav.header.php";
  ?>
  <div id="containerHome">
    <div class="userInput">
      <h2>Movie Browser</h2>

      <!-- <form method="post"> -->
      <div class="searchBar">
        <div class="form__group field">
          <input list="filterList" type="text" name="movie_search" class="form__field title" placeholder="Search a title..." />
          <datalist id="filterList">
          </datalist>
          <label id="name" class="form__label">Search a movie title...</label>
        </div>
      </div>

      <div class="buttonRow">
        <div class="showMatchingButton">
          <button type="submit" id="searchMatching" class="style-3">Show Matching Movies</button>
          <!-- </form> -->
        </div>
        <div class="loginButton">
          <button type="button" class="style-3" onclick="window.location.href = 'login.php';">Login</button>
        </div>
        <div class="signUp">
          Don't have an account?
          <a href="join.php">Join Now</a>
        </div>
      </div>

      <div class="photoCredit">
        Photo from Wallpaper Flare
      </div>
    </div>

  </div>
  <script src="javascript/clearStorage.js"></script>
  <script src="javascript/nav.header.js"></script>
  <script src="javascript/index.js"></script>
</body>

</html>
