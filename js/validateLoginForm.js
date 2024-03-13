function validateForm() {

    var emailValid = validateEmail();
    var passwordValid = validatePassword();

    return emailValid && passwordValid;
}


function validateEmail() {
    var emailInput = document.getElementById("email");
    var email = emailInput.value.trim();
    var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (email === "") {
        console.log("empty")
        document.getElementById("emailmsg").innerHTML = "Please enter an email address.";
        return false;
    } else if (!emailRegex.test(email)) {
        document.getElementById("emailmsg").innerHTML = "Please enter a valid email.";
        return false;
    } else if (email != "" && emailRegex.test(email)) {
        
        return true;
    }
}



function validatePassword() {
    var passwordInput = document.getElementById("password");
    var password = passwordInput.value;



    if (password.length < 1) {
        document.getElementById("passwordmsg").innerHTML = "Please enter a password.";
        return false;
    } else {
        document.getElementById("passwordmsg").innerHTML = "";
        return true;
    }
}


$(document).ready(function() {
    var debounceTimer;
    
    $('#email').on('input', function() {
        clearTimeout(debounceTimer); // Clear previous timer
        
        var email = $(this).val();
        var emailInput = document.getElementById("email");
        var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        if (!emailRegex.test(email)) {
            console.log("regex11")
            document.getElementById("emailmsg").innerHTML = "Please enter a valid email.";
            return;
        }
        
        // Set a timer to delay the AJAX request by 1 second
        debounceTimer = setTimeout(function() {
            $.ajax({
                url: 'loginemail.php',
                type: 'POST',
                data: {
                    email: email
                },
                success: function(response) {
                    $('#emailmsg').html(response);
                },
                error: function(xhr, status, error) {
                    // Handle error here
                    console.error(xhr.responseText);
                    // For example, you can display an error message
                    $('#emailmsg').html("An error occurred while processing your request. Please try again later.");
                }
            });
        }, 300); // 1 second delay
    });
});
