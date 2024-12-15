<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$booking = null;  

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    
    $sql = "SELECT * FROM travel WHERE `$first_column` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $booking = $result->fetch_assoc();
    } else {
        echo "No booking found with the given ID.";
    }

    // Handle form submission for update or delete
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            // Update booking logic here
            $update_sql = "UPDATE travel SET name=?, email=?, Source=?, destination=?, startdate=?, returndate=?, noofseat=? WHERE `$first_column`=?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssssss", $_POST['name'], $_POST['email'], $_POST['Source'], $_POST['destination'], $_POST['startdate'], $_POST['returndate'], $_POST['noofseat'], $id);
            if ($update_stmt->execute()) {
                echo "Booking updated successfully.";
                $booking = $_POST;  // Update $booking with the new data
            } else {
                echo "Error updating booking: " . $conn->error;
            }
        } elseif (isset($_POST['delete'])) {
            // Delete booking logic here
            $delete_sql = "DELETE FROM travel WHERE `$first_column` = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("s", $id);
            if ($delete_stmt->execute()) {
                echo "Booking deleted successfully.";
                header("Location: all_bookings.php");
                exit();
            } else {
                echo "Error deleting booking: " . $conn->error;
            }
        }
    }
} else {
    echo "No booking ID provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
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
    <br><br><br>
    
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
                        <a class="nav-link" href="loginform.html">Login</a>
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
    
    <div class="edit-form">
        <h2 style="color: red;">Edit Booking</h2>
    <?php if ($booking): ?>
            <form method="post">
                <input type="text" name="name" value="<?php echo htmlspecialchars($booking['name']); ?>" required>
                <input type="email" name="email" value="<?php echo htmlspecialchars($booking['email']); ?>" required>
                <input type="text" name="Source" value="<?php echo htmlspecialchars($booking['Source']); ?>" required>
                <input type="text" name="destination" value="<?php echo htmlspecialchars($booking['destination']); ?>" required>
                <input type="date" name="startdate" value="<?php echo htmlspecialchars($booking['startdate']); ?>" >
                <input type="date" name="returndate" value="<?php echo htmlspecialchars($booking['returndate']); ?>">
                <input type="number" name="noofseat" value="<?php echo htmlspecialchars($booking['noofseat']); ?>" required>
                <br><br><center><input type="submit" name="update" value="Update Booking">
                <input type="submit" name="delete" value="Delete Booking" onclick="return confirm('Are you sure you want to delete this booking?');"></center>
            </form>
        <?php else: ?>
            <p>No booking data available to edit.</p>
        <?php endif; ?>
       <br> <a href="all_bookings.php">Back to All Bookings</a>
    </div>
</body>
</html>