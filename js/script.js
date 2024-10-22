const form = document.querySelector('#form')
const username = document.querySelector('#username');
const email = document.querySelector('#email');
const password = document.querySelector('#password');

form.addEventListener('submit', (e) => {

    if (!validateInputs()) {
        e.preventDefault();
    }
})

function validateInputs() {
    const usernameVal = username.value.trim()
    const emailVal = email.value.trim()
    const passwordVal = password.value.trim();
    let success = true

    if (usernameVal === '') {
        success = false;
        setError(username, 'Username is required')
    } else {
        setSuccess(username)
    }

    // Email validation
    if (emailVal === '') {
        success = false;
        setError(email, 'Email is required');
    } else if (!isValidEmail(emailVal)) {
        success = false;
        setError(email, 'Please provide a valid email');
    } else {
        setSuccess(email);
    }


    if (passwordVal === '') {
        success = false;
        setError(password, 'Password is required')
    } else if (passwordVal.length < 8) {
        success = false;
        setError(password, 'Password must be atleast 8 characters long')
    } else {
        setSuccess(password)
    }
    return success;

}
// Email validation function (simple regex check)
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

//element - password, msg- pwd is reqd
function setError(element, message) {
    const inputGroup = element.parentElement;
    const errorElement = inputGroup.querySelector('.error')

    errorElement.innerText = message;
    inputGroup.classList.add('error')
    inputGroup.classList.remove('success')
}

function setSuccess(element) {
    const inputGroup = element.parentElement;
    const errorElement = inputGroup.querySelector('.error')

    errorElement.innerText = '';
    inputGroup.classList.add('success')
    inputGroup.classList.remove('error')
}