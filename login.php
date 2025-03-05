<?php
session_start();
include './includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // ✅ Fetch user details
        $sql = "SELECT id, name, password, session_token FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // ✅ Generate a new session token
                $session_token = bin2hex(random_bytes(32));

                // ✅ Update user's session_token in the database
                $update_sql = "UPDATE users SET session_token = :session_token WHERE id = :id";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bindParam(":session_token", $session_token, PDO::PARAM_STR);
                $update_stmt->bindParam(":id", $user['id'], PDO::PARAM_INT);
                $update_stmt->execute();

                // ✅ Store user details in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['session_token'] = $session_token;

                header("Location: index.html");
                exit();
            } else {
                echo "<script>alert('Invalid password!'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('No account found!'); window.location.href='login.php';</script>";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>