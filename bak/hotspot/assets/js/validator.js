//==========================================================================================================================================================================================================================================================================================================================================
//
//  ####    #####  #####   ##   ##   ####     ####    ##  ##     ##   ####
//  ##  ##  ##     ##  ##  ##   ##  ##       ##       ##  ####   ##  ##
//  ##  ##  #####  #####   ##   ##  ##  ###  ##  ###  ##  ##  ## ##  ##  ###
//  ##  ##  ##     ##  ##  ##   ##  ##   ##  ##   ##  ##  ##    ###  ##   ##
//  ####    #####  #####    #####    ####     ####    ##  ##     ##   ####
//
//==========================================================================================================================================================================================================================================================================================================================================

function trace(msg) {
    traceOn = false;
    if (traceOn) {
        return console.log(msg);
    }
}

const _FULL_TEXT_PATTERN = /^[a-z A-Z.]+$/i;
const _NUMBER_PATTERN = /^[0-9.]+$/i;
const _ALPHA_NUMERIC_PATTERN = /^[a-z  A-Z 0-9.,/_-]+$/i;
const _USERNAME_PATTERN = /^[a-z  A-Z 0-9.@\-/_]+$/i;
const _PASSWORD_PATTERN = /^[a-z  A-Z 0-9.,!@#$%^&*()_"'{}+=()?\-\[\]/]+$/i;
const _MOBILE_NUMBER_PATTERN = /^(?:\+88|88)?(01[3-9]\d{8})$/;
const _EMAIL_PATTERN = /(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/i;

$(document).ready(function () {
    setUpValidation();
});

function setUpValidation() {
    let inputs = $(".validate");
    if (inputs.length > 0) {
        $(':input[type="submit"]').prop('disabled', true);
        for (let i = 0; i < inputs.length; i++) {
            validateAndSetClass(inputs[i]);
            addEventListenerToInput(inputs[i]);
        }
    }
    return true;
}

function validateAndSetClass(input) {

    if (input.type === "text") {
        InputAndSetClass(input, _FULL_TEXT_PATTERN);
    }
    if (input.type === "password") {
        InputAndSetClass(input, _PASSWORD_PATTERN);
    }
    if (input.id === "password") {
        InputAndSetClass(input, _PASSWORD_PATTERN);
    }

    if (input.tagName === "SELECT") {
        InputAndSetClass(input, _ALPHA_NUMERIC_PATTERN);
    }
    if (input.type === "number") {
        InputAndSetClass(input, _NUMBER_PATTERN);
    }
    if (input.type === "email") {
        InputAndSetClass(input, _EMAIL_PATTERN);
    }

    if (input.id === "mobile") {
        InputAndSetClass(input, _MOBILE_NUMBER_PATTERN);
    }
    if (input.id === "alphaNumeric") {
        InputAndSetClass(input, _ALPHA_NUMERIC_PATTERN);
    }
    if (input.id === "username") {
        InputAndSetClass(input, _USERNAME_PATTERN);
    }
    return input;
}

function InputAndSetClass(input, pattern) {

    if (pattern.test(input.value)) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');

    } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
    }
}

function addEventListenerToInput(input) {
    input.addEventListener('input', function () {
        validateAndSetClass(input);
        verifyValidation(input);
    });
}


function verifyValidation(i) {
    let inputs = $(".validate");
    let validInputArray = $(".is-valid");
    if (validInputArray.length == inputs.length) {
        $(':input[type="submit"]').removeAttr("disabled");

    } else {
        $(':input[type="submit"]').prop('disabled', true);
    }
}

