<!-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PASENGER</title>
<style>

body {
font-family: Arial, sans-serif;
margin: 0;
padding: 0;
background-color: #f0f0f0;
}

.container {
width: 80%;
margin: 40px auto;
text-align: center;
}

.logo {
font-size: 48px;
font-weight: bold;
margin-bottom: 20px;
}

.icons-container {
display: flex;
justify-content: space-between;
align-items: center;
}

.icon-container {
width: 40%;
background-color: #fff;
padding: 20px;
border: 1px solid #ddd;
border-radius: 10px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.material-icons {
font-size: 48px;
margin-bottom: 10px;
}

h2 {
font-size: 24px;
font-weight: bold;
margin-bottom: 10px;
}

.middle-portion {
width: 80%;
margin: 40px auto;
text-align: center;
}

.middle-portion-text {
font-size: 24px;
font-weight: bold;
color: #333;
margin-bottom: 10px;
}

.option-button {
width: 100%;
height: 30px;
padding: 5px;
border: 1px solid #ccc;
border-radius: 5px;
background-color: #4CAF50;
color: #fff;
cursor: pointer;
}

.popup-container {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.5);
display: none;
}

.popup {
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
width: 50%;
height: 50%;
background-color: #fff;
padding: 20px;
border: 1px solid #ddd;
border-radius: 10px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.popup-title {
font-size: 24px;
font-weight: bold;
margin-bottom: 10px;
}

.popup-content {
font-size: 18px;
margin-bottom: 20px;
}

.time-route-display {
margin-top: 20px;
font-size: 20px;
font-weight: bold;
color: #333;
}

</style>
</head>
<body>
<div class="container">
<h1 class="logo">PASENGER</h1>
<div class="icons-container">
<div class="icon-container">
<i class="material-icons">schedule</i>
<h2>Bus Time</h2>
<button id="bus-time-button" class="option-button">Select Time</button>
</div>
<div class="icon-container">
<i class="material-icons">route</i>
<h2>Bus Route</h2>
<button id="bus-route-button" class="option-button">Select Route</button>
</div>
</div>
<button id="enter-button" class="option-button" style="margin-top: 20px;">Enter</button>
<div id="selected-time" class="time-route-display"></div>
<div id="selected-route" class="time-route-display"></div>

<div class="popup-container" id="popup-container">
<div class="popup" id="popup">
<h2 id="popup-title"></h2>
<div id="popup-content"></div>
<button id="popup-confirm-button" class="option-button">Confirm</button>
</div>
</div>
</div>

<script>
const busTimeButton = document.getElementById('bus-time-button');
const busRouteButton = document.getElementById('bus-route-button');
const popupContainer = document.getElementById('popup-container');
const popup = document.getElementById('popup');
const popupTitle = document.getElementById('popup-title');
const popupContent = document.getElementById('popup-content');
const popupConfirmButton = document.getElementById('popup-confirm-button');
const selectedTimeDisplay = document.getElementById('selected-time');
const selectedRouteDisplay = document.getElementById('selected-route');
const enterButton = document.getElementById('enter-button');
let selectedTime = '';
let selectedRoute = '';

busTimeButton.addEventListener('click', () => {
  popupTitle.textContent = 'Select Bus Time';
  popupContent.innerHTML = `
    <div class="time-select-container">
      <select id="hour-select" class="time-select">
        ${Array.from({ length: 12 }, (_, i) => `<option value="${(i+1).toString().padStart(2,'0')}">${(i+1).toString().padStart(2,'0')}</option>`).join('')}
      </select>
      <select id="minute-select" class="time-select">
        ${Array.from({ length: 12 }, (_, i) => `<option value="${(i*5).toString().padStart(2,'0')}">${(i*5).toString().padStart(2,'0')}</option>`).join('')}
      </select>
      <select id="am-pm-select" class="time-select">
        <option value="AM">AM</option>
        <option value="PM">PM</option>
      </select>
    </div>
  `;
  popupContainer.style.display = 'block';
  popupConfirmButton.onclick = () => {
    selectedTime = `${document.getElementById('hour-select').value}:${document.getElementById('minute-select').value} ${document.getElementById('am-pm-select').value}`;
    selectedTimeDisplay.innerHTML = `Selected Time: ${selectedTime}`;
    popupContainer.style.display = 'none';
  };
});

busRouteButton.addEventListener('click', () => {
  popupTitle.textContent = 'Select Bus Route';
  popupContent.innerHTML = `
    <div class="route-select-container">
      <label for="from-destination">From:</label>
      <input type="text" id="from-destination" placeholder="Enter starting point">
      <label for="to-destination">To:</label>
      <input type="text" id="to-destination" placeholder="Enter destination">
    </div>
  `;
  popupContainer.style.display = 'block';
  popupConfirmButton.onclick = () => {
    selectedRoute = `${document.getElementById('from-destination').value} to ${document.getElementById('to-destination').value}`;
    selectedRouteDisplay.innerHTML = `Selected Route: ${selectedRoute}`;
    popupContainer.style.display = 'none';
  };
});

enterButton.addEventListener('click', () => {
  window.location.href = 'busdetails.html';
});
</script>
</body>
</html> -->

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['bus_time'])) {
        $_SESSION['bus_time'] = $_POST['bus_time'];
    }
    if (isset($_POST['bus_route_from']) && isset($_POST['bus_route_to'])) {
        $_SESSION['bus_route'] = $_POST['bus_route_from'] . " to " . $_POST['bus_route_to'];
    }
    header("Location: busdetails.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PASENGER</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f0f0f0; text-align: center; }
    .container { width: 80%; margin: 40px auto; }
    .option-button { background-color: #4CAF50; color: white; border: none; padding: 10px; cursor: pointer; }
    .popup-container { display: none; position: fixed; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .popup { background: white; padding: 20px; margin: 15% auto; width: 50%; border-radius: 10px; }
</style>
</head>
<body>
<div class="container">
    <h1>PASENGER</h1>
    <form method="POST">
        <h2>Bus Time</h2>
        <input type="time" name="bus_time" required>
        <h2>Bus Route</h2>
        <input type="text" name="bus_route_from" placeholder="From" required>
        <input type="text" name="bus_route_to" placeholder="To" required>
        <button type="submit" class="option-button">Enter</button>
    </form>
</div>
</body>
</html>