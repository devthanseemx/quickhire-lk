$(document).ready(function () {
    const $form = $('#login-form');
    if ($form.length === 0) return;

    const $fields = {
        username: $('#username'),
        password: $('#password'),
    };

    const $errors = {
        username: $('#username-error'),
        password: $('#password-error'),
    };

    $form.on('submit', function (event) {
        event.preventDefault();

        // Hide previous errors
        $.each($fields, function (key, $input) {
            hideError($input, $errors[key]);
        });

        let isValid = true;

        if ($fields.username.val().trim() === '') {
            showError($fields.username, $errors.username, 'Username is required.');
            isValid = false;
        }
        if ($fields.password.val().trim() === '') {
            showError($fields.password, $errors.password, 'Password is required.');
            isValid = false;
        }

        if (!isValid) return;

        // AJAX login
        const formData = new FormData($form[0]);
        $.ajax({
            url: 'login-api.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data) {
                if (data.status === 'success') {
                    setTimeout(function () {
                        window.location.href = data.redirect;
                    }, 200);
                } else {
                    if (data.field === 'username') {
                        showError($fields.username, $errors.username, data.message);
                    } else if (data.field === 'password') {
                        showError($fields.password, $errors.password, data.message);
                    } else {
                        showToast(data.message, 'error');
                    }
                }
            },
            error: function () {
                showToast('A network error occurred. Please try again.', 'error');
            }
        });
    });

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
    const $subtitle = $('.text-gray-500.text-center.mb-20');
    const $formElements = $('#login-form > *, .my-8, .mt-6');

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