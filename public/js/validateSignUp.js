// Get all the values of the sign Up
const firstName = document.getElementById('name');
const lastName = document.getElementById('lastName');
const email = document.getElementById('userEmail');
const password = document.getElementById('userPassword');
const address1 = document.getElementById('userAdress1');
const address2 = document.getElementById('userAdress2');
const country = document.getElementById('userCountry');
const city = document.getElementById('userCity');
const postalCode = document.getElementById('usersPostalCode');
const phone = document.getElementById('userPhone');

// Function to validate the email
const isValidEmail = (email) => {
    let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailPattern.test(email);
}

// Function to validate the phone number
const isValidPhoneNumber = (phone) => {
    let phonePattern = /^(\+\d{1,2}\s?)?(\d{1,10}[-\.\s]?)?\d{1,10}[-\.\s]?\d{1,10}$/;
    return phonePattern.test(phone);
}

// Function to validate the postal code
const isValidPostalCode = (postalCode) => {
    const postalPattern = /^(\d{4,5}|[a-zA-Z]\d[a-zA-Z] ?\d[a-zA-Z]\d)$/i;
    return postalPattern.test(postalCode);
}

// Function to validate the size of the password
const isPasswordTooShort = (password) => password.length < 8;

// Function to validate to show the alert and activate the prevent default to not send the form
const showAlert = (message, event) => {
    alert(message);
    event.preventDefault();
}

// Validate user registration form fields
function validateForm(event) {
    // Check if any essential field is empty
    if (
        !firstName.value.trim() ||
        !lastName.value.trim() ||
        !email.value.trim() ||
        !password.value.trim() ||
        !address1.value.trim() ||
        !address2.value.trim() ||
        !country.value.trim() ||
        !city.value.trim() ||
        !postalCode.value.trim() ||
        !phone.value.trim()
    ) {
        showAlert('Please complete all fields.', event);
    } else if (isPasswordTooShort(password.value)) {
        // Check if the password is too short
        showAlert('Please enter a longer password (More than 8 digits).', event);
    } else if (!isValidEmail(email.value)) {
        // Check if the email is valid
        showAlert('Please enter a valid email.', event);
    } else if (!isValidPostalCode(postalCode.value)) {
        // Check if the postal code is valid
        showAlert('Please enter a valid postal code.', event);
    } else if (!isValidPhoneNumber(phone.value)) {
        // Check if the phone number is valid, including the country code
        showAlert('Please enter a valid phone number, including the country code.', event);
    } else if (country.value === "") {
        // Check if a country is selected
        showAlert("You must select a country.", event);
    }
}

// Get the form and add the event when the form is submitted
const form = document.getElementById('registerForm');
form.addEventListener('submit', validateForm);
