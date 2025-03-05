<?php
session_start();
$host = "localhost"; // Change if needed
$user = "root"; // Change if needed
$password = ""; // Change if using a different password
$database = "leafycart";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    
    // Check if the email is already registered
    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User already exists
        echo "<script>alert('User already exists! Redirecting to login.'); window.location.href='login.php';</script>";
    } else {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! Redirecting to login page.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error signing up. Please try again.'); window.history.back();</script>";
        }
    }
    $stmt->close();
}
$conn->close();
?>
