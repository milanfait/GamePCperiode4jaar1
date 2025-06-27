<?php

include("../conn.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_POST['username_or_email']) || empty($_POST['password'])) {
    $_SESSION['error'] = 'Vul zowel e-mailadres als wachtwoord in.';
    header('location: ../index.php?page=login');
    exit();
}

$email = $_POST['username_or_email']; // alleen e-mail is toegestaan
$password = $_POST['password'];
$_SESSION['formData'] = $_POST;

// Zoek gebruiker alleen op basis van e-mailadres
$sql = 'SELECT users.id as userID, email, password, roleName FROM users
        JOIN userroles ON userroles.FKuserID = users.id
        JOIN roles ON roles.id = userroles.FKroleID
        WHERE email = :email';

$sth = $conn->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
$sth->execute(['email' => $email]);

$user = $sth->fetch(PDO::FETCH_ASSOC);

// Verifieer wachtwoord
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['role'] = $user['roleName'];
    $_SESSION['user_id'] = $user['userID'];

    if ($user['roleName'] === 'customer') {
        header('location: ../index.php?page=overviewcustomer');
        exit();
    } elseif ($user['roleName'] === 'employee') {
        header('location: ../index.php?page=overviewemployee');
        exit();
    } else {
        $_SESSION['error'] = 'Ongeldige rol.';
        header('location: ../index.php?page=login');
        exit();
    }
} else {
    $_SESSION['error'] = 'E-mailadres of wachtwoord is onjuist.';
    header('location: ../index.php?page=login');
    exit();
}
