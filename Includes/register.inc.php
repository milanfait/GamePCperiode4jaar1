<?php
$formData = isset($_SESSION['formData']) ? $_SESSION['formData'] : [];
?>
<form action="php/register.php" method="POST">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm" style="margin: 8.2rem 0;">
                    <div class="card-body" id="registerForm">
                        <h3 class="text-center mb-4">Register</h3>
                        <div class="mb-3">
                            <label class="form-label">Select A Role (at least one):</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="customer" id="rolecustomer">
                                <label class="form-check-label" for="rolecustomer">customer</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="employee" id="roleAemployee">
                                <label class="form-check-label" for="roleemployee">employee</label>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingFirstName" name="firstName" placeholder="">
                            <label for="floatingFirstName" class="form-label">First Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatinginfix" name="infix" placeholder="">
                            <label for="floatinginfix" class="form-label">infix</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="lastName" id="floatingLastName" placeholder="">
                            <label for="floatingLastName" class="form-label">Last Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingstreet" name="street" placeholder="">
                            <label for="floatingstreet" class="form-label">street</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="houseNumber" id="floatinghouseNumber" placeholder="">
                            <label for="floatinghouseNumber" class="form-label">House Number</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="postalCode" id="floatingpostalCode" placeholder="">
                            <label for="floatingpostalCode" class="form-label">Postal Code</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingmunicipality" name="municipality"  placeholder="">
                            <label for="floatingmunicipality" class="form-label">Municipality</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="province" id="floatingprovince" placeholder="">
                            <label for="floatingprovince" class="form-label">province</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" name="birthDate" id="floatingBirthDate" placeholder=""x max="<?= date('Y-m-d'); ?>" required>
                            <label for="floatingBirthDate" class="form-label">Birthday</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="gender" id="floatinggender" placeholder="">
                            <label for="floatinggender" class="form-label">gender</label>

                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="phoneNumber" id="floatingphoneNumber" placeholder="">
                            <label for="floatingphoneNumber" class="form-label">phoneNumber</label>

                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="companyName" id="floatingcompanyName" placeholder="">
                            <label for="floatingcompanyName" class="form-label">companyName</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" id="floatingEmail" placeholder="">
                            <label for="floatingEmail" class="form-label">Email</label>

                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password"  oninput="checkPassword()" placeholder="">
                            <label for="password" class="form-label">Password</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="repeatPassword" name="confirmPassword" oninput="checkPassword()" placeholder="" required>
                            <label for="repeatPassword" class="form-label">Confirm password</label>
                        </div>

                        <p id="passwordErrorMessage" class="text-danger"></p>

                        <button type="submit" class="btn btn-primary w-100" id="submitButton" disabled>Submit</button>
                        <div class="mt-3 text-center">
                            <a href="?page=login" style="color:rgb(243, 179, 16); text-decoration:none;">Already a member? Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
unset($_SESSION['formData']);
?>