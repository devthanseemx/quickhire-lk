document.addEventListener("DOMContentLoaded", () => {
    // --- Element Selections ---
    const sections = {
        email: document.getElementById("check-email-section"),
        code: document.getElementById("code-section"),
        reset: document.getElementById("reset-section"),
    };
    const forms = {
        sendCode: document.getElementById("send-code-form"),
        verifyCode: document.getElementById("verify-code-form"),
        resetPassword: document.getElementById("reset-password-form"),
    };
    const errors = {
        email: document.getElementById("emailError"),
        code: document.getElementById("codeError"),
        password: document.getElementById("passwordError"),
    };

    const apiUrl = 'forgot-password-action.php';
    let timerInterval;

    // --- Timer Functions ---
    function startTimer(durationInSeconds) {
        const timerDisplay = document.getElementById('timer');
        let time = durationInSeconds;

        clearInterval(timerInterval);
        timerDisplay.classList.remove('hidden');

        const updateTimer = () => {
            if (time < 0) {
                clearInterval(timerInterval);
                timerDisplay.textContent = "Code has expired. Please request a new one.";
                return;
            }
            const minutes = Math.floor(time / 60);
            const seconds = time % 60;
            timerDisplay.textContent = `Code expires in ${minutes}:${seconds.toString().padStart(2, '0')}`;
            time--;
        };

        updateTimer();
        timerInterval = setInterval(updateTimer, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
        const timerDisplay = document.getElementById('timer');
        if (timerDisplay) {
            timerDisplay.classList.add('hidden');
        }
    }

    // --- Helper Functions ---
    const showSection = (sectionName) => {
        Object.values(sections).forEach(section => section.classList.add("hidden"));
        sections[sectionName].classList.remove("hidden");


        if (sectionName === 'code') {
            startTimer(300);
        } else {
            stopTimer(); 
        }
    };

    const showError = (fieldName, message) => {
        errors[fieldName].textContent = message;
        errors[fieldName].classList.remove("hidden");
    };

    const clearErrors = () => {
        Object.values(errors).forEach(error => error.classList.add("hidden"));
    };

    // --- Form Submission Logic ---
    forms.sendCode.addEventListener("submit", (e) => {
        e.preventDefault();
        clearErrors();
        const formData = new FormData(forms.sendCode);
        formData.append('action', 'send_code');

        fetch(apiUrl, {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    showToast(data.message, "success");
                    showSection('code');

                    // --- Trigger email queue sending via AJAX ---
                    fetch('../../src/utils/sendQueuedEmails.php', {
                        method: 'POST'
                    }).catch(err => {
                        console.error('Queue processor failed', err);
                    });

                } else {
                    showError(data.field, data.message);
                }
            })
            .catch(() => showError('email', 'A network error occurred. Please try again.'));
    });


    forms.verifyCode.addEventListener("submit", (e) => {
        e.preventDefault();
        clearErrors();
        const formData = new FormData(forms.verifyCode);
        formData.append('email', document.getElementById('email').value);
        formData.append('action', 'verify_code');

        fetch(apiUrl, {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    showToast(data.message, "success");
                    showSection('reset');
                } else {
                    showError(data.field, data.message);
                }
            })
            .catch(() => showError('code', 'A network error occurred. Please try again.'));
    });

    forms.resetPassword.addEventListener("submit", (e) => {
        e.preventDefault();
        clearErrors();
        const formData = new FormData(forms.resetPassword);
        formData.append('action', 'reset_password');

        fetch(apiUrl, {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    showToast(data.message, "success");
                    setTimeout(() => window.location.href = data.redirect, 2000);
                } else {
                    showError(data.field, data.message);
                }
            })
            .catch(() => showError('password', 'A network error occurred. Please try again.'));
    });

    showSection('email');
});


$(document).ready(function () {
    let imageAnimated = false; 

    function animateSection($section) {
        const $titles = $section.find('h2.text-3xl.font-bold');
        const $subtitles = $section.find('p.text-gray-500');
        const $formElements = $section.find('form > *');
        const $timer = $section.find('.timer-paragraph');

        if (!imageAnimated && $section.attr('id') === 'check-email-section') {
            $('.hidden.md\\:block').addClass('animate-slide-in-from-left');
            imageAnimated = true;
        }

        $titles.each(function (index) {
            $(this).addClass('animate-slide-in-from-bottom')
                .css('animation-delay', (0.2 + index * 0.1) + 's');
        });

        $subtitles.each(function (index) {
            $(this).addClass('animate-slide-in-from-bottom')
                .css('animation-delay', (0.3 + index * 0.1) + 's');
        });

        $formElements.each(function (index) {
            $(this).addClass('animate-slide-in-from-bottom')
                .css('animation-delay', (0.4 + index * 0.1) + 's');
        });

        if ($timer.length) {
            $timer.addClass('animate-slide-in-from-bottom')
                .css('animation-delay', '0.6s');
        }
    }

    animateSection($('#check-email-section'));
    animateSection($('#code-section'));
    animateSection($('#reset-section'));
});
