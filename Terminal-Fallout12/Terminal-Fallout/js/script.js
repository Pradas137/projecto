window.addEventListener("load", function () {
    var words = document.getElementsByClassName('word');
    var symbols = document.getElementsByClassName('symbol');
    var prompt = document.getElementById('prompt');
    const passwordValue = document.getElementById('password').value;
    var helpsType1 = 0;
    var helpsType2 = 0;
    console.log(passwordValue);
    var arrayPrompt = Array(16).fill("<br>");
    var tries = 4;
    var gameRun = true;
    var failedAttempts = 0;
    var startTime = Date.now();

    //Hardcore game
    var lastCoincidences = 0;
    var hardcore = false;
    var hardcoreElement = document.getElementById("hardcore");
    if (hardcoreElement != null) {
        if (hardcoreElement.value === "on") {
            hardcore = true;
        }
    }

    //Show initial attempts
    renewAttempts();
    //Start chronometer
    setInterval(clockRunning, 10);

    //Add event listener to all the words
    for (let index = 0; index < words.length; index++) {
        words[index].addEventListener("click", checkPassword);
    }
    //Add event listener to all the symbol-helps
    for (let index = 0; index < symbols.length; index++) {
        symbols[index].addEventListener("click", symbolHelp);
    }

    function symbolHelp(event) {
        var symbolId = event.target.id;
        if (gameRun) {
            spanToDots(symbolId);
            //Randomly select the type of help, always at least 1 type of each
            if (helpsType1 + helpsType2 === 2 && helpsType1 != helpsType2) {
                (helpsType1 > helpsType2) ? helpType2(symbolId) : helpType1(symbolId);
            } else {
                (Math.random() < 0.5) ? helpType1(symbolId) : helpType2(symbolId);
            }
        }
    }

    function checkPassword(event) {
        if (gameRun) {
            if (event.target.id === passwordValue) {
                var timeDiff = new Date() - startTime;  //in ms
                win(timeDiff);
            } else {
                failedAttempts++;
                checkCoincidentChar(event.target.id);
            }
        }
    }

    function checkCoincidentChar(wordId) {
        var coincidentChar = 0;
        for (let i = 0; i < wordId.length; i++) {
            if (wordId[i] === passwordValue[i]) {
                coincidentChar++;
            }
        }
        if (hardcore && lastCoincidences > coincidentChar) {
            endGame(false);
        } else {
            lastCoincidences = coincidentChar;
            spanToDots(wordId);
            tries--;
            renewAttempts();
            if (tries === 0) {
                lose();
            } else {
                renewPromptWord(wordId, coincidentChar);
            }
        }
    }

    //With the ID of the <span>, converts his content to dots
    function spanToDots(spanID) {
        var targetSpan = document.getElementById(spanID);
        var spanValue;

        //If the <span> is a Symbol, we will use the innerText to get the value,to avoid the escaped text that innerHTML would return
        if (targetSpan.classList.contains("symbol")) {
            spanValue = targetSpan.innerText;
            targetSpan.classList.remove('symbol');
            targetSpan.removeEventListener("click", symbolHelp);
            //If the <span> is a word, we will use the innerHTML to get the value that includes '<br>'
        } else if (targetSpan.classList.contains("word")) {
            spanValue = targetSpan.innerHTML;
            targetSpan.classList.remove('word');
            targetSpan.removeEventListener("click", checkPassword);
        }

        var stringDots = "";
        for (let index = 0; index < spanValue.length; index++) {
            if (spanValue[index] === "<" && spanValue[index + 1] === "b") {
                stringDots += "<br>";
                index += 3;
            } else {
                stringDots += ".";
            }
        }
        targetSpan.innerHTML = stringDots;
    }

    //Draw the charTrie as many times as attempts remain
    function renewAttempts() {
        triesElement = document.getElementById('tries');
        charTrie = "█ ";    //This char represents an attempt
        attemptMarker = "";
        for (let index = 0; index < tries; index++) {
            attemptMarker += charTrie;
        }
        triesElement.innerHTML = attemptMarker;

    }

    //Fill the failed attempts and the game time, in the HTML form, to send all the data for the ranking
    function win(finalTime) {
        //Get de gamemode
        var gamemodeElement = document.getElementById("difficulty");
        if (gamemodeElement != null) {
            gamemode = gamemodeElement.value;
        } else {
            gamemode = "easy";
        }
        document.getElementById("failedAttempts").value = failedAttempts;
        document.getElementById("gameTime").value = finalTime;
        document.getElementById("gamemode").value = gamemode;
        endGame(true)
    }

    function lose() {
        endGame(false);
    }

    function endGame(win) {
        gameRun = false;
        //Hide the gamePanel and show the win or lose panel
        document.getElementById('gamePanel').classList.add("hide");

        if (win) {
            document.getElementById('winPanel').classList.remove("hide");
            document.getElementById("rankigForm").classList.remove("hide");
        } else {
            document.getElementById('losePanel').classList.remove("hide");
        }
    }

    //Get the wordID and how many chars are coincident and print this information in the prompt,
    //this prompt is an array with 16 holes, and works like a queue
    function renewPromptWord(wordId, coincidentChar) {
        promptQueue(wordId);
        promptQueue("Entry Denied");
        promptQueue("Likeness=" + coincidentChar);
        prompt.innerHTML = arrayPrompt.join("");
    }

    function renewPromptSymbol(symbolId, helpType) {
        promptQueue(symbolId);
        if (helpType === "RESET") {
            promptQueue("Tries Reset.");
        } else {
            promptQueue("Dud Removed.");
        }
        prompt.innerHTML = arrayPrompt.join("");
    }

    //Removes the frist element from the array and one in the queue
    function promptQueue(value) {
        arrayPrompt.shift();
        arrayPrompt.push(">" + value + "<br>");
    }

    function helpType1(symbolId) {
        helpsType1++;
        removeDudWord();
        renewPromptSymbol(symbolId, "REMOVE")
    }

    function helpType2(symbolId) {
        helpsType2++;
        resetAttempts();
        renewPromptSymbol(symbolId, "RESET")
    }

    function removeDudWord() {
        var currentWords = document.getElementsByClassName('word');
        var spanPassword = document.getElementById(passwordValue);
        if (currentWords.length > 1) {
            var posPassInArray;
            for (let index = 0; index < currentWords.length; index++) {
                if (currentWords[index] == spanPassword) {
                    posPassInArray = index;
                }
            }
            var randomDudWord;
            while (randomDudWord === undefined) {
                //Generate a random number to select a word (not the password) and delete it
                var randomNum = Math.floor(Math.random() * currentWords.length);
                if (randomNum != posPassInArray) {
                    randomDudWord = randomNum;
                }
            }
            spanToDots(currentWords[randomDudWord].id);
        }
    }

    function resetAttempts() {
        tries = 4;
        renewAttempts();
    }

    //Calcualtes the time
    function clockRunning() {
        var timeElapsed = new Date(Date.now() - startTime),
            min = timeElapsed.getUTCMinutes(),
            sec = timeElapsed.getUTCSeconds(),
            ms = timeElapsed.getUTCMilliseconds();

        //Render the time in the HTML
        document.getElementById("display-area").innerHTML =
            (min > 9 ? min : "0" + min) + ":" +
            (sec > 9 ? sec : "0" + sec) + "." +
            (ms > 99 ? ms : ms > 9 ? "0" + ms : "00" + ms);
    };
});