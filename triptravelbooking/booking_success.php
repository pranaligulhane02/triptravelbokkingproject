<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM travel ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $booking = $result->fetch_assoc();
} else {
   
    $booking = null;
}

$conn->close();
?>


 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-image: url('bus.jpg'); 
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .success-background {
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 600px;
            margin: 20px;
            animation: fadeInUp 1s ease-out;
        }

        .success-message h2 {
            color: #28a745;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .success-message p {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .info {
            background-color: rgba(240, 248, 255, 0.9);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: left;
        }

        .info h3 {
            color: #007bff;
            margin-bottom: 10px;
        }

        .info p {
            font-size: 1rem;
            margin: 5px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 30px;
            text-decoration: none;
            margin: 10px 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .button:hover {
            background-color: #ffffff;
            border-color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <div class="success-background">
        <div class="success-message">
            <h2>Booking Successful!</h2>
            <p>Thank you for booking with us. Your travel arrangements have been confirmed.</p>
            
            <?php if ($booking): ?>
           
            
           
            <?php else: ?>
            <p>Enjoy Your Journey!!</p>
            <?php endif; ?>
            <a href="payment.html" class="button">Payment Here</a>
            <a href="all_bookings.php" class="button">View All Bookings</a>
            <a href="index.html" class="button">Back to Home</a>
        </div>
    </div>

</body>
</html>
