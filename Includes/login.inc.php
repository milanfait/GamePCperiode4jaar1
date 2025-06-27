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

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="floatingEmail" name="username_or_email" placeholder="E-mailadres" required value="<?= isset($formData['username_or_email']) ? htmlspecialchars($formData['username_or_email']) : '' ?>">
                                <label for="floatingEmail" class="form-label">E-mailaddres</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Wachtwoord" required>
                                <label for="floatingPassword" class="form-label">password</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php unset($_SESSION['formData']); ?>
