<?php


//combination of https://www.youtube.com/watch?v=hkTQkaFzEEo
//and https://www.php.net/manual/en/timezones.america.php
function getTimeInBerta()  // love this function name - Jordan
{
  $dateTime = new DateTime(null, new DateTimeZone("America/Edmonton"));
  return $dateTime->format('m/d/Y g:i A');
}

function timeUntilLastMilestone()
{
  $currentDate = new DateTime(null, new DateTimeZone("America/Edmonton"));
  $dueDate =  new DateTime("4/8/2022 11:59pm", new DateTimeZone("America/Edmonton"));
  $interval = date_diff($dueDate, $currentDate);

  return $interval->format('%a days, %h hours, and %i minutes');
}

//check login status
session_start();
$loginStatus = $_SESSION["isLoggedIn"];

function isLoggedIn()
{
  $isUserLoggedIn = $_SESSION["isLoggedIn"];

  if (isset($_SESSION["isLoggedIn"]) &&  $isUserLoggedIn == true) {
    return include "../partials/nav_logged_in.php";
  } else {
    return include "../partials/nav.header.php";
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <?php require "../partials/head.php"; ?>
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="stylesheet" class="homepageCSS" href="style/aboutStyle.css">
  <link rel="stylesheet" href="style/homeStyleTest.css">

</head>




<body>
  <?php isLoggedIn()
  ?>
  <!-- HOMEPAGE -->
  <div class="containerAbout">
    <!-- <div class="header">
      Header
    </div> -->

    <div class="row2">
      <div class="aboutCommon bg-zinc-500">
        <div id="about">ABOUT</div>
        <div id="uni">University: <span>Mount Royal University</span></div>
        <div id="class">Class: <span>COMP 3512</span></div>
        <div id="sem">Semester: Winter 2022</div>
        <div id="tech-used">Web Technologies Used: <span>PHP, Javascript, HTML, CSS</span></div>
        <div id="online"> <a href="https://www.youtube.com/watch?v=HbBMp6yUXO0" target="_blank">Online Resource Link For Nav Bar</a></div>
        <div id="asg-link"><a href="https://github.com/MRU-CSIS-3512-202201-001/asg-2-teamwork-team-a2-02" target="_blank">Assignment Source Code Link</a></div>
        <div id="dateTime">Current Date and Time in Alberta is: <span><?= getTimeInBerta() ?></span></div>
        <div id="countdown"> <span><?= timeUntilLastMilestone() ?></span> until milestone 5 is due</div>

      </div>

      <div class="memb1 bg-zinc-700">
        <div class="pic"> <img id="johnPic" src="images/me1.png" alt="john"></div>
        <div class="name">John Valiente</div>
        <!-- <div class="links">Github link : <span>test</span></div> -->
        <a href="https://github.com/johnvaliente" target="_blank">Github Link</a>

      </div>
      <div class="memb2 bg-zinc-700">
        <div class="pic"> <img id="darPic" src="images/dar.png" alt="darylle"> </div>
        <div class="name">Darylle Diego</div>
        <!-- <div class="links">Github link : <span>test</span></div> -->
        <a href="https://github.com/DarylleDiego" target="_blank">Github Link</a>

        <!-- Photo by Viktor Forgacs on Unsplash -->
      </div>
      <div class="memb3 bg-zinc-700">
        <div class="pic"> <img id="jordPic" src="images/jordan.jpg" alt="jordan"> </div>
        <div class="name">Jordan Henkelman</div>
        <a href="https://github.com/jthenks" target="_blank">Github Link</a>

      </div>
    </div>


  </div>

  <script src="javascript/clearStorage.js"></script>
  <script src="javascript/nav.header.js"></script>
</body>

</html>
