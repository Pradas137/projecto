<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link href="https://fonts.googleapis.com/css?family=Share+Tech+Mono&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../css/terminal.css">
  <script src="../js/script.js"></script>
  <script src="../js/gameSettings.js"></script>
  <title>Fallout Hack Terminal</title>
</head>

<body class="background">
  <div id="container" class="terminal">
    <div id="gamePanel" class="screenEffect">
      <div id="header-container">
        <p id="title">ROBCO INDUSTRIES (TM) TERMINAL PROTOCOL</p>
      </div>
      <div id="enter">
        <p>ENTER PASSWORD NOW
        <output id="display-area">00:00.000</output></p>
      </div>
      <div id="attempts">
        <p>ATTEMPT(S) LEFT:  <span id="tries"></span></p>
      </div>
      <div id="terminal">
        <?php require './stringDump.php'; ?>
        <div class="input" id="prompt-position">
          <div id="prompt"></div>
          <div id="cursor">
            <p>></p>
            <p class="blink">█</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="winPanel" class="terminal hide">
    <div id="rankigForm" class="hide">
      <img src="../resources/boy.png" alt="winBoy" width="85%">
      <p>Enter your name to appear in the ranking</p>
      <form action="./bridge.php" method="post">
        <?php
        //If there is a name in the session, autocomplete the form
        if (isset($_SESSION["name"])) {
          $name = htmlspecialchars($_SESSION["name"]);
          echo "<input type='text' name='name' value=\"$name\" required autofocus>";
        } else {
          echo "<input type='text' name='name' required autofocus>";
        }
        ?>
        <input type="hidden" name="failedAttempts" id="failedAttempts">
        <input type="hidden" name="gameTime" id="gameTime">
        <input type="hidden" name="gamemode" id="gamemode">
        <input type="image" src="../resources/button.png" alt="submit" width="25%">
      </form>
    </div>
  </div>
  <div id="losePanel" class="terminal hide">
    <h3>Terminal blocked.</h3>
    <img src="../resources/nuclear_fungus.jpg" alt="nuclear fungus" width="75%">
    <div class="buttons">
      <?php
      // The play again button needs send the data about the game mode and if it is a hardcore game 
      $hardcore = "";
      if (isset($_GET["hardcore"]) and $_GET["hardcore"] == "on") {
        $hardcore = "&hardcore=on";
      }
      if (isset($_GET["difficulty"]) and in_array($_GET["difficulty"], ["normal", "hard"])) {
        $difficulty = $_GET["difficulty"];
        $get = "difficulty=$difficulty" . $hardcore;
      } else {
        $get = "difficulty=easy" . $hardcore;
      }
      echo "<button onclick=\"window.location.href='./bridge.php?$get'\" class='button'>Play Again</button>";
      ?>
      <button onclick="window.location.href='../index.php'" class="button" accesskey="m">Menu</button>
      <button onclick="window.location.href='./ranking.php'" class="button" accesskey="r">Ranking</button>
    </div>
  </div>
  </div>
  <div id="options">
    <i id="home" onclick="window.location.href='../index.php'" class="material-icons">home</i>
    <i id="volume" class="material-icons" accesskey="u"> volume_up </i>
    <i id="colorBlindness" class="material-icons off" accesskey="o"> visibility_off </i>
  </div>
  <div id="rotate">Turn your device or resize your browser</div>
  <div id="audioLibrary">
    <audio id="sos" class="sound" src="path"></audio>
  </div>
</body>

</html>