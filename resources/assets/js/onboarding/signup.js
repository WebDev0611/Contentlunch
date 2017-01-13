/*
    Scores a password
 */
function scorePassword(pass) {
    let score = 0;

    if (!pass) {
        return score;
    }

    // award every unique letter until 5 repetitions
    let letters = new Object();
    for (var i=0; i<pass.length; i++) {
        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
        score += 5.0 / letters[pass[i]];
    }

    // bonus points for mixing it up
    const variations = {
        digits: /\d/.test(pass),
        lower: /[a-z]/.test(pass),
        upper: /[A-Z]/.test(pass),
        nonWords: /\W/.test(pass),
    };

    let variationCount = 0;

    for (let check in variations) {
        variationCount += (variations[check] == true) ? 1 : 0;
    }

    score += (variationCount - 1) * 15;
    score = parseInt(score);

    return score > 98 ? 98 : score;
}

/*
    Returns a color based on password strength.
 */
function checkPassStrength(pass) {
    var score = scorePassword(pass);
    if (score > 80)
        return "#77FF77";
    if (score >= 40)
        return "#FFBB77";
    if (score < 40)
        return "#FF7777";
    return "";
}

/*
    Update the Password strength indicator
 */
function updatePasswordStrengthIndicator(pass) {
    $(".input-strength-indicator span").css({
        'background-color': checkPassStrength(pass),
        'width': scorePassword(pass)+"%"
    });
}

$(document).ready(function() {
    $("#password").on("keypress keyup keydown", function() {
        updatePasswordStrengthIndicator($(this).val());
    });
    updatePasswordStrengthIndicator($("#password").val());
});