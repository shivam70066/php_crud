function validate() {
    // Get form elements
    var nameInput = document.getElementById("name");
    var name = nameInput.value;
    var emailInput = document.getElementById("email");
    var email = emailInput.value;
    var numberInput = document.getElementById("number");
    var number = numberInput.value;
    var locationInput = document.getElementById("location");
    var location = locationInput.value;
    var expiretimeInput = document.getElementById("expire_time");
    var expiretime = expiretimeInput.value;

    // expiretimeInput.style.border = '1px solid red';


    // // Perform validation

    var nameRegex = /^[a-zA-Z ]+$/;

    if (name.trim() === "") {
        nameInput.style.border = '1px solid red';
        return false;
    } else if (!nameRegex.test(name)) {
        nameInput.style.border = '1px solid red';
        return false;
    } else {
        nameInput.style.border = 'none';
    }

    var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (email.trim() === "") {
        emailInput.style.border = '1px solid red';
        return false;
    } else if (!emailRegex.test(email)) {
        emailInput.style.border = '1px solid red';
        return false;
    } else {
        emailInput.style.border = 'none';
    }

    if (number.length !== 10 || isNaN(number)) {
        numberInput.style.border = '1px solid red';
        return false;
    } else {
        numberInput.style.border = 'none';
    }

    if (location.trim() === "") {
        locationInput.style.border = '1px solid red';
        return false;
    }

    if (isNaN(expiretime) || expiretime <= 0 ) {
        expiretimeInput.style.border = '1px solid red';
        return false;
    }

    return true;
}