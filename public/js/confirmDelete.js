// Get all the delete links on the page
const deleteLinks = document.querySelectorAll('.deleteLink');

// Add a confirmation dialog to each delete link
deleteLinks.forEach(function (deleteLink) {
    deleteLink.addEventListener('click', function (event) {
        if (!confirm('Are you sure you want to delete?')) {
            event.preventDefault();
        }
    });
});