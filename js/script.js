const navbar = document.querySelector('.navbar');
const footer = document.querySelector('.footer');


    setTimeout(() => {
        if (navbar) {
            navbar.classList.add('transition');
        }
    }, 100);


    const adminCheckbox = document.querySelector('#roleAdmin');
    const registerForm = document.querySelector('#registerForm');

    adminCheckbox.addEventListener('change', () => {
        const isChecked = adminCheckbox.checked;
        usernameField = document.querySelector('#floatingUsername');
        if (isChecked) {
                const usernameDiv = document.createElement('div');
                usernameDiv.className = 'form-floating mb-3';
                usernameDiv.innerHTML = `
                    <input type="text" class="form-control" id="floatingUsername" name="username" placeholder="">
                    <label for="floatingUsername" class="form-label">Username</label>
                `;
                registerForm.insertBefore(usernameDiv, registerForm.querySelector('#password').parentElement);
        } else {
            if (usernameField) {
                usernameField.parentElement.remove();
            }
        }
    });
