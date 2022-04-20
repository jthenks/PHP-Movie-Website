<?php


$joined = false;

function isValidQuery($pdo)
{
  require "data/all-movie-id.php";
  $movieId = $_GET["id"];

  return  isId($movieId) && isOneKey() && isGreaterThanZero($movieId) && isInteger($movieId) && isMovie($movieId, $pdo);
}
function isId()
{
  $id = array_key_exists("id", $_GET);

  if (!$id) {
    determineError("noIdKey");
  }
  return array_key_exists("id", $_GET);
}
function isOneKey()
{
  //https://stackoverflow.com/questions/8469767/get-url-query-string-parameters
  $rows = explode("&", $_SERVER['QUERY_STRING']);
  $count = sizeof($rows) == 1;

  if (!$count == 1) {
    determineError("moreThanOne");
  }

  return $count == 1;
}
function isInteger($movieId)
{
  $isNum = is_numeric($movieId);
  if (!$isNum) {
    determineError("notNumeric");
  }
  return is_numeric($movieId);
}
function isGreaterThanZero($movieId)
{
  $idGreaterThanZero = $movieId > 0;
  if (!$idGreaterThanZero) {
    determineError("idLessThanZero");
  }
  return $movieId > 0;
}
function isMovie($movieId, $pdo)
{
  $statement =  $pdo->prepare("SELECT title FROM movie WHERE id IN ($movieId)");
  $statement->execute();
  $results = $statement->fetchAll(PDO::FETCH_OBJ);

  $foundMovie = count($results) == 1 ? true : false;

  if (!$foundMovie) {
    determineError("noMovie");
  }

  return $foundMovie;
}

function determineError($error)
{
  setcookie("error", $error);
}

function getFromApi($title, $pdo)
{
  // % wildcard from: https://www.mysqltutorial.org/mysql-like/
  $sql = "SELECT * FROM movie WHERE title LIKE :title";
  $statement = $pdo->prepare($sql);

  // ["title" => $title]
  $statement->execute(["title" => ("%$title%")]);
  $results = $statement->fetchAll(PDO::FETCH_ASSOC);

  return $results;
}

function getFavorites($id, $pdo)
{
  $sql = "SELECT * FROM favorites WHERE user_id LIKE :id";
  $statement = $pdo->prepare($sql);

  $statement->execute(["id" => $id]);
  $results = $statement->fetchAll(PDO::FETCH_ASSOC);

  //if a user does not have a favourite movie yet
  if (count($results) > 0) {
    $favIDList = [];
    foreach ($results as $result) {
      $favID = $result['movie_id'];
      array_push($favIDList, $favID);
    }
    $favIDString = implode(",", $favIDList);

    $sql = "SELECT * FROM movie WHERE id IN (:favIDString)";
    $statement = $pdo->prepare($sql);

    $statement->execute(["favIDString" => $favIDString]);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  return $results;
}

function addToFaves($userID, $movieID, $pdo)
{
  $sql = "INSERT INTO favorites (user_id, movie_id) VALUES (:userID, :movieID)";
  $statement = $pdo->prepare($sql);

  $statement->execute(["userID" => $userID, "movieID" => $movieID]);
}

function removeFromFaves($userID, $movieID, $pdo)
{
  $sql = "DELETE FROM favorites WHERE user_id = :userID AND movie_ID = :movieID";
  $statement = $pdo->prepare($sql);

  $statement->execute(["userID" => $userID, "movieID" => $movieID]);
}

function removeAllFaves($userID, $pdo)
{
  $sql = "DELETE FROM favorites WHERE user_id = :userID";
  $statement = $pdo->prepare($sql);

  $statement->execute(["userID" => $userID]);
}

function addToUsers($firstName, $lastName, $city, $country, $email, $password, $pdo)
{
  $sql = "SELECT 1 FROM users WHERE email LIKE ('$email')";
  $statement = $pdo->prepare($sql);
  $statement->execute();
  $results = $statement->fetchAll(PDO::FETCH_ASSOC);

  session_start();
  if (count($results) > 0) {
    $_SESSION["isLoggedIn"] = false;
    echo "<p style='color: red;'> Email already exists. </p>";
  } else {
    $digest = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    $sql = "INSERT INTO users (firstname, lastname, city, country, email, password) 
      VALUES ('$firstName' , '$lastName', '$city', '$country', '$email', '$digest')";
    $statement = $pdo->prepare($sql);

    $statement->execute();

    $_SESSION["isLoggedIn"] = true;
    // echo "<p style='color: green;'> New account created. </p>";
  }
}

function hasOneTitle()
{
  $rows = explode("&", $_SERVER['QUERY_STRING']);
  $count = sizeof($rows);

  return $count == 1 ? true : false;
}

function getHighestRating($pdo, $num)
{
  $sql = "SELECT id,poster_path,title FROM movie order by vote_average desc limit $num";
  $statement = $pdo->prepare($sql);
  $statement->execute();
  $results = $statement->fetchAll(PDO::FETCH_ASSOC);

  return $results;
}

function findSameReleaseDate($pdo, $year, $start, $end, $id)
{
  $sql = <<<'EOD'
  SELECT
      id,poster_path,title
  FROM
      movie
  WHERE
    YEAR(release_date) = :year
  AND vote_average BETWEEN :start AND :end AND id <> :id
  EOD;

  $statement = $pdo->prepare($sql);

  $statement->execute(["year" => $year, "start" => $start, "end" => $end, "id" => $id]);
  $results = $statement->fetchAll(PDO::FETCH_ASSOC);


  return $results;
}
