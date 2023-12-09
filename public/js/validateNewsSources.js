// Get all the values of the news sources
const nameSource = document.getElementById('nameSource');
const rssUrl = document.getElementById('rss');
const category = document.getElementById('category');
const userId = document.getElementById('user');
const categoryId = document.getElementById('categoryId');

// Function to validate if is a URL
const isValidURL = (url) => {
    const pattern = new RegExp("^(https?|ftp)://");
    return pattern.test(url);
}

// Function to show an alert message and prevent the form submission
const showAlert = (message, event) => {
    alert(message);
    event.preventDefault();
}

// Validate form fields for news source information
function validateForm(event) {
    // Check if essential values are missing
    if (!userId.value || !userId || !category.value) {
        showAlert("Something went wrong, please try again.", event);
    } else if (nameSource.value.trim() === "" || rssUrl.value.trim() === "" ||
        userId.value.trim() === "") {
        // Check for empty fields
        showAlert("Please complete all fields.", event);
    } else if (!isValidURL(rssUrl.value)) {
        // Check for a valid RSS URL
        showAlert("The RSS URL is not a valid URL.", event);
    } else if (category.value === "") {
        // Check if a category is selected
        showAlert("You must select a category.", event);
    }

    // Validates the category when edited.
    if (categoryId && (categoryId.value.trim() === "" || !categoryId.value)) {
        showAlert("Something went wrong, please try again.", event);
    }
}

// Get the form and add the event
let form = document.getElementById('newsSourcesForm');
form.addEventListener('submit', validateForm);