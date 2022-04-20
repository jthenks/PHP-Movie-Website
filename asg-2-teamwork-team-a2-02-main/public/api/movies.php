<?php


require "../../database/Connection.php";
$config = include "../../config.php";
require "../query-helper.php";

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");

$pdo = Connection::connect($config['database']);
$error = [];

if (isset($_GET["title"]) && hasOneTitle() && $_GET["title"] != "") {
  $results = getFromApi($_GET["title"], $pdo);
  echo json_encode($results);  // changed these from echo
} else {
  echo json_encode($error); // changed from echo 
}
