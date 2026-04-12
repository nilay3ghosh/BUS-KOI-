<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(0);
ini_set('display_errors', 0);

include 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, email, password 
             FROM owners 
             WHERE email = ? 
             LIMIT 1"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                // ✅ Session setup
                $_SESSION['owner_id']   = $user['id'];
                $_SESSION['owner_name'] = $user['name'];
                $_SESSION['email']      = $user['email'];
                $_SESSION['user_type']  = 'owner';
                $_SESSION['logged_in']  = true;

                $stmt->close();
                $conn->close();

                // ✅ Redirect after login
                header("Location: ownerhomeinterface.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "No account found with this email!";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VRAMAN - Bus Owner Login</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Roboto:wght@300;400;500;700&display=swap');

* { box-sizing: border-box; }

html, body {
  height: 100%;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #e8f5e8 0%, #ffffff 50%, #f0f8f0 100%);
  margin: 0;
  padding: 0;
  overflow-x: hidden;
  color: #333;
}

.main-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.login-section {
  background: white;
  max-width: 400px;
  width: 100%;
  padding: 40px;
  border-radius: 15px;
  text-align: center;
  border: 2px solid #e8f5e8;
  box-shadow: 0 10px 30px rgba(76,175,80,0.1);
}

.login-section h2 {
  color: #2e7d32;
  margin-bottom: 30px;
  font-size: 28px;
}

.form-group {
  margin-bottom: 25px;
  text-align: left;
}

.form-group label {
  color: #2e7d32;
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 5px;
  display: block;
}

.login-section input,
.login-section button {
  width: 100%;
  padding: 15px;
  border-radius: 8px;
  font-size: 16px;
  border: 2px solid #e8f5e8;
  background: #fafafa;
  transition: 0.3s;
}

.login-section input:focus {
  border-color: #4CAF50;
  outline: none;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(76,175,80,0.1);
}

.login-section button {
  background: linear-gradient(135deg, #4CAF50, #2e7d32);
  color: white;
  font-weight: 600;
  cursor: pointer;
  border: none;
  margin-top: 10px;
}

.login-section button:hover {
  background: linear-gradient(135deg, #45a049, #1b5e20);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(76,175,80,0.3);
}

.error {
  background: #ffebee;
  color: #d32f2f;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 20px;
  border: 1px solid #ffcdd2;
}

.success {
  background: #e8f5e8;
  color: #2e7d32;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 20px;
  border: 1px solid #c8e6c9;
}

.password-wrapper {
  position: relative;
}

.password-wrapper input {
  padding-right: 55px;
}

.toggle-eye {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  font-size: 20px;
  color: #4CAF50;
}

.links-container {
  margin-top: 20px;
}

.links-container a {
  color: #4CAF50;
  text-decoration: none;
  font-weight: 500;
}

.links-container a:hover {
  text-decoration: underline;
}

.forgot-password {
  color: #ff6b35;
}

@media (max-width: 480px) {
  .login-section { padding: 35px 25px; }
}
</style>
</head>

<body>

<div class="main-wrapper">
<section class="login-section">

<h2>Bus Owner Login</h2>

<?php if (!empty($error)): ?>
<div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">

<div class="form-group">
<label>Email</label>
<input type="email" name="email" required
placeholder="Enter registered email"
value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
</div>

<div class="form-group">
<label>Password</label>
<div class="password-wrapper">
<input type="password" name="password" id="password" required placeholder="Enter password">
<span class="toggle-eye" onclick="togglePassword()">👁️</span>
</div>
</div>

<button type="submit">Login</button>

</form>

<div class="links-container">
<p><a href="forgot-password.php" class="forgot-password">Forgot Password?</a></p>
<p>Don't have an account? <a href="ownersignup.php">Sign up here</a></p>
<p><a href="index.php">Back to Home</a></p>
</div>

</section>
</div>

<script>
function togglePassword() {
  const p = document.getElementById("password");
  p.type = p.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
