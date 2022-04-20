<?php

require "query-helper.php";
require "../database/Connection.php";
// require "./login/login-handler.php";

function newAccount()
{
  if (isset($_POST['submit'])) {
    if (
      $_POST['first_name'] != "" &&
      $_POST['last_name'] != "" &&
      $_POST['city'] != "" &&
      $_POST['country'] != "" &&
      $_POST['email'] != "" &&
      filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) &&
      $_POST['password'] != "" &&
      strlen($_POST['password']) >= 8 &&
      $_POST['confirm_pass'] == $_POST['password']
    ) {
      $config = include "../config.php";
      $pdo = Connection::connect($config['database']);
      addToUsers(
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['city'],
        $_POST['country'],
        $_POST['email'],
        $_POST['password'],
        $pdo
      );

      if ($_SESSION["isLoggedIn"] == true) {
        $_SESSION["user"] = createUser($pdo, $_POST["email"]);

        header("Location: ./index.php");
      }
    }
  }
}

function createUser($pdo, $e)
{
  $sqlUser = 'SELECT firstname, lastname, id, city, country FROM users WHERE email= :e';
  $statement = $pdo->prepare($sqlUser);
  $statement->execute(["e" => $e]);

  $results = $statement->fetchAll(PDO::FETCH_OBJ);
  $results = json_encode($results[0]);
  $results = json_decode($results, true);

  return $results;
}

function firstName()
{
  if (!isset($_POST['first_name'])) {
    echo "<label class='form__label name'>First Name</label>";
  } else {
    if ($_POST['first_name'] == "") {
      echo "<label class='form__label name' style='color: red;'>First Name is required</label>";
    } else {
      $_SESSION['first_name'] = $_POST['first_name'];
    }
  }
}

function firstNameValue()
{
  if (isset($_POST['first_name']) && $_POST['first_name'] != "") {
    echo $_POST['first_name'];
  }
}

function lastName()
{
  if (!isset($_POST['last_name'])) {
    echo "<label class='form__label name'>Last Name</label>";
  } else {
    if ($_POST['last_name'] == "") {
      echo "<label class='form__label name' style='color: red;'>Last Name is required</label>";
    } else {
      $_SESSION['last_name'] = $_POST['last_name'];
    }
  }
}

function lastNameValue()
{
  if (isset($_POST['last_name']) && $_POST['last_name'] != "") {
    echo $_POST['last_name'];
  }
}

function city()
{
  if (!isset($_POST['city'])) {
    echo "<label class='form__label name'>City</label>";
  } else {
    if ($_POST['city'] == "") {
      echo "<label class='form__label name' style='color: red;'>City is required</label>";
    } else {
      $_SESSION['city'] = $_POST['city'];
    }
  }
}

function cityValue()
{
  if (isset($_POST['city']) && $_POST['city'] != "") {
    echo $_POST['city'];
  }
}

function country()
{
  if (!isset($_POST['country'])) {
    echo "<label class='form__label name'>Country</label>";
  } else {
    if ($_POST['country'] == "") {
      echo "<label class='form__label name' style='color: red;'>Country is required</label>";
    } else {
      $_SESSION['country'] = $_POST['country'];
    }
  }
}

function countryValue()
{
  if (isset($_POST['country']) && $_POST['country'] != "") {
    echo $_POST['country'];
  }
}

function email()
{
  if (!isset($_POST['email'])) {
    echo "<label class='form__label name'>Email</label>";
  } else {
    if ($_POST['email'] == "") {
      echo "<label class='form__label name' style='color: red;'>Email is required</label>";
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      echo "<label class='form__label name' style='color: red;'>Email is invalid</label>";
    } else {
      $_SESSION['email'] = $_POST['email'];
    }
  }
}

function emailValue()
{
  if (isset($_POST['email']) && $_POST['email'] != "") {
    echo $_POST['email'];
  }
}

function password()
{
  if (!isset($_POST['password'])) {
    echo "<label class='form__label name'>Password</label>";
  } else {
    if (strlen($_POST['password']) < 8) {
      echo "<label class='form__label name' style='color: red;'>Must be 8 characters long</label>";
    } else {
      $_SESSION['password'] = $_POST['password'];
    }
  }
}

function passValue()
{
  if (isset($_POST['password']) && $_POST['password'] != "") {
    echo $_POST['password'];
  }
}

function confirmPass()
{
  if (!isset($_POST['confirm_pass'])) {
    echo "<label class='form__label name'>Confirm Password</label>";
  } else {
    if ($_POST['confirm_pass'] == "") {
      echo "<label class='form__label name' style='color: red;'>Confirm Password is required</label>";
    } else if ($_POST['confirm_pass'] != $_POST['password']) {
      echo "<label class='form__label name' style='color: red;'>Password mismatch</label>";
    } else if (strlen($_POST['password']) < 8) {
      echo "<label class='form__label name' style='color: red;'>Password invalid</label>";
    } else {
      $_SESSION['confirm_pass'] = $_POST['confirm_pass'];
    }
  }
}

function confirmValue()
{
  if (isset($_POST['confirm_pass']) && $_POST['confirm_pass'] != "") {
    echo $_POST['confirm_pass'];
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "../partials/head.php"; ?>
  <link rel="stylesheet" href="style/join.css">
  <link rel="stylesheet" href="style/homeStyleTest.css">
</head>

<body>
  <?php include "../partials/nav.header.php"; ?>
  <div id="containerHome">
    <form method="post" class="userInput">
      <h2>Registration</h2>

      <div class="form__group field">
        <input class="form__field title" type="text" name="first_name" placeholder="First Name" value="<?php firstNameValue() ?>" />
        <datalist class="filterList">
        </datalist>
        <?php firstName() ?>
      </div>
      <div class="form__group field">
        <input class="form__field title" type="text" name="last_name" placeholder="Last Name" value="<?php lastNameValue() ?? '' ?>" />
        <datalist class="filterList">
        </datalist>
        <?php lastName() ?>
      </div>
      <div class="form__group field">
        <input class="form__field title" type="text" name="city" placeholder="City" value="<?php cityValue() ?>" />
        <datalist class="filterList">
        </datalist>
        <?php city() ?>
      </div>
      <div class="form__group field">
        <input class="form__field title" type="text" name="country" placeholder="Country" value="<?php countryValue() ?>" />
        <datalist class="filterList">
        </datalist>
        <?php country() ?>
      </div>
      <div class="form__group field">
        <input class="form__field title" type="text" name="email" id="email" placeholder="Email" value="<?php emailValue() ?>" />
        <datalist class="filterList">
        </datalist>
        <?php email() ?>
      </div>
      <div class="form__group field">
        <span id="password-hider" class="fa fa-fw fa-eye field-icon"></span>
        <input id="password-input" type="password" name="password" class="form__field title" placeholder="Password" value="<?php passValue() ?>" />
        <datalist class="filterList">
        </datalist>
        <?php password() ?>
      </div>
      <div class="form__group field">
        <span id="confirm-hider" class="fa fa-fw fa-eye field-icon"></span>
        <input id="confirm-input" type="password" name="confirm_pass" class="form__field title" placeholder="Confirm Password" value="<?php confirmValue() ?>" />
        <datalist class="filterList">
        </datalist>
        <?php confirmPass() ?>
      </div>

      <div class="buttonRow createRow">
        <div class="createAccountButton">
          <input type='hidden' name='submit' />
          <?= newAccount() ?>
          <button type="submit" class="style-3">Create Account</button>
        </div>
        <br>
        <div class="signUp">
          Already have an account?
          <a href="login.php">Sign In</a>
        </div>
      </div>
    </form>
  </div>

  <script src="javascript/clearStorage.js"></script>
  <script src="javascript/nav.header.js"></script>
  <script src="javascript/form.js"></script>
</body>

</html>
