// Verberg eventuele error message
function closeErrorMessage() {
    var errorDiv = document.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
}

// Check wachtwoord validatie en zet submit knop aan/uit
function checkPassword() {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("repeatPassword").value;
    const submitBtn = document.getElementById("submitButton");
    const errorMessage = document.getElementById("passwordErrorMessage");
    let errors = [];

    if (password) {
        if (password.length < 15) {
            errors.push("- Password must be 15 characters or more");
        }
        if (!/[A-Z]/.test(password)) {
            errors.push("- Password must contain at least one uppercase letter.");
        }
        if (!/[a-z]/.test(password)) {
            errors.push("- Password must contain at least one lowercase letter.");
        }
        if (!/[^a-zA-Z0-9]/.test(password)) {
            errors.push("- Password must contain at least one special character.");
        }
        if (password !== confirmPassword) {
            errors.push("- Passwords must match.");
        }

        if (errors.length > 0) {
            errorMessage.innerHTML = errors.join("<br>");
            submitBtn.disabled = true;
        } else {
            errorMessage.innerHTML = "";
            submitBtn.disabled = false;
        }
    } else {
        // Geen password ingevuld = knop uitzetten + geen foutmelding
        errorMessage.innerHTML = "";
        submitBtn.disabled = true;
    }
}

// Rol selectie bij login, verandert formulier dynamisch
function loginRoleSelector(event) {
    if (event.target.tagName === 'INPUT' && event.target.type === 'radio') {
        const selectedRole = event.target.value;
        const formContainer = document.querySelector('#loginFields');
        formContainer.innerHTML = '';

        if (selectedRole === 'runner') {
            formContainer.innerHTML = `
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingEmail" name="email" placeholder="Please enter your email" required>
                    <label for="floatingEmail" class="form-label">Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Please enter your password" required>
                    <label for="floatingPassword" class="form-label">Password</label>
                </div>
            `;
        } else if (selectedRole === 'admin') {
            formContainer.innerHTML = `
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="floatingUsername" name="username" placeholder="Please enter your username" required>
                    <label for="floatingUsername" class="form-label">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Please enter your password" required>
                    <label for="floatingPassword" class="form-label">Password</label>
                </div>
            `;
        } else if (selectedRole === 'organizer') {
            formContainer.innerHTML = `
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="userkeyCode" name="userkeyCode" placeholder="Enter userkey">
                    <label for="userkeyCode" class="form-label">Userkey</label>
                </div>
            `;
        }
    }
}

// Event listeners toevoegen zodat de functies reageren op input/events
document.addEventListener('DOMContentLoaded', () => {
    // Password check op input veranderingen
    const passwordInput = document.getElementById('password');
    const repeatPasswordInput = document.getElementById('repeatPassword');
    if (passwordInput && repeatPasswordInput) {
        passwordInput.addEventListener('input', checkPassword);
        repeatPasswordInput.addEventListener('input', checkPassword);
        checkPassword(); // meteen checken bij laden
    }

    // Login rol selectie
    const roleRadios = document.querySelectorAll('input[type=radio][name=role]');
    roleRadios.forEach(radio => {
        radio.addEventListener('change', loginRoleSelector);
    });

    // Sluit knop voor error message als die er is
    const closeBtn = document.querySelector('.error-message .close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeErrorMessage);
    }
});
