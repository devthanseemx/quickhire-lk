$(document).ready(function () {
    const $form = $('#registration-form');
    if ($form.length === 0) return;

    const $fields = {
        fullName: $('#full-name'),
        username: $('#username'),
        email: $('#email'),
        password: $('#password'),
    };

    const $errors = {
        fullName: $('#fullname-error'),
        username: $('#username-error'),
        email: $('#email-error'),
        password: $('#password-error'),
    };

    $form.on('submit', function (event) {
        event.preventDefault();
        if (!validateFields()) return;

        const formData = new FormData(this);
        $.ajax({
            url: '../../src/authentication/register-api.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: handleServerResponse,
            error: function () {
                showToast('A network error occurred. Please try again.', 'error');
            }
        });
    });

    function handleServerResponse(data) {
        if (data.status === 'success') {
            showToast(data.message, 'success');
            setTimeout(function () {
                window.location.href = '../../src/authentication/login.php';
            }, 2500);
        } else {
            if (data.message.includes('username')) {
                showError($fields.username, $errors.username, data.message);
            } else if (data.message.includes('email')) {
                showError($fields.email, $errors.email, data.message);
            } else {
                showToast(data.message, 'error');
            }
        }
    }

    function validateFields() {
        let isValid = true;
        $.each($fields, function (key, $input) {
            hideError($input, $errors[key]);
        });

        if ($fields.fullName.val().trim() === '') {
            showError($fields.fullName, $errors.fullName, 'This field is required.');
            isValid = false;
        }
        if ($fields.username.val().trim() === '') {
            showError($fields.username, $errors.username, 'This field is required.');
            isValid = false;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($fields.email.val())) {
            showError($fields.email, $errors.email, 'Please enter a valid email.');
            isValid = false;
        }
        if ($fields.password.val().trim().length < 6) {
            showError($fields.password, $errors.password, 'Password must be at least 6 characters.');
            isValid = false;
        }

        return isValid;
    }

    function showError($input, $errorElement, message) {
        $errorElement.text(message).removeClass('hidden');
        $input.addClass('border-red-500').focus();
    }

    function hideError($input, $errorElement) {
        $errorElement.addClass('hidden');
        $input.removeClass('border-red-500');
    }

    // --- Password Visibility Toggle ---
    $('#togglePassword').on('click', function () {
        const $passwordField = $fields.password;
        const type = $passwordField.attr('type') === 'password' ? 'text' : 'password';
        $passwordField.attr('type', type);
        $(this).toggleClass('bi-eye bi-eye-slash');
    });

    // --- Animation on Page Load ---
    const $leftImage = $('.hidden.md\\:block');
    const $title = $('.text-3xl.font-bold');
    const $subtitle = $('.custom-margin');
    const $formElements = $('#registration-form > *, .my-8, .mt-6');

    if ($leftImage.length) {
        $leftImage.addClass('animate-slide-in-from-left');
    }
    if ($title.length) {
        $title.addClass('animate-slide-in-from-bottom').css('animation-delay', '0.2s');
    }
    if ($subtitle.length) {
        $subtitle.addClass('animate-slide-in-from-bottom').css('animation-delay', '0.3s');
    }
    $formElements.each(function (index) {
        $(this).addClass('animate-slide-in-from-bottom')
            .css('animation-delay', (0.4 + index * 0.1) + 's');
    });
});