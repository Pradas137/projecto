<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="./css/mainMenu.css">
  <title>Game Menu</title>
</head>

<body>
  <div class="container">
    <h1>Terminal Fallout</h1>
    <div class="buttons">
      <button onclick="window.location.href='php/game.php'" accesskey="G">Game</button>
      <button onclick="window.location.href='php/ranking.php'" accesskey="R">Ranking</button>
    </div>
  </div>
  
  <?php
  if (!empty($_POST["name"]) || !empty($_POST["failedAttempts"]) || !empty($_POST["gameTime"])) {
    $record = htmlspecialchars($_POST["name"]) . ";" . $_POST["failedAttempts"] . ";" . $_POST["gameTime"] . "\n";
    $fileRankingData = './resources/rankingData.txt';
    if (file_exists($fileRankingData)) {
      $previousData = file_get_contents($fileRankingData);
    } else {
      $previousData = '';
    }
    file_put_contents($fileRankingData, "$previousData$record");

    //Prevent multiple entry of the same data if the user reload the page
    header('Location: ./index.php');
  }
  ?>
</body>

</html>