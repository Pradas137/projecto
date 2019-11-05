<?php
session_start();
//Remove the game session
if (isset($_SESSION["game"])) {
    unset($_SESSION["game"]);
}

//When you lose and click play again
//Redirect to a new game with the same game mode
if (isset($_GET["difficulty"])) {
    $difficulty = $_GET["difficulty"];
    if (isset($_GET["hardcore"])) {
        header("Location: ./game.php?difficulty=$difficulty&hardcore=on");
    } else {
        header("Location: ./game.php?difficulty=$difficulty");
    }
}
//When you win and save a record
//If recive a post method with a record save and reedirect to the ranking highlight this record
if (!empty($_POST["name"]) || !empty($_POST["failedAttempts"]) || !empty($_POST["gameTime"]) || !empty($_POST["gamemode"])) {
    $record = htmlspecialchars($_POST["name"]) . ";" . $_POST["failedAttempts"] . ";" . $_POST["gameTime"] . ";" . $_POST["gamemode"] . "\n";
    $fileRankingData = '../resources/rankingData.txt';
    if (file_exists($fileRankingData)) {
        $previousData = file_get_contents($fileRankingData);
    } else {
        $previousData = '';
    }
    file_put_contents($fileRankingData, "$previousData$record");

    //Save the name in sessions to use in future forms if you win again
    $_SESSION["name"] = $_POST["name"];

    //Reedirect to the ranking sending the same POST
    header('HTTP/1.1 307');
    header('Location: ./ranking.php');
}
