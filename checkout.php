<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include './includes/db.php'; // Make sure this file and path are correct
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$product = array(
    'name' => 'Example Product',
    'quantity' => 1,
    'price' => '10.00'
);

$_SESSION['cart'][] = $product;
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and get form data
    $firstName = trim($_POST["first_name"]);
    $lastName = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $landmark = trim($_POST["landmark"]);
    $address = trim($_POST["address"]);
    $city = trim($_POST["city"]);
    $state = trim($_POST["state"]);
    $country = trim($_POST["country"]);
    $postcode = trim($_POST["postcode"]);
    $orderNotes = trim($_POST["order_notes"]);

    // Check if fields are empty
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($country) || empty($postcode)) {
        echo "<script>alert('All required billing fields must be filled!'); window.location.href='checkout.html';</script>";
        exit();
    }

    // Check if billing details already exist for the user
    try {
        $checkBilling = $conn->prepare("SELECT id FROM billing_details WHERE email = :email AND first_name = :firstName AND last_name = :lastName AND address = :address");
        $checkBilling->bindParam(":email", $email, PDO::PARAM_STR);
        $checkBilling->bindParam(":firstName", $firstName, PDO::PARAM_STR);
        $checkBilling->bindParam(":lastName", $lastName, PDO::PARAM_STR);
        $checkBilling->bindParam(":address", $address, PDO::PARAM_STR);
        $checkBilling->execute();

        if ($checkBilling->rowCount() > 0) {
            $billingData = $checkBilling->fetch(PDO::FETCH_ASSOC);
            $billingId = $billingData['id'];
        } else {
            // Insert billing details into database using prepared statement
            $sqlBilling = "INSERT INTO billing_details (first_name, last_name, email, phone, landmark, address, city, state, country, postcode, order_notes) VALUES (:firstName, :lastName, :email, :phone, :landmark, :address, :city, :state, :country, :postcode, :orderNotes)";

            $stmt = $conn->prepare($sqlBilling);
            $stmt->bindParam(":firstName", $firstName, PDO::PARAM_STR);
            $stmt->bindParam(":lastName", $lastName, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
            $stmt->bindParam(":landmark", $landmark, PDO::PARAM_STR);
            $stmt->bindParam(":address", $address, PDO::PARAM_STR);
            $stmt->bindParam(":city", $city, PDO::PARAM_STR);
            $stmt->bindParam(":state", $state, PDO::PARAM_STR);
            $stmt->bindParam(":country", $country, PDO::PARAM_STR);
            $stmt->bindParam(":postcode", $postcode, PDO::PARAM_STR);
            $stmt->bindParam(":orderNotes", $orderNotes, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $billingId = $conn->lastInsertId();
            } else {
                echo "<script>alert('Error inserting billing details!'); window.location.href='checkout.html';</script>";
                exit();
            }
        }

        // Insert order details using prepared statement
        $sqlOrder = "INSERT INTO order_details (billing_id, product_name, quantity, price) VALUES (:billingId, :productName, :quantity, :price)";
        $stmt = $conn->prepare($sqlOrder);
        $stmt->bindParam(":billingId", $billingId, PDO::PARAM_INT);

        // Replace with your actual product details from the cart
        // Example using a session cart:
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product) {
                $stmt->bindParam(":productName", $product['name'], PDO::PARAM_STR);
                $stmt->bindParam(":quantity", $product['quantity'], PDO::PARAM_INT);
                $stmt->bindParam(":price", $product['price'], PDO::PARAM_STR);
                
                if (!$stmt->execute()) {
                    echo "<script>alert('Error inserting order details!'); window.location.href='checkout.html';</script>";
                    exit();
                }
            }
            // Clear cart logic (replace with your actual cart clearing)
            $_SESSION['cart'] = array(); // Example: clear session cart
            echo "<script>alert('Order placed successfully!'); window.location.href='done.html';</script>";
        } else {
            echo "<script>alert('Your cart is empty!'); window.location.href='checkout.html';</script>";
            exit();
        }

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage()); // Debugging (remove in production)
    }
}
?>