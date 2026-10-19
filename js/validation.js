// Standalone Client-Side Validation Rules

function validateLoginForm() {
    let valid = true;
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');

    if (emailError) emailError.classList.add('hidden');
    if (passwordError) passwordError.classList.add('hidden');

    if (!email.endsWith('@gmail.com')) {
        if (emailError) {
            emailError.textContent = "Email must end with @gmail.com";
            emailError.classList.remove('hidden');
        }
        valid = false;
    }

    if (password === "") {
        if (passwordError) {
            passwordError.textContent = "Password cannot be empty.";
            passwordError.classList.remove('hidden');
        }
        valid = false;
    }

    return valid;
}

function validateRegisterForm() {
    let valid = true;
    const name = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const password = document.getElementById('password').value;

    const nameError = document.getElementById('nameError');
    const emailError = document.getElementById('emailError');
    const phoneError = document.getElementById('phoneError');
    const passwordError = document.getElementById('passwordError');

    if (nameError) nameError.classList.add('hidden');
    if (emailError) emailError.classList.add('hidden');
    if (phoneError) phoneError.classList.add('hidden');
    if (passwordError) passwordError.classList.add('hidden');

    // No numbers on Name
    if (/\d/.test(name) || name === "") {
        if (nameError) {
            nameError.textContent = "Name cannot contain numbers or be blank.";
            nameError.classList.remove('hidden');
        }
        valid = false;
    }

    // Only accept @gmail.com
    if (!email.endsWith('@gmail.com')) {
        if (emailError) {
            emailError.textContent = "Email must strictly end with @gmail.com";
            emailError.classList.remove('hidden');
        }
        valid = false;
    }

    // Must start with 09 and contain exactly 11 digits
    const phoneRegex = /^09\d{9}$/;
    if (!phoneRegex.test(phone)) {
        if (phoneError) {
            phoneError.textContent = "Phone number must start with '09' and be exactly 11 digits.";
            phoneError.classList.remove('hidden');
        }
        valid = false;
    }

    // Password rules: Letters, special chars, and numbers
    const passRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passRegex.test(password)) {
        if (passwordError) {
            passwordError.textContent = "Password must have letters, numbers, special characters, & 8+ length.";
            passwordError.classList.remove('hidden');
        }
        valid = false;
    }

    return valid;
}