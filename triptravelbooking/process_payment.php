<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$sql = "CREATE TABLE IF NOT EXISTS payments (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    transaction_id VARCHAR(30) NOT NULL,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    card_number VARCHAR(20) NOT NULL,
    expiry_date VARCHAR(5) NOT NULL,
    cvv VARCHAR(4) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'], $_POST['email'], $_POST['amount'], $_POST['card_number'], $_POST['expiry_date'], $_POST['cvv'])) {
  
    $name = sanitize_input($conn->real_escape_string($_POST["name"]));
    $email = sanitize_input($conn->real_escape_string($_POST["email"]));
    $amount = floatval($_POST["amount"]);
    $card_number = sanitize_input($conn->real_escape_string($_POST["card_number"]));
    $expiry_date = sanitize_input($conn->real_escape_string($_POST["expiry_date"]));
    $cvv = sanitize_input($conn->real_escape_string($_POST["cvv"]));

    $transaction_id = uniqid("TRANS_");

   
    $current_date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO payments (transaction_id, name, email, amount, card_number, expiry_date, cvv)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdsss", $transaction_id, $name, $email, $amount, $card_number, $expiry_date, $cvv);

    if ($stmt->execute()) {
      
        $receipt = "<h2>Payment Receipt</h2>";
        $receipt .= "<p><strong>Transaction ID:</strong> " . htmlspecialchars($transaction_id) . "</p>";
        $receipt .= "<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>";
        $receipt .= "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        $receipt .= "<p><strong>Amount:</strong> $" . number_format($amount, 2) . "</p>";
        $receipt .= "<p><strong>Card Number:</strong> **** **** **** " . substr($card_number, -4) . "</p>";
        $receipt .= "<p><strong>Date:</strong> " . htmlspecialchars($current_date) . "</p>";
        $receipt .= "<p>Thank you for your payment!</p>";
       

       
        echo $receipt;
        
       
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Payment Processing</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='d-flex align-items-center justify-content-center' style='height: 100vh;'>
        <div class='text-center'>
            <h1>Payment Processing Page</h1>
            <p>This page processes payment form submissions. Please submit the payment form to see results.</p>
            <a href='payment_form.html' class='btn btn-primary'>Go to Payment Form</a>
            
        </div>
    </body>
    </html>";
}

$conn->close();
?>