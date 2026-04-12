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
    .option {
        font-size: 24px;
        font-weight: bold;
        padding: 20px;
        margin: 20px;
        display: inline-block;
        cursor: pointer;
        border-radius: 10px;
        background-color: #4CAF50;
        color: white;
        transition: 0.3s;
        text-decoration: none;
    }
    .option:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Select an Option</h1>
    <a href="passengerlogin.html" class="option">PASSENGER</a>
    <a href="ownerlogin.html" class="option">BUS OWNER</a>
</div>
</body>
</html> -->
<?php
session_start();
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
    .option {
        font-size: 24px;
        font-weight: bold;
        padding: 20px;
        margin: 20px;
        display: inline-block;
        cursor: pointer;
        border-radius: 10px;
        background-color: #4CAF50;
        color: white;
        transition: 0.3s;
        text-decoration: none;
    }
    .option:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Select an Option</h1>
    <a href="passengerlogin.php" class="option">PASSENGER</a>
    <a href="ownerlogin.php" class="option">BUS OWNER</a>
</div>
</body>
</html>
