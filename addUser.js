function validateForm() {
  var nameValid = validateName();
  var emailValid = validateEmail();
  var designationValid = validateDesignation();
  // var confirmPasswordValid = validateConfirmPassword();
  var mobileValid = validateMobileNumber();
  var ageValid = validateAge();

  var genderValid = validateGender();
  var locValid = validateLocation();

  var flag = nameValid && emailValid && mobileValid && designationValid && ageValid && genderValid && locValid;
  if (!flag) {
    document.getElementById("divmsg").style.display = "block";
  }
  return flag;
}




function validateLocation() {
  var countrySelect = document.getElementById("countrySelect");
  var stateSelect = document.getElementById("stateSelect");

  var countryValue = countrySelect.value;
  alert(countryValue);
  var stateValue = stateSelect.value;

  // Check if any of the selects have default value
  if (countryValue == "select" || stateValue == "select") {
    document.getElementById("locmsg").innerHTML =
      "Please enter age between 18 and 100";
      return false;
  } else {
    alert(countryValue);
      return true;
  }
}

function validateGender() {
  var genderOptions = document.getElementsByName('gender');
  console.log(genderOptions)
  var genderError = document.getElementById('genderError');
  var genderSelected = false;

  for (var i = 0; i < genderOptions.length; i++) {
    if (genderOptions[i].checked) {
      genderSelected = true;
      break;
    }
  }

  if (!genderSelected) {
    genderError.textContent = "Please select a gender";
    return false;
  } else {
    genderError.textContent = "";
    return true;
  }
}

function validateAge() {
  // Get the value from the age input field
  console.log("age")
  var ageValue = document.getElementById("age").value;

  // Convert the value to a number
  var age = parseInt(ageValue);

  // Check if age is less than 18 or greater than 100
  if (age < 18 || age > 100 || isNaN(age)) {
    // Invalid age
    document.getElementById("agemsg").innerHTML =
      "Please enter ageeen 18 and 100";
    return false;
  } else {
    // Valid age
    document.getElementById("agemsg").innerHTML = ""; // Clear error message if any
    return true;
  }
}

function validateEmail() {
  var emailInput = document.getElementById("email");
  var email = emailInput.value.trim();
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (email === "") {
    document.getElementById("emailmsg").innerHTML =
      "Please enter an email address.";
    return false;
  } else if (!emailRegex.test(email)) {
    // document.getElementById("emailmsg").innerHTML = "Please enter a valid email address.";
    return false;
  } else {
    // document.getElementById("emailmsg").innerHTML = "";
    return true;
  }
}

function validateName() {
  var nameInput = document.getElementById("name");
  var name = nameInput.value.trim();
  var nameRegex = /^[a-zA-Z ]+$/;

  if (name === "") {
    document.getElementById("namemsg").innerHTML = "Please enter a name.";
    return false;
  } else if (!nameRegex.test(name)) {
    document.getElementById("namemsg").innerHTML =
      "Please enter a valid name(letters and spaces only).";
    return false;
  } else {
    document.getElementById("namemsg").innerHTML = "";
    return true;
  }
}

function validateDesignation() {
  var designationValue = document.getElementById("designation").value;

  // Check if the length of the designation is greater than two
  if (designationValue.length > 1) {
    // Designation is valid
    document.getElementById("designationmsg").innerHTML = "";
    return true;
  } else {
    // Designation is invalid
    document.getElementById("designationmsg").innerHTML =
      "Please Enter a valid Designation";
    return false;
  }
}

function validateConfirmPassword() {
  var passwordInput = document.getElementById("password");
  var confirmPasswordInput = document.getElementById("cpassword");
  var password = passwordInput.value;
  var confirmPassword = confirmPasswordInput.value;

  if (password !== confirmPassword) {
    document.getElementById("cpasswordmsg").innerHTML =
      "Passwords do not match.";
    return false;
  } else {
    document.getElementById("cpasswordmsg").innerHTML = "";
    return true;
  }
}

function validateMobileNumber() {
  var mobileInput = document.getElementById("number");
  var mobileNumber = mobileInput.value;

  if (mobileNumber.length !== 10 || isNaN(mobileNumber)) {
    document.getElementById("numbermsg").innerHTML =
      "Please enter a valid mobile number.";
    return false;
  } else {
    document.getElementById("numbermsg").innerHTML = "";
    return true;
  }
}



