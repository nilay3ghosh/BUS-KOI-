<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "bus_booking_system",3308);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
//echo "✅ Connected successfully using mysqli_connect!";
?>
