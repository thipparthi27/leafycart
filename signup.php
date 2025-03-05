<?php
session_start();
include './includes/db.php'; // Ensure correct database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ Check if fields are empty
    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required!'); window.location.href='signup.php';</script>";
        exit();
    }

    // ✅ Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!'); window.location.href='signup.php';</script>";
        exit();
    }

    // ✅ Check if user already exists
    try {
        $check_user = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $check_user->bindParam(":email", $email, PDO::PARAM_STR);
        $check_user->execute();

        if ($check_user->rowCount() > 0) {
            echo "<script>alert('User already exists! Please log in.'); window.location.href='login.php';</script>";
            exit();
        }

        // ✅ Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ✅ Insert new user into database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! Please log in.'); window.location.href='login.html';</script>";
        } else {
            echo "<script>alert('Signup failed! Please try again.'); window.location.href='signup.html';</script>";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage()); // Debugging (remove in production)
    }
}
?>