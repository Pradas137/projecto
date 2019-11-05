<?php
session_start();

//Check if there is a previous session, if it exist,use the session if not, it will create a new game
if (!empty($_SESSION["game"])) {
    echo $_SESSION["game"];
} else {
    define("CHAR_TOTAL", 408);
    $arraySymbols = [",", "`", "!", "@", "#", "$", "%", "^", "&", "*", "?", "\\", "|", "/", ":", ";", "+", "="];
    $arrayOpenBrackets = ["<", "(", "[", "{"];
    $arrayCloseBrackets = [">", ")", "]", "}"];

    //Default variables (easy mode)
    $wordLen = "5";
    $numWords = 6;
    $numHelps = 3;

    //Check the parameters of the GET request and modify the game variables according to the game mode
    //Save a variable with the game mode, will be used to insert in the HTML
    $htmlDifficulty = "<input id='difficulty' type='hidden' value='easy'>";
    if (isset($_GET["difficulty"]) and in_array($_GET["difficulty"], ["normal", "hard"])) {
        if ($_GET["difficulty"] == "normal") {
            $numWords = 10;
            $htmlDifficulty = "<input id='difficulty' type='hidden' value='normal'>";
        } else if ($_GET["difficulty"] == "hard") {
            $numHelps = 1;
            $numWords = 12;
            $htmlDifficulty = "<input id='difficulty' type='hidden' value='hard'>";
        }
        $wordLen = "7";
    }

    //Save a variable if the game is in hardcore, will be used to insert in the HTML
    $htmlHardcore = "";
    if (isset($_GET["hardcore"]) and $_GET["hardcore"] == "on") {
        $htmlHardcore = "<input id='hardcore' type='hidden' value='on'>";
    }

    //Import the words from the json
    $jsonArray = json_decode(file_get_contents("../resources/dictionary.json"), true);
    $arrayAllWords = $jsonArray[$wordLen];

    //Select the random words and creates an array
    $arrayWords = [];
    while (count($arrayWords) < $numWords) {
        $randomNum = rand(0, count($arrayAllWords) - 1);
        if (!in_array(strtoupper($arrayAllWords[$randomNum]), $arrayWords)) {
            array_push($arrayWords, strtoupper($arrayAllWords[$randomNum]));
        }
    }

    //Selects a words as password, from the previous array and save it in a variable, will be used to insert in the HTML
    $password = $arrayWords[rand(0, count($arrayWords) - 1)];
    $htmlPassword = "<input id='password' type='hidden' value=\"$password\">";

    //Get the random positions to put our random words without overlapping
    $arrayWordsPosition = [];
    $lengthWord = strlen($arrayWords[0]);
    while (count($arrayWordsPosition) < count($arrayWords)) {
        $randomNum = rand(0, (CHAR_TOTAL - $lengthWord) - 1);
        $engaged = false;
        if ($randomNum < (CHAR_TOTAL / 2) - $lengthWord or $randomNum > (CHAR_TOTAL / 2)) {
            foreach ($arrayWordsPosition as $position) {
                if ($randomNum < ($position + $lengthWord + 2) and $randomNum > ($position - $lengthWord - 2)) {
                    $engaged = true;
                }
            }
            if (!$engaged) {
                array_push($arrayWordsPosition, $randomNum);
            }
        }
    }

    //Establish the occupeied rows
    $busyRows = [];
    foreach ($arrayWordsPosition as $pos) {
        array_push($busyRows, floor($pos / 12));
    }
    array_unique($busyRows);

    //Geneate the random helps
    $arrayHelps = [];
    while (count($arrayHelps) < $numHelps) {
        $randomLen = rand(1, 10);
        $braketType = rand(0, count($arrayOpenBrackets) - 1);
        $help = "";
        while (strlen($help) < $randomLen) {
            $help .= $arraySymbols[rand(0, count($arraySymbols) - 1)];
        }
        //Add the open and close brakets
        $help = $arrayOpenBrackets[$braketType] . $help;
        $help .= $arrayCloseBrackets[$braketType];
        array_push($arrayHelps, $help);
    }

    //Generate the random position to place our help string
    $arrayHelpsPosition = [];
    $index = 0;
    while (count($arrayHelpsPosition) < count($arrayHelps)) {
        $randomRow = rand(0, (CHAR_TOTAL / 12) - 1);
        if (!in_array($randomRow, $busyRows) and !in_array(($randomRow - 1), $busyRows)) {
            $lengthHelpByIndex = strlen($arrayHelps[$index]);
            $rowPos = rand(0, (12 - $lengthHelpByIndex));
            $finalPos = $randomRow * 12 + $rowPos;
            array_push($busyRows, $randomRow);
            array_push($arrayHelpsPosition, $finalPos);
            $index++;
        }
    }

    //Create one string with symbols and our words and helps placed in the space that we created before
    $stringDump = "";
    $copyArrayWords = $arrayWords;
    while (strlen($stringDump) < CHAR_TOTAL) {
        $currentPos = strlen($stringDump);
        if (in_array($currentPos, $arrayWordsPosition)) {
            $stringDump .= array_shift($copyArrayWords);
        } else if (in_array($currentPos, $arrayHelpsPosition)) {
            $index = array_search($currentPos, $arrayHelpsPosition);
            $stringDump .= $arrayHelps[$index];
        } else {;
            if (in_array((floor(strlen($stringDump) / 12)), $busyRows)) {
                $stringDump .= $arraySymbols[rand(0, count($arraySymbols) - 1)];
            } else {
                if (floor(strlen($stringDump) / 12) % 2 == 0) {
                    $auxArraySymbol = array_merge($arraySymbols, $arrayCloseBrackets);
                    $stringDump .= $auxArraySymbol[rand(0, count($auxArraySymbol) - 1)];
                } else {
                    $auxArraySymbol = array_merge($arraySymbols, $arrayOpenBrackets);
                    $stringDump .= $auxArraySymbol[rand(0, count($auxArraySymbol) - 1)];
                }
            }
        }
    }

    //Breack up the line every 12 chars, also divide equally the stringDump in 2 divs (this 2 divs are not opened and closed properly yet)
    $countChar = 0;
    $row = 0;
    for ($pos = 0; $pos < strlen($stringDump); $pos++) {
        if ($countChar == 12) {
            if ($row == 16) {
                $countChar = 0;
                $subString = "</div><div class ='col4'>";

                $firstPartDump = substr($stringDump, 0, $pos);
                $lastPartDump = substr($stringDump, $pos);

                $stringDump = $firstPartDump . $subString . $lastPartDump;
                $pos += strlen($subString) - 1;
            } else {
                $countChar = 0;
                $subString = "<br>";

                $firstPartDump = substr($stringDump, 0, $pos);
                $lastPartDump = substr($stringDump, $pos);

                $stringDump = $firstPartDump . $subString . $lastPartDump;
                $pos += strlen($subString) - 1;
            }
            $row++;
        } else {
            $countChar++;
        }
    }

    //Escape the string to avoid errors when we visualize it in HTML
    $stringDump = htmlspecialchars($stringDump);
    //Unescape the entities that we need like the divs and the BR
    $stringDump = str_replace("&lt;br&gt;", "<br>", $stringDump);
    $stringDump = str_replace("&lt;/div&gt;&lt;div class ='col4'&gt;", "</div><div class ='col4'>", $stringDump);

    //Add <span> to words
    foreach ($arrayWords as $word) {
        for ($i = 0; $i < strlen($word); $i++) {
            if ($i == 0) {  //Only for words that are in the same line
                $repString = "<span id='$word' class='word'>$word</span>";
                $stringDump = str_replace($word, $repString, $stringDump);
            } else {        //For words that are divided in differents lines
                $subString = substr($word, 0, $i) . "<br>" . substr($word, $i);
                $repString = "<span id='$word' class='word'>$subString</span>";
                $stringDump = str_replace($subString, $repString, $stringDump);
            }
        }
    }

    //Add <span> to the helps with the class symbol
    foreach ($arrayHelps as  $help) {
        $helpSpan = "<span id='$help' class='symbol'>" . htmlspecialchars($help) . "</span>";
        $stringDump = str_replace(htmlspecialchars($help), $helpSpan, $stringDump);
    }

    //Open and close the previous <div>
    $stringDump = "<div class ='col2'>" . $stringDump;
    $stringDump .= "</div>";

    //Create the string that simulates a memory access, in hex
    $accesNum = rand(11, 60) * 1000;
    $rows = 0;
    $firstCol = "<div class ='col1'>";
    $thirdCol = "<div class ='col3'>";
    while ($rows < 17) {
        $firstCol .= strtoupper("0x" . dechex($accesNum) . "<br>");
        $accesNum += 12;
        $rows++;
    }
    $firstCol .= "</div>";
    while ($rows < 34) {
        $thirdCol .= strtoupper("0x" . dechex($accesNum) . "<br>");
        $accesNum += 12;
        $rows++;
    }
    $thirdCol .= "</div>";

    //Insert the 'stringDump' and the 'simulated memory columns' into the HTML,
    //The div with id 'root' will be closed in the html
    $htmlGame = "<div id='root'>$firstCol$stringDump$thirdCol";

    //Concate all the information and insert it in the HTML
    $htmlInsert = $htmlDifficulty . $htmlHardcore . $htmlPassword . $htmlGame;
    echo $htmlInsert;

    //Save the game in a session 
    $_SESSION["game"] = $htmlInsert;
}
