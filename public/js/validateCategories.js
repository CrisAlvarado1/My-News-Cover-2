// Get all the values of the categories
const categoryName = document.getElementById('categoryName');
const categoryId = document.getElementById('categoryId');

// Function to show an alert message and prevent the form submission
const showAlert = (message, event) => {
    alert(message);
    event.preventDefault();
}

// Validates the form fields for category information
function validateForm(event) {
    // Check if the category name field is empty or undefined
    if (!categoryName) {
        showAlert("Something went wrong, please try again.", event);
    } else if (categoryName.value.trim() === "" || !categoryName.value) {
        showAlert("Please complete all fields.", event);
    }

    // Check if the category ID field is empty or undefined
    if (categoryId && (categoryId.value.trim() === "" || !categoryId.value)) {
        showAlert("Something went wrong, please try again.", event);
    }
}

// Get the form and add the event
let form = document.querySelector('form');
form.addEventListener('submit', validateForm);