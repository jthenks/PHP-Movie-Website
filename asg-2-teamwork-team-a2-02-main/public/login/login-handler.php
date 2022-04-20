<?php

session_start();

$config = include "../../config.php";
include "../../database/Connection.php";

$pdo = Connection::connect($config['database']);


$e = $_POST["email"];
$sql = 'SELECT password FROM users WHERE email= :e';

$statement = $pdo->prepare($sql);
$statement->execute(["e" => $e]);
$retrievedPass = $statement->fetchColumn();

//checks to see if user is valid or not, if not set a cookie and redirect to login.php
//if user is valid then go to home.php
if (isValidUser($retrievedPass)) {

  $_SESSION["isLoggedIn"] = true;
  $_SESSION["user"] = createUser($pdo, $e);

  header("Location: ../index.php");
} else {
  setcookie("isValid", "false");
  header("Location: ../login.php?isValid=false");
}

function isValidUser($retrievedPass)
{
  return password_verify($_POST['password'], $retrievedPass) == true ? true : false;
}

function createUser($pdo, $e)
{
  $sqlUser = 'SELECT firstname, lastname, id, city, country FROM users WHERE email= :e';
  $statement = $pdo->prepare($sqlUser);
  $statement->execute(["e" => $e]);

  $results = $statement->fetchAll(PDO::FETCH_OBJ);
  $results = json_encode($results[0]);
  $results = json_decode($results, true);

  // $userObject = new UserObject($results['firstname'], $results['lastname'], $results['city'], $results['country']);

  return $results;
}


// var_dump($userObject->getCountry());
