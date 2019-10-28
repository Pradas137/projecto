<?php
    //Create an array with the words in the file
    $file = fopen('../resources/dictionary.txt','r');
    while ($line = fgets($file)) {
        $arrayAllWords[] = trim(strtoupper($line));
    }
    fclose($file);

    //Select 6 random words and creates an array
    $arrayRandomWords = [];
    while(count($arrayRandomWords)<6){
        $randomNum = rand(0, count($arrayAllWords)-1);
        if(!in_array($arrayAllWords[$randomNum],$arrayRandomWords)){
            array_push($arrayRandomWords,$arrayAllWords[$randomNum]);
        }
    }

    //Selects a words as password, from the previous array and insert in the HTML 
    $password = $arrayRandomWords[rand(0,count($arrayRandomWords)-1)];
    echo "<input id='password' type='hidden' value=\"$password\">";

    return $arrayRandomWords;
