<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History</title>
  <link rel="stylesheet" href="style.css">
</head>
<style>
    
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f0f0f0;
}

header {
  background-color: #333;
  color: #fff;
  padding: 00px;
  text-align: center;
}

.logo {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 10px;
}

.history-section {
  width: 100%;
  margin: 40px auto;
  text-align: center;
  background-color: #d0cccc;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#history-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

#history-list li {
  margin-bottom: 10px;
  padding: 10px;
  border-bottom: 1px solid #ccc;
}

#history-list li:last-child {
  border-bottom: none;
}



</style>
<body>
  <header>
    <div class="logo">
      <h1>SEARCH HISTORY</h1>
    </div>
  </header>
  <section class="history-section">
    <h2>History</h2>
    <ul id="history-list">
    </ul>
  </section>
  <script>
    // Assume that the history items are stored in local storage
    const historyItems = JSON.parse(localStorage.getItem('historyItems'));
    const historyListElement = document.getElementById('history-list');
    
    if (historyItems) {
      historyItems.forEach((item) => {
        const listItemElement = document.createElement('LI');
        listItemElement.textContent = item;
        historyListElement.appendChild(listItemElement);
      });
    }
  </script>
</body>
</html>
 -->

 <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bus_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM history ORDER BY timestamp DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>History</title>
  <link rel="stylesheet" href="style.css">
</head>
<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f0f0f0;
}

header {
  background-color: #333;
  color: #fff;
  padding: 20px;
  text-align: center;
}

.logo {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 10px;
}

.history-section {
  width: 80%;
  margin: 40px auto;
  text-align: center;
  background-color: #d0cccc;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#history-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

#history-list li {
  margin-bottom: 10px;
  padding: 10px;
  border-bottom: 1px solid #ccc;
}

#history-list li:last-child {
  border-bottom: none;
}
</style>
<body>
  <header>
    <div class="logo">
      <h1>SEARCH HISTORY</h1>
    </div>
  </header>
  <section class="history-section">
    <h2>History</h2>
    <ul id="history-list">
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<li>{$row['search_term']} - {$row['timestamp']}</li>";
          }
      } else {
          echo "<li>No history found.</li>";
      }
      $conn->close();
      ?>
    </ul>
  </section>
</body>
</html>
