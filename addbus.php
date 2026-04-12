<?php
session_start();
require_once 'db_connect.php';  // This will use your existing DB connection

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $busName = trim($_POST['busName']);
    $source = trim($_POST['source']);
    $destination = trim($_POST['destination']);
    $time = trim($_POST['time']);
    $busNo = trim($_POST['busNo']);
    $driverName = trim($_POST['driverName']);

    $sql = "INSERT INTO bus (bus_name, source, destination, time, bus_no, driver_name)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $busName, $source, $destination, $time, $busNo, $driverName);

    if ($stmt->execute()) {
        $message = "✅ Bus details saved successfully!";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Input Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input, button {
            width: 80%;
            margin: 10px auto;
            padding: 10px;
            display: block;
        }
        .message {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Enter Bus Details</h1>
    <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
    <form method="POST" action="">
        <input type="text" name="busName" placeholder="Bus Name" required>
        <input type="text" name="source" placeholder="Source" required>
        <input type="text" name="destination" placeholder="Destination" required>
        <input type="text" name="time" placeholder="Time" required>
        <input type="text" name="busNo" placeholder="Bus No" required>
        <input type="text" name="driverName" placeholder="Driver Name" required>
        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
