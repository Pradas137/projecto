<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://fonts.googleapis.com/css?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/terminal.css">
    <script src="../js/script.js"></script>
    <title>Fallout Hack Terminal</title>
  </head>
  <body class="background">
    <div id="container" class="terminal">
      <div id="gamePanel" class="screenEffect">
        <div id="header-container">

          <p id="title">ROBCO INDUSTRIES (TM) TERMINAL PROTOCOL<span>&nbsp;</span></p>
          </div>
          <p class ="enter">ENTER PASSWORD NOW<span>&nbsp;</span></p>
          <output id="display-area">00:00.000</output>
          <div id="attempts">
          <p class= "attemps" >ATTEMPT(S) LEFT:<span>&nbsp;</span></p><span id="tries"></span>
        </div>


        <div id="terminal" class="mymove" >
           <p><?php require './stringDump.php';?>
              <div class="input" id="prompt-position">
                <div id="prompt"></div>
                <div id="cursor">
                  <p></p><p class="blink">█</p></p>
                </div>
              </div>
            </div>
        </div>
      </div>
      <div id="winPanel" class="terminal hide">
          <div id="rankigForm" class="hide">
              <img src="../resources/boy.jpg" alt="winBoy" width="85%">
              <p>Enter your name to appear in the ranking</p>
              <form action="../index.php" method="post">
                <input type="text" name="name" required autofocus>
                <input type="hidden"  name="failedAttempts" id="failedAttempts">
                <input type="hidden" name="gameTime" id="gameTime">
                <input type="image" src="../resources/button.png" alt="submit" width="25%">
              </form>
            </div>
      </div>
      <div id="losePanel" class="terminal hide">
        <h3>Terminal blocked.</h3>
        <img src="../resources/nuclear_fungus.jpg" alt="nuclear fungus" width="75%">
        <div class="buttons">
          <button onclick="window.location.reload()" class="button again" accesskey="P">Play Again</button><br>
          <button onclick="window.location.href='../index.php'" class="button" accesskey="M">Menu</button>
          <button onclick="window.location.href='./ranking.php'" class="button" accesskey="R">Ranking</button>
        </div>
      </div>
    </div>
    <div id="rotate">Turn your device or resize your browser</div>
  </body>
</html>