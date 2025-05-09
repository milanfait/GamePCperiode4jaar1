<?php

include("../conn.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_POST['roles']) || empty($_POST['roles'])) {
    $_SESSION['error'] = 'Role is not selected.';
    header('location: ../index.php?page=login');
    exit();
}

$selectedRole = $_POST['roles'][0];
$password = isset($_POST['password']) ? $_POST['password'] : null;
$_SESSION['formData'] = $_POST;

if ($selectedRole == 'customer') {
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $_SESSION['error'] = 'Email is required for customers.';
        header('location: ../index.php?page=login');
        exit();
    }

    $sql = 'SELECT email, password, roleName, users.id as userID FROM users 
            JOIN userroles ON userroles.FKuserID = users.id
            JOIN roles ON roles.id = userroles.FKroleID
            WHERE email = :email AND roles.roleName = :role';

    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([
        'email' => $_POST['email'],
        'role' => $selectedRole
    ]);

} else if ($selectedRole == 'employee') {
    if (!isset($_POST['userkeyCode']) || empty($_POST['userkeyCode'])) {
        $_SESSION['error'] = 'Userkey is required for employees.';
        header('location: ../index.php?page=login');
        exit();
    }
    echo "test";
    $sql = 'SELECT userkey, roleName, users.id AS userID FROM users
            JOIN userroles ON userroles.FKuserID = users.id
            JOIN roles ON roles.id = userroles.FKroleID 
            WHERE userkey = :userkey AND roles.roleName = :role';

    $sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $sth->execute([
        'userkey' => $_POST['userkeyCode'],
        'role' => $selectedRole
    ]);

} else {
    $_SESSION['error'] = 'Invalid role selected.';
    header('location: ../index.php?page=login');
    exit();
}

$rsemail = $sth->fetch(PDO::FETCH_ASSOC);
var_dump($rsemail);
$_SESSION['role'] = $rsemail['roleName'];
$_SESSION['user_id'] = $rsemail['userID'];
if ($rsemail['roleName'] == 'employee') {
    header('location: ../index.php?page=overviewemployee');
} else if ($rsemail['roleName'] == 'customer') {
    header('location: ../index.php?page=overviewcustomer');
}

?>
<script>
    const maxAttempts = 3;
    const lockoutTime = 5 * 60 * 1000;
    const attemptsKey = 'loginAttempts';
    const lockoutKey = 'lockoutTime';

    let attempts = parseInt(localStorage.getItem(attemptsKey)) || 0;
    let lockout = parseInt(localStorage.getItem(lockoutKey)) || 0;

    const now = Date.now();

    if (lockout && now < lockout) {
        alert('You have exceeded the maximum login attempts. Please wait 5 minutes before trying again.');
        window.location.href = '../index.php?page=login';
    } else {
        localStorage.removeItem(lockoutKey);
    }

    <?php if (password_verify($_POST['password'], $rsemail['password'])) { ?>
    localStorage.removeItem(attemptsKey);
    localStorage.removeItem(lockoutKey);
    <?php
    if ($_SESSION['role'] == 'customer') {
        header('location: ../index.php?page=overviewcustomer');
    } else if ($_SESSION['role'] == 'employee') {
        header('location: ../index.php?page=overviewemployeeemployee');
    }
    ?>
    <?php } else { ?>
    attempts++;
    if (attempts >= maxAttempts) {
        localStorage.setItem(lockoutKey, now + lockoutTime);
        alert('You have exceeded the maximum login attempts. Please wait 5 minutes before trying again.');
    } else {
        alert(`Login info is not correct! You have ${maxAttempts - attempts} attempts remaining.`);
    }
    localStorage.setItem(attemptsKey, attempts);
    window.location.href = '../index.php?page=login';
    <?php } ?>
</script>