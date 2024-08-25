function validateForm() {
    var title = document.getElementById('title').value;
    var content = document.getElementById('content').value;
    var titleError = document.getElementById('titleError');
    var contentError = document.getElementById('contentError');
    var isValid = true;

    // Reset error messages
    titleError.innerText = '';
    contentError.innerText = '';

    // Validate title
    if (title.trim() === '') {
        titleError.innerText = 'Title cannot be empty';
        isValid = false;
    }

    // Validate content
    if (content.trim() === '') {
        contentError.innerText = 'Content cannot be empty';
        isValid = false;
    }

    return isValid;
}