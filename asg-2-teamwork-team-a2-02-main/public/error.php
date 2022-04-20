<?php
$error = "none";
if (isset($_COOKIE['error'])) {

  if ($_COOKIE['error'] == 'noIdKey') {
    $error = "No id detected";
  } else if ($_COOKIE['error'] == 'moreThanOne') {
    $error = "There is more than one key";
  } else if ($_COOKIE['error'] == 'notNumeric') {
    $error = "Not numeric";
  } else if ($_COOKIE['error'] == 'idLessThanZero') {
    $error = "Id is less than zero";
  } else if ($_COOKIE['error'] == 'noMovie') {
    $error = "Movie not found";
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "../partials/head.php"; ?>
  <link rel="stylesheet" href="style/errorStyle.css">
  <link rel="stylesheet" href="style/homeStyleTest.css">
</head>

<body class="errBG">
  <?php include "../partials/nav.header.php"; ?>
  <div class="container">

    <div class="col1">
      <h2>Error 404:</h2>
      <h1><?php echo $error ?></h1>
      <h3>Please try again</h3>

    </div>

    <div class="col2">
      <img class="crash" src="images/crash.png" alt="Icon From juicy_fish at flaticon.com">

    </div>
    <a href="https://www.flaticon.com/free-icons/crash" title="crash icons">Crash icons created by juicy_fish - Flaticon</a>

  </div>
  <script src="javascript/clearStorage.js"></script>
  <script src="javascript/nav.header.js"></script>
</body>

</html>
