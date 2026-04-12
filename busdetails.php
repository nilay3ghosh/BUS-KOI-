<!-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bus Details</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
        text-align: center;
    }
    .container {
        width: 80%;
        margin: 40px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: #4CAF50;
        color: white;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Bus Details</h1>
    <table>
        <thead>
            <tr>
                <th>Bus Name</th>
                <th>Route</th>
                <th>Time</th>
                <th>Bus No</th>
                <th>Driver Name</th>
            </tr>
        </thead>
        <tbody id="busTableBody">
        </tbody>
    </table>
</div>
<script>
    document.addEventListener("DOMContentLoaded", loadBuses);
    
    function loadBuses() {
        let buses = JSON.parse(localStorage.getItem("buses")) || [];
        let tableBody = document.getElementById("busTableBody");
        tableBody.innerHTML = "";
        buses.forEach(bus => {
            let row = tableBody.insertRow();
            row.insertCell(0).innerText = bus.busName;
            row.insertCell(1).innerText = bus.route;
            row.insertCell(2).innerText = bus.time;
            row.insertCell(3).innerText = bus.busNo;
            row.insertCell(4).innerText = bus.driverName;
        });
    }
</script>
</body>
</html> -->
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bus_booking_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch bus details
$sql = "SELECT busName, source, destination, time, busNo, driverName FROM buses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bus Details</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
        text-align: center;
    }
    .container {
        width: 80%;
        margin: 40px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: #4CAF50;
        color: white;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Bus Details</h1>
    <table>
        <thead>
            <tr>
                <th>Bus Name</th>
                <th>Source</th>
                <th>Destination</th>
                <th>Time</th>
                <th>Bus No</th>
                <th>Driver Name</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['busName']); ?></td>
                        <td><?php echo htmlspecialchars($row['source']); ?></td>
                        <td><?php echo htmlspecialchars($row['destination']); ?></td>
                        <td><?php echo htmlspecialchars($row['time']); ?></td>
                        <td><?php echo htmlspecialchars($row['busNo']); ?></td>
                        <td><?php echo htmlspecialchars($row['driverName']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No bus details found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php $conn->close(); ?>