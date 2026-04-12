<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bus Details</title>
  <link rel="stylesheet" href="style.css">
</head>
<style>
  .bus-info {
    margin: 100px auto;
    width: 50%;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    background-color: #fff;
  }

  .bus-info h2 {
    text-align: center;
  }

  .bus-info table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }

  .bus-info table, th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
  }

  .cancel-button {
    display: block;
    width: 100%;
    padding: 10px;
    margin-top: 20px;
    text-align: center;
    background-color: #ff5733;
    color: white;
    font-size: 1.2em;
    font-weight: bold;
    text-decoration: none;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  }

  .cancel-button:hover {
    background-color: #e04e2c;
  }
</style>
<body>
  <div class="bus-info">
    <h2>Bus Details</h2>
    <form id="bus-form">
      <label for="bus-name">Bus Name:</label>
      <input type="text" id="bus-name" required><br><br>

      <label for="bus-route">Bus Route:</label>
      <input type="text" id="bus-route" required><br><br>

      <label for="bus-no">Bus No:</label>
      <input type="text" id="bus-no" required><br><br>

      <label for="bus-time">Bus Time:</label>
      <input type="time" id="bus-time" required><br><br>

      <button type="button" onclick="addBus()">Add Bus</button>
    </form>
    
    <table id="bus-table">
      <tr>
        <th>Bus Name</th>
        <th>Bus Route</th>
        <th>Bus No</th>
        <th>Bus Time</th>
      </tr>
    </table>
    
    <a href="ownerhomeinterface.html" class="cancel-button">Cancel bus</a>
  </div>
  
  <script>
    function addBus() {
      const name = document.getElementById('bus-name').value;
      const route = document.getElementById('bus-route').value;
      const no = document.getElementById('bus-no').value;
      const time = document.getElementById('bus-time').value;

      if (name && route && no && time) {
        const table = document.getElementById('bus-table');
        const newRow = table.insertRow();
        newRow.innerHTML = `<td>${name}</td><td>${route}</td><td>${no}</td><td>${time}</td>`;
      }
    }
  </script>
</body>
</html> -->

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bus_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_bus'])) {
    $bus_id = $_POST['bus_id'];
    $sql = "DELETE FROM buses WHERE id='$bus_id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Bus cancelled successfully');</script>";
    } else {
        echo "<script>alert('Error: ' . $conn->error);</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bus Details</title>
  <link rel="stylesheet" href="style.css">
</head>
<style>
  .bus-info {
    margin: 100px auto;
    width: 50%;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    background-color: #fff;
  }
  .bus-info h2 {
    text-align: center;
  }
  .bus-info table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }
  .bus-info table, th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
  }
  .cancel-button {
    display: block;
    width: 100%;
    padding: 10px;
    margin-top: 20px;
    text-align: center;
    background-color: #ff5733;
    color: white;
    font-size: 1.2em;
    font-weight: bold;
    text-decoration: none;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  }
  .cancel-button:hover {
    background-color: #e04e2c;
  }
</style>
<body>
  <div class="bus-info">
    <h2>Bus Details</h2>
    <table id="bus-table">
      <tr>
        <th>Bus Name</th>
        <th>Bus Route</th>
        <th>Bus No</th>
        <th>Bus Time</th>
        <th>Action</th>
      </tr>
      <?php
      $sql = "SELECT * FROM buses";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['bus_name']}</td>
                      <td>{$row['bus_route']}</td>
                      <td>{$row['bus_no']}</td>
                      <td>{$row['bus_time']}</td>
                      <td>
                        <form method='POST'>
                          <input type='hidden' name='bus_id' value='{$row['id']}'>
                          <button type='submit' name='cancel_bus' class='cancel-button'>Cancel</button>
                        </form>
                      </td>
                    </tr>";
          }
      }
      $conn->close();
      ?>
    </table>
  </div>
</body>
</html>

