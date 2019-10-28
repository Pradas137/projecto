<?php
define("CHAR_TOTAL", 408);
$arrayWords = require('./loadDictionary.php');
$copyArrayWords = $arrayWords;
$lengthWord = strlen($arrayWords[0]);
$arraySymbols = [",", "`", "!", "@", "#", "$", "%", "^", "&", "*", "?", "\\", "|", "/", ":", ";", "+", "="];
$arrayOpenBrackets = ["<", "(", "[", "{"];
$arrayCloseBrackets = [">", ")", "]", "}"];

//Get 6 random positions to put our random words without overlapping
$arrayWordsPosition = [];
while (count($arrayWordsPosition) < 6) {
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

//Geneate 3 random helps
$arrayHelps = [];
while (count($arrayHelps) < 3) {
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

//Generate 3 random position to place our help string
$arrayHelpsPosition = [];
$index = 0;
while (count($arrayHelpsPosition) < 3) {
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
while (strlen($stringDump) < CHAR_TOTAL) {
    $currentPos = strlen($stringDump);
    if (in_array($currentPos, $arrayWordsPosition)) {
        $stringDump .= array_shift($copyArrayWords);
    } else if (in_array($currentPos, $arrayHelpsPosition)) {
        $index = array_search($currentPos, $arrayHelpsPosition);
        $stringDump .= $arrayHelps[$index];
    } else {;
        if( in_array((floor(strlen($stringDump) / 12)), $busyRows)){
            $stringDump .= $arraySymbols[rand(0, count($arraySymbols) - 1)];
        }else{
            if (floor(strlen($stringDump) / 12)%2 == 0) {
                $auxArraySymbol = array_merge($arraySymbols,$arrayCloseBrackets);
                $stringDump .= $auxArraySymbol[rand(0, count($auxArraySymbol) - 1)];
            }else{
                $auxArraySymbol = array_merge($arraySymbols,$arrayOpenBrackets);
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
echo "<div id='root'>$firstCol$stringDump$thirdCol";
