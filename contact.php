<?php
// Database connection
$host = "localhost";
$user = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (empty)
$dbname = "leafycart"; // Change to your database name

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['contact_name']);
    $email = trim($_POST['contact_email']);
    $subject = trim($_POST['contact_subject']);
    $message = trim($_POST['contact_message']);



    // Prepare SQL statement to insert data
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Message sent successfully!'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Failed to send message! Try again.'); window.location.href='index.html';</script>";
    }    

    $stmt->close();
}

$conn->close();
?>