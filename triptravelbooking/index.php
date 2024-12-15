<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $Source = $_POST["source"];
    $destination = $_POST["destination"];
    $startdate = $_POST["startdate"];
    $returndate = $_POST["returndate"];
    $noofseat= $_POST["noofseat"];
    $travelname = $_POST["travelname"];
    
    
    $sql = "INSERT INTO `travel` (`name`, `email`, `source`,`destination`, `startdate`, `returndate`,`noofseat`,`travelname`)
     VALUES ('$name', '$email', '$Source','$destination', '$startdate', '$returndate','$noofseat','$travelname')";

    if ($conn->query($sql) === TRUE) {
        
        header("Location: booking_success.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; 
    }
}

$conn->close();
?>