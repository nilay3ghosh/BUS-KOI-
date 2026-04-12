<!-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
        text-align: center;
    }
    .container {
        width: 40%;
        margin: 50px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    input {
        width: 80%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    button {
        width: 85%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }
    button:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <form id="login-form">
        <input type="text" id="username" placeholder="Username" required>
        <input type="email" id="gmail" placeholder="Gmail" required>
        <input type="password" id="password" placeholder="Password" required>
        <button id="login-button">Login</button>
        <p>Don't have an account? <a href="passengersignup.html">Sign up</a></p>
    </form>
</div>
<script>
    document.getElementById("login-form").addEventListener("submit", function(event) {
        event.preventDefault();
        let username = document.getElementById("username").value;
        let gmail = document.getElementById("gmail").value;
        let password = document.getElementById("password").value;
        if (username && gmail && password) {
            alert("Login successful!");
        } else {
            alert("Please enter valid credentials.");
        }
    });
</script>
</body>
</html> -->
<?php
session_start();
include 'db_connect.php'; // database connection

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $gmail = trim($_POST['gmail']);
    $password = $_POST['password'];

    // Validate inputs server-side
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])[A-Za-z]+$/', $username)) {
        $error = "Username must contain both uppercase and lowercase letters only.";
    } elseif (!filter_var($gmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid Gmail format.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{7,9}$/', $password)) {
        $error = "Password must be 7-9 characters long and contain uppercase, lowercase, number, and special character.";
    } else {
        // Secure input
        $username = mysqli_real_escape_string($conn, $username);
        $gmail = mysqli_real_escape_string($conn, $gmail);

        $query = "SELECT * FROM users WHERE username='$username' AND gmail='$gmail'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['user'] = $row['username'];
                header("Location: passengerhomeinterface.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Invalid username or Gmail.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        text-align: center;
    }
    .container {
        width: 40%;
        margin: 50px auto;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    input {
        width: 80%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    button {
        width: 85%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background-color: #45a049;
    }
    p.error {
        color: red;
    }
</style>
<script>
    function validateForm() {
        const username = document.forms["loginForm"]["username"].value;
        const password = document.forms["loginForm"]["password"].value;
        const usernameRegex = /^(?=.*[a-z])(?=.*[A-Z])[A-Za-z]+$/;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{7,9}$/;

        if (!usernameRegex.test(username)) {
            alert("Username must have both uppercase and lowercase letters.");
            return false;
        }
        if (!passwordRegex.test(password)) {
            alert("Password must be 7–9 characters long and include uppercase, lowercase, digit, and special character.");
            return false;
        }
        return true;
    }
</script>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" name="loginForm" onsubmit="return validateForm();">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="gmail" placeholder="Gmail" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="passengersignup.php">Sign up</a></p>
    </form>
</div>
</body>
</html>
