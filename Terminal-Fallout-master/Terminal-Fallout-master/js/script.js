window.addEventListener("load", function () {
    var words = document.getElementsByClassName('word');
    var symbols = document.getElementsByClassName('symbol');
    var prompt = document.getElementById('prompt');
    const passwordValue = document.getElementById('password').value;
    var arrayPrompt = Array(16).fill("<br>");
    var tries = 4;
    var gameRun = true;
    var failedAttempts = 0;
    var startTime = Date.now();

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
            //Randomly select the type of help
            if (Math.random() < 0.5) {
                removeDudWord();
                renewPromptSymbol(symbolId, "REMOVE")
            } else {
                resetAttempts();
                renewPromptSymbol(symbolId, "RESET")
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
        spanToDots(wordId);

        tries--;
        renewAttempts();
        if (tries === 0) {
            lose();
        } else {
            renewPromptWord(wordId, coincidentChar);
        }
    }

    //With the ID of the <span>, converts his content to dots
    function spanToDots(spanID) {
        var targetSpan = document.getElementById(spanID);
        var spanValue;

        //If the <span> is a Symbol, we will use the innerText to get the value,to avoid the escaped text that innerHTML would return
        if (targetSpan.className === "symbol") {
            spanValue = targetSpan.innerText;
            targetSpan.classList.remove('symbol');
            targetSpan.removeEventListener("click", symbolHelp);
            //If the <span> is a word, we will use the innerHTML to get the value that includes '<br>'
        } else if (targetSpan.className === "word") {
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
        document.getElementById("failedAttempts").value = failedAttempts;
        document.getElementById("gameTime").value = finalTime;
        endGame(true)
    }

    function lose() {
        endGame(false);
    }

    function endGame(win) {
        gameRun = false;
        //Hide the gamePanel and show the win or lose panel
        var gamePanel = document.getElementById('gamePanel');
        gamePanel.classList += " hide";

        if (win) {
            var winPanel = document.getElementById('winPanel');
            winPanel.classList = "terminal";
            document.getElementById("rankigForm").classList = "";
        } else {
            var losePanel = document.getElementById('losePanel');
            losePanel.classList = "terminal";
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
        var timeElapsed = new Date(Date.now() - startTime)
            , min = timeElapsed.getUTCMinutes()
            , sec = timeElapsed.getUTCSeconds()
            , ms = timeElapsed.getUTCMilliseconds();

        //Render the time in the HTML
        document.getElementById("display-area").innerHTML =
            (min > 9 ? min : "0" + min) + ":" +
            (sec > 9 ? sec : "0" + sec) + "." +
            (ms > 99 ? ms : ms > 9 ? "0" + ms : "00" + ms);
    };
});