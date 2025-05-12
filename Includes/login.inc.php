<?php

$formData = isset($_SESSION['formData']) ? $_SESSION['formData'] : [];
?>
    <form action="php/login.php" method="post">
        <div class="content d-flex justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-4" style="max-width: 500px; width: 100%;">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h3 class="text-center mb-4">Login</h3>
                                <div class="mb-3">
                                    <label class="form-label text-center d-block">Select the role you want to log in as</label>
                                    <div class="d-flex justify-content-center gap-3" id="loginRoleSelector">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="roles[]" value="customer" id="rolecustomerLogin">
                                            <label class="form-check-label" for="rolecustomer">customer</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="roles[]" value="admin" id="roleAdminLogin">
                                            <label class="form-check-label" for="roleAdmin">Admin</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="roles[]" value="employee" id="roleemployeeLogin">
                                            <label class="form-check-label" for="roleemployee">employee</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center gap-3" id="loginFields"></div>

                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php unset($_SESSION['formData']); ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const roleSelector = document.getElementById("loginRoleSelector");
        roleSelector.addEventListener("click", function (event) {
            if (event.target.tagName === 'INPUT' && event.target.type === 'radio') {
                const selectedRole = event.target.value;

                const formContainer = document.querySelector('#loginFields');
                formContainer.innerHTML = '';

                if (selectedRole === 'customer') {
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
                } else if (selectedRole === 'employee') {
                    formContainer.innerHTML = `
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="userkeyCode" name="userkeyCode" placeholder="Enter userkey" required>
                            <label for="userkeyCode" class="form-label">Userkey</label>
                        </div>
                    `;
                }
            }
        });
    });
</script>

