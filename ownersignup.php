<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name']);
    $busNo = trim($_POST['bus_no']);
    $driverName = trim($_POST['driver_name']);
    $email = trim($_POST['email']);
    $driverEmail = trim($_POST['driver_email']);
    $route = trim($_POST['route']);
    $contact = trim($_POST['contact']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    /* ---------- VALIDATION ---------- */
    if (empty($name)) {
        $error = "Bus Owner Name is required.";
    } elseif (!preg_match('/^[a-zA-Z\s]{2,50}$/', $name)) {
        $error = "Invalid owner name.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid owner email.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {

        /* ---------- DUPLICATE CHECK ---------- */
        $check = $conn->prepare(
            "SELECT id FROM owners WHERE email = ? OR contact = ?"
        );
        $check->bind_param("ss", $email, $contact);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "This email or contact number is already registered.";
        } else {

            /* ---------- INSERT OWNER ---------- */
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO owners
                (name, bus_no, driver_name, email, driver_email, route, contact, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssssss",
                $name, $busNo, $driverName,
                $email, $driverEmail,
                $route, $contact, $hashedPassword
            );

            if ($stmt->execute()) {
                // Redirect to LOGIN after signup
                header("Location: ownerlogin.php?signup=success");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }

            $stmt->close();
        }

        $check->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bus Owner Sign Up</title>

<style>
/* ===== ORIGINAL CSS (UNCHANGED) ===== */
*{margin:0;padding:0;box-sizing:border-box;}
html,body{
height:100%;
font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
background:linear-gradient(135deg,#e8f5e8 0%,#ffffff 50%,#f0f8f0 100%);
overflow-x:hidden;
}
.main-wrapper{
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
padding:20px;
}
.container{
background:white;
max-width:500px;
width:100%;
padding:40px;
border-radius:15px;
border:2px solid #e8f5e8;
box-shadow:0 10px 30px rgba(76,175,80,.1);
}
h2{text-align:center;color:#2e7d32;margin-bottom:30px;}
.form-group{margin-bottom:20px;}
label{color:#2e7d32;font-size:14px;font-weight:500;}
input,button{
width:100%;
padding:15px;
border-radius:8px;
border:2px solid #e8f5e8;
font-size:16px;
background:#fafafa;
}
input:focus{
outline:none;
border-color:#4caf50;
background:#fff;
box-shadow:0 0 0 3px rgba(76,175,80,.1);
}
button{
background:linear-gradient(135deg,#4caf50,#2e7d32);
color:white;
border:none;
font-weight:600;
cursor:pointer;
}
button:hover{
background:linear-gradient(135deg,#45a049,#1b5e20);
transform:translateY(-2px);
box-shadow:0 5px 15px rgba(76,175,80,.3);
}
.error{
background:#ffebee;
color:#d32f2f;
padding:12px;
border-radius:8px;
margin-bottom:20px;
text-align:center;
border:1px solid #ffcdd2;
}
.success{
background:#e8f5e8;
color:#2e7d32;
padding:12px;
border-radius:8px;
margin-bottom:20px;
text-align:center;
border:1px solid #c8e6c9;
}
.login-link{text-align:center;margin-top:20px;}
.login-link a{color:#4caf50;text-decoration:none;font-weight:500;}
.login-link a:hover{text-decoration:underline;}
.password-wrapper{position:relative;}
.toggle-eye{
position:absolute;
right:15px;
top:50%;
transform:translateY(-50%);
cursor:pointer;
color:#4caf50;
}
.form-row{display:flex;gap:15px;}
.form-row .form-group{flex:1;}
@media(max-width:768px){
.form-row{flex-direction:column;}
.container{padding:30px;}
}
</style>
</head>

<body>
<div class="main-wrapper">
<div class="container">

<h2>Bus Owner Sign Up</h2>

<?php if (!empty($error)): ?>
<div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST">

<div class="form-group">
<label>Bus Owner Name</label>
<input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
</div>

<div class="form-row">
<div class="form-group">
<label>Bus Number</label>
<input type="text" name="bus_no" required value="<?= htmlspecialchars($_POST['bus_no'] ?? '') ?>">
</div>
<div class="form-group">
<label>Driver Name</label>
<input type="text" name="driver_name" required value="<?= htmlspecialchars($_POST['driver_name'] ?? '') ?>">
</div>
</div>

<div class="form-group">
<label>Owner Email</label>
<input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
</div>

<div class="form-group">
<label>Driver Email</label>
<input type="email" name="driver_email" required value="<?= htmlspecialchars($_POST['driver_email'] ?? '') ?>">
</div>

<div class="form-group">
<label>Route</label>
<input type="text" name="route" required value="<?= htmlspecialchars($_POST['route'] ?? '') ?>">
</div>

<div class="form-group">
<label>Contact</label>
<input type="tel" name="contact" maxlength="10" required value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>">
</div>

<div class="form-group password-wrapper">
<label>Password</label>
<input type="password" name="password" id="password" required>
<span class="toggle-eye" onclick="toggle('password',this)">👁️</span>
</div>

<div class="form-group password-wrapper">
<label>Confirm Password</label>
<input type="password" name="confirm_password" id="confirm" required>
<span class="toggle-eye" onclick="toggle('confirm',this)">👁️</span>
</div>

<button type="submit">Create Account</button>

<div class="login-link">
<p>Already have an account? <a href="ownerlogin.php">Login here</a></p>
</div>

</form>
</div>
</div>

<script>
function toggle(id,icon){
const f=document.getElementById(id);
f.type=f.type==="password"?"text":"password";
icon.textContent=f.type==="text"?"🙈":"👁️";
}
</script>

</body>
</html>
