function toggleVisibility(inputId, icon) {
    const input = document.getElementById(inputId);
    
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const emailInput  = document.getElementById('emailInput');
    const emailMsg    = document.getElementById('emailCheckMsg');
    const roleSelect  = document.getElementById('usertype');
    const submitBtn   = document.querySelector('.btn-signup');
    const signupForm  = document.querySelector('form');
    let timer         = null;
    let emailTaken    = false;

    function checkEmail() {
        clearTimeout(timer);
        const email = emailInput.value.trim();
        const role  = roleSelect.value;

        emailMsg.textContent = '';
        emailMsg.className   = '';
        emailTaken = false;
        submitBtn.disabled = false;

        if (email === '') return;

        timer = setTimeout(function () {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../../Controller/checkEmailController.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const res = JSON.parse(xhr.responseText);
                        if (res.exists) {
                            emailMsg.textContent = '✗ This email is already registered.';
                            emailMsg.className   = 'error-text';
                            emailTaken = true;
                            submitBtn.disabled = true;
                        } else {
                            emailMsg.textContent = '✓ Email is available.';
                            emailMsg.className   = 'success-text';
                            emailTaken = false;
                            submitBtn.disabled = false;
                        }
                    } catch (e) {
                        emailMsg.textContent = '';
                        emailMsg.className   = '';
                        emailTaken = false;
                        submitBtn.disabled = false;
                    }
                }
            };

            xhr.onerror = function () {
                emailMsg.textContent = '';
                emailMsg.className   = '';
                emailTaken = false;
                submitBtn.disabled = false;
            };

            xhr.send('email=' + encodeURIComponent(email) + '&role=' + encodeURIComponent(role));
        }, 500);
    }

    emailInput.addEventListener('input', checkEmail);

    roleSelect.addEventListener('change', function () {
        if (emailInput.value.trim() !== '') {
            checkEmail();
        }
    });

    signupForm.addEventListener('submit', function (e) {
        if (emailTaken) {
            e.preventDefault();
        }
    });
});