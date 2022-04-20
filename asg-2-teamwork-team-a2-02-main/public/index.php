<?php

session_start();

if ($_GET['status'] == "logout") {
  $_SESSION["isLoggedIn"] = false;
}
$isUserLoggedIn = $_SESSION["isLoggedIn"];


if ($isUserLoggedIn) {

  require include "home_logged_in.php";
} else {
  require include "home_logged_out.php";
}
