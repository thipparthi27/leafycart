<?php
session_start();
$servername = "localhost";  // Change if needed
$username = "root";  // Change if using another user
$password = "";  // Change if your MySQL has a password
$database = "leafycart";  // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Insert into database (optional)
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Message sent successfully!";
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the contact page
    header("Location: contact.php");
    exit();
}
?>
