window.addEventListener("load", function () {
    // Check for query parameters in the URL and handle specific error messages.
    let queryString = window.location.search;

    if (queryString) {
        queryString = queryString.slice(1);
        let queryParams = queryString.split('=');

        if (queryParams[0] === "error") {
            let errorValue = decodeURIComponent(queryParams[1]);

            // Handle different error messages based on 'errorValue':

            // When the category has related news and the user want to delete
            if (errorValue === "news") {
                alert("This category has related news.");
            }

            // When the user want sign up with the one email is already register
            if (errorValue === "exist") {
                alert("There is already a user with this email.");
            }

            // When the user want sign up with the one email is already register
            if (errorValue === "login") {
                alert("Invalid parameters.");
            }
        }
    }
});