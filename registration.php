<?php
// src/registration.php
session_start();
require_once 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname  = trim($_POST['lastname'] ?? '');
    $gender    = $_POST['gender'] ?? '';
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';

    if (!$firstname || !$lastname || !$email || !$password) {
        $message = "Please fill all required fields.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT user_id FROM tbl_users WHERE user_email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = "Email already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO tbl_users (user_firstname, user_lastname, user_gender, user_email, user_password) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$firstname, $lastname, $gender, $email, $hash]);
            $message = "Registration successful. You can now <a href='login.php'>login</a>.";
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>user resgistration</title></head>
<body>
  <h2>USER REGISTRATION</h2>
  <?php if ($message) echo "<p>$message</p>"; ?>
  <form method="post">
    <label>Names: <input name="firstname" required></label><br>
    <label>Email: <input name="email" type="email" required></label><br>
    <label>Password: <input name="password" type="password" required></label><br>
    <button type="submit">Submit</button>
  </form>
</body></html>