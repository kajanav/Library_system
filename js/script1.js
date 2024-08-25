function validateForm() {
    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();
    var usernameError = document.getElementById('usernameError');
    var passwordError = document.getElementById('passwordError');
    var isValid = true;

    // Reset error messages
    usernameError.innerText = '';
    passwordError.innerText = '';

    // Validate username
    if (username === '') {
        usernameError.innerText = 'Username cannot be empty';
        isValid = false;
    }

    // Validate password
    if (password === '') {
        passwordError.innerText = 'Password cannot be empty';
        isValid = false;
    }

    return isValid;
}