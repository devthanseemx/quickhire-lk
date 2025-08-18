document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registration-form');
    if (!form) return;

    const fields = {
        fullName: document.getElementById('full-name'),
        username: document.getElementById('username'),
        email: document.getElementById('email'),
        password: document.getElementById('password'),
    };

    const errors = {
        fullName: document.getElementById('fullname-error'),
        username: document.getElementById('username-error'),
        email: document.getElementById('email-error'),
        password: document.getElementById('password-error'),
    };

    // --- Form Submission Handler ---
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        if (!validateFields()) return;

        const formData = new FormData(form);
        fetch('register-api.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => handleServerResponse(data))
            .catch(error => {
                console.error('Fetch Error:', error);
                showToast('A network error occurred. Please try again.', 'error');
            });
    });

    /**
     * Handles the response from the server after the AJAX call.
     * @param {object} data The JSON response from the server.
     */
    function handleServerResponse(data) {
        if (data.status === 'success') {
            showToast(data.message, 'success');
            setTimeout(() => { window.location.href = '../authentication/login.php'; }, 3500);
        } else {
            if (data.message.includes('username')) {
                showError(fields.username, errors.username, data.message);
            } else if (data.message.includes('email')) {
                showError(fields.email, errors.email, data.message);
            } else {
                showToast(data.message, 'error');
            }
        }
    }

    /**
     * Validates fields and shows/hides errors below the inputs.
     * @returns {boolean}
     */
    function validateFields() {
        let isValid = true;
        Object.keys(fields).forEach(key => hideError(fields[key], errors[key]));

        if (fields.fullName.value.trim() === '') { showError(fields.fullName, errors.fullName, 'This field is required.'); isValid = false; }
        if (fields.username.value.trim() === '') { showError(fields.username, errors.username, 'This field is required.'); isValid = false; }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fields.email.value)) { showError(fields.email, errors.email, 'Please enter a valid email.'); isValid = false; }
        if (fields.password.value.trim().length < 6) { showError(fields.password, errors.password, 'Password must be at least 6 characters.'); isValid = false; }

        return isValid;
    }

    function showError(input, errorElement, message) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
        input.classList.add('border-red-500');
        input.focus();
    }

    function hideError(input, errorElement) {
        errorElement.classList.add('hidden');
        input.classList.remove('border-red-500');
    }

    // --- Password Visibility Toggle ---
    const togglePasswordIcon = document.getElementById('togglePassword');
    togglePasswordIcon.addEventListener('click', function () {
        const passwordField = fields.password;
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Select the elements to animate
    const leftImage = document.querySelector(".hidden.md\\:block");
    const title = document.querySelector(".text-3xl.font-bold");
    const subtitle = document.querySelector(".custom-margin");
    const formElements = Array.from(document.querySelectorAll("#registration-form > *, .my-8, .mt-6"));

    // Animate the left image
    if (leftImage) {
        leftImage.classList.add('animate-slide-in-from-left');
    }

    // Animate the title with a delay
    if (title) {
        title.classList.add('animate-slide-in-from-bottom');
        title.style.animationDelay = '0.2s';
    }

    // Animate the subtitle with a slightly longer delay
    if (subtitle) {
        subtitle.classList.add('animate-slide-in-from-bottom');
        subtitle.style.animationDelay = '0.3s';
    }

    // Animate each form element with a staggered delay
    formElements.forEach((element, index) => {
        element.classList.add('animate-slide-in-from-bottom');
        // Start the delay after the title/subtitle and increment for each element
        element.style.animationDelay = `${0.4 + index * 0.1}s`;
    });
});