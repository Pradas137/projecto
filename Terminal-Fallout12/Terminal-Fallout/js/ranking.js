window.addEventListener("load", function () {
    var colorBlindnessActivated = false;
    var colorBlindness = document.getElementById("colorBlindness");

    //Color mode control
    colorBlindness.addEventListener("click", function () {
        if (colorBlindnessActivated) {
            colorBlindnessActivated = false;
            //Change the menu icon
            colorBlindness.innerHTML = "visibility_off";

            //Remove the class of the HTML entities, to get the default style
            document.body.classList.remove("colorBlindness");
            document.body.classList.remove("cBlindness");
            document.getElementById("options").classList.remove("bordercolorBlindness")
        } else {
            colorBlindnessActivated = true;
            //Change the menu icon
            colorBlindness.innerHTML = "visibility";

            //Add the class, to the HTML entities, to apply a special style
            document.body.classList.add("colorBlindness");
            document.body.classList.add("cBlindness");
            document.getElementById("options").classList.add("bordercolorBlindness")
        }
    });
})