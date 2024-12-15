<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to log messages
function log_message($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, 'debug.log');
}

log_message("Script started");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    log_message("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

log_message("Connected successfully to the database");

// Check if the users table exists, if not create it
$table_check = $conn->query("SHOW TABLES LIKE 'users'");
if ($table_check->num_rows == 0) {
    $create_table_sql = "CREATE TABLE users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        password VARCHAR(255) NOT NULL,
        age INT(3),
        gender VARCHAR(10),
        phone VARCHAR(15)
    )";
    if ($conn->query($create_table_sql) === TRUE) {
        log_message("Table 'users' created successfully");
    } else {
        log_message("Error creating table: " . $conn->error);
        die("Error creating table: " . $conn->error);
    }
}

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    log_message("POST data received");
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    log_message("Received data - Username: $username, Age: $age, Gender: $gender, Phone: $phone");

    $sql = "INSERT INTO users (username, password, age, gender, phone) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        log_message("Prepare failed: " . $conn->error);
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssiss", $username, $hashed_password, $age, $gender, $phone);
    if ($stmt->execute()) {
        log_message("New record created successfully");
        echo "success";  // This will be used by JavaScript to determine if registration was successful
    } else {
        log_message("Error: " . $stmt->error);
        echo "error: " . $stmt->error;
    }

    $stmt->close();
} else {
    log_message("No POST data received");
    echo "This script should be accessed via a POST request from the registration form.";
}

$conn->close();
log_message("Script ended");
?>