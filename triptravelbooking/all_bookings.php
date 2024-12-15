<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}


$table_structure_sql = "DESCRIBE travel";
$table_structure_result = $conn->query($table_structure_sql);

if (!$table_structure_result) {
    die("Query failed: " . $conn->error);
}

$first_column = '';
while ($row = $table_structure_result->fetch_assoc()) {
    $first_column = $row['Field'];
    break;
}


$sql = "SELECT * FROM travel ORDER BY `$first_column` DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="booking.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f5a623;
            --bg-color: #f8f9fa;
            --dark-color: #333;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--bg-color);
            color: var(--dark-color);
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 150px 0;
            margin-bottom: 50px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease-out;
        }

        .hero-section p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .form-section {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,.1);
            margin-bottom: 30px;
        }

        .form-section h2 {
            color: var(--primary-color);
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0,0,0,.2);
        }

        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 60px 0 40px;
            margin-top: 80px;
            clip-path: polygon(0 15%, 100% 0, 100% 100%, 0 100%);
        }

        .footer h5 {
            color: var(--secondary-color);
        }

        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin-right: 15px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .social-icons a:hover {
            color: var(--secondary-color);
            transform: translateY(-5px) rotate(15deg);
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
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">TripVibes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="loginfrom.html">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.html">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">About Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br><br>
    <div class="all-bookings">
    <h2 style="color: red;">All Booking</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Start Date</th>
                    <th>Return Date</th>
                    <th>Number of Seats</th>
                    <th>Travel Name</th>
                    <th>Action</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['Source']); ?></td>
                    <td><?php echo htmlspecialchars($row['destination']); ?></td>
                    <td><?php echo htmlspecialchars($row['startdate']); ?></td>
                    <td><?php echo htmlspecialchars($row['returndate']); ?></td>
                    <td><?php echo htmlspecialchars($row['noofseat']); ?></td>
                    <td><?php echo htmlspecialchars($row['travelname']); ?></td>
                    <td><a href="edit_booking.php?id=<?php echo htmlspecialchars($row[$first_column]); ?>">Edit/Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </table>

        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
        <a href="login.html" class="button">Back to Home</a><br>
        <?php echo "Number of rows: " . $result->num_rows . "<br>"; ?>
    </div>
</body>
</html>