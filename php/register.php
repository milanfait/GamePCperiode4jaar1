<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("../conn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = 'Email already exists.';
        header('Location: ../index.php?page=register');
        exit();
    }

    $_SESSION['formData'] = $_POST;

    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $firstName = $_POST['firstName'];
    $infix = $_POST['infix'];
    $lastName = $_POST['lastName'];
    $street = $_POST['street'];
    $houseNumber = $_POST['houseNumber'];
    $postalCode = $_POST['postalCode'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];
    $birthDate = $_POST['birthDate'];
    $gender = $_POST['gender'];
    $phoneNumber = $_POST['phoneNumber'];
    $companyName = $_POST['companyName'];
    $roles = isset($_POST['roles']) ? $_POST['roles'] : [];
    $username = isset($_POST['username']) ? $_POST['username'] : null;

    $userKey = null;
    if (in_array('employee', $roles)) {
        $userKey = uniqid('ORG-', true);
    }

    $sql = "INSERT INTO `users` (`email`, `password`, `firstName`, `infix`, `lastName`, `street`, `houseNumber`, `postalCode`, `municipality`, `province`, `birthDate`, `gender`, `phoneNumber`, `companyName`, `userkey`, `username`)
            VALUES (:email, :password, :firstName, :infix, :lastName, :street, :houseNumber, :postalCode, :municipality, :province, :birthDate, :gender, :phoneNumber, :companyName, :userkey, :username)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':infix', $infix);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':street', $street);
    $stmt->bindParam(':houseNumber', $houseNumber);
    $stmt->bindParam(':postalCode', $postalCode);
    $stmt->bindParam(':municipality', $municipality);
    $stmt->bindParam(':province', $province);
    $stmt->bindParam(':birthDate', $birthDate);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':phoneNumber', $phoneNumber);
    $stmt->bindParam(':companyName', $companyName);
    $stmt->bindParam(':userkey', $userKey);
    $stmt->bindParam(':username', $username);

    try {
        $stmt->execute();
        $userId = $conn->lastInsertId();
        $_SESSION['user_id'] = $userId;

        $roleIds = [
            'customer' => 1,
            'employee' => 2,
        ];

        $sql = "INSERT INTO `userroles` (`FKuserID`, `FKroleID`) VALUES (:user_id, :role_id)";
        $stmt = $conn->prepare($sql);

        foreach ($roles as $role) {
            if (isset($roleIds[$role])) {
                $stmt->bindParam(':user_id', $userId);
                $stmt->bindParam(':role_id', $roleIds[$role]);
                $stmt->execute();
            }
        }

        header('Location: ../php/emailVerification.php?email=' . urlencode($email));
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
        echo $e->getMessage();
        header('Location: ../index.php?page=register');
        exit();
    }
} else {
    echo "uhhm this ain't right";
}
?>