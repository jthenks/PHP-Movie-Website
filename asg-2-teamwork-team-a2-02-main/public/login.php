<?php

function getMessage()
{

  if (isset($_GET['isValid']) && $_GET['isValid'] == "false") {
    $msg = '<h2 class="red">login attempt unsuccesful </h2>';
  } else {
    $msg = "<h2>Login</h2>";
  }
  return $msg;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
  <?php include "../partials/head.php"; ?>
  <link rel="stylesheet" href="style/join.css">
  <link rel="stylesheet" href="style/homeStyleTest.css">
</head>



<body>
  <?php include "../partials/nav.header.php"; ?>
  <form action="login/login-handler.php" method="post">
    <div id="containerHome">
      <div class="userInput">

        <?php echo getMessage() ?>



        <div class="searchBar">
          <div class="form__group field">
            <input class="form__field title" placeholder="Email" name="email" required />
            <datalist class="filterList">
            </datalist>
            <label class="form__label name">Email</label>
          </div>
          <div class="form__group field">
            <span id="password-hider" class="fa fa-fw fa-eye field-icon"></span>
            <input id="password-input" type="password" class="form__field title" name="password" placeholder="Password" required />
            <datalist class="filterList">
            </datalist>
            <label class="form__label name">Password</label>
          </div>
        </div>

        <div class="buttonRow">
          <div class="loginButton">
            <button type="submit" class="style-3">Login</button>
          </div>
          <br>
          <div class="signUp">
            Don't have an account?
            <a href="join.php">Join Now</a>
          </div>
        </div>
      </div>

    </div>
  </form>

  <script src="javascript/clearStorage.js"></script>
  <script src="javascript/nav.header.js"></script>
  <script src="javascript/showPassword.js"></script>
</body>

</html>
