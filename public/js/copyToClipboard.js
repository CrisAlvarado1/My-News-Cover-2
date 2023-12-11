/**
 * Copies the content of the 'accessLink' element to the clipboard
 * and provides user feedback with an alert.
 */
function copyToClipboard() {
    var accessLink = document.getElementById('accessLink');
    var range = document.createRange();
    range.selectNode(accessLink);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);

    document.execCommand('copy');
    window.getSelection().removeAllRanges();
    document.getElementById('copyButton').disabled = true;
}

// Get the 'copyButton' element and attach the 'copyToClipboard' function to the click event
document.addEventListener('DOMContentLoaded', function () {
    const copyButton = document.getElementById('copyButton');
    copyButton.addEventListener('click', copyToClipboard);
});