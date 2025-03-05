<?php
// Database connection
$host = "localhost";
$user = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password (empty)
$dbname = "leafycart"; // Change this to your database name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['subscribe_email'])) {
    $email = trim($_POST['subscribe_email']); // Remove unnecessary spaces
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Clean input

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!'); window.history.back();</script>";
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM subscribers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('You are already subscribed!'); window.history.back();</script>";
        exit();
    }
    $stmt->close();

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        echo "<script>alert('Subscription successful!'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Subscription failed! Try again.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
