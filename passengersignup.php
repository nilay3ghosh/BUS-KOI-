<?php
// Prevent any output before headers
ob_start();

// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Suppress error reporting for cleaner output (remove in production)
error_reporting(0);
ini_set('display_errors', 0);

include 'db_connect.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $emergencyContact = trim($_POST['emergency_contact']);
    $emergencyName = trim($_POST['emergency_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    
    // VALIDATIONS
    if (empty($fullName)) {
        $error = "Full Name is required.";
    } elseif (!preg_match('/^[a-zA-Z\s]{2,50}$/', $fullName)) {
        $error = "Full Name must contain only letters and spaces (2-50 characters).";
    } elseif (empty($email)) {
        $error = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (empty($contact)) {
        $error = "Contact Number is required.";
    } elseif (!preg_match('/^[6-9]\d{9}$/', $contact)) {
        $error = "Contact number must be a valid 10-digit Indian number.";
    } elseif (empty($emergencyContact)) {
        $error = "Emergency Contact Number is required.";
    } elseif (!preg_match('/^[6-9]\d{9}$/', $emergencyContact)) {
        $error = "Emergency contact must be a valid 10-digit Indian number.";
    } elseif (empty($emergencyName)) {
        $error = "Emergency Contact Name is required.";
    } elseif (!preg_match('/^[a-zA-Z\s]{2,50}$/', $emergencyName)) {
        $error = "Emergency Contact Name must contain only letters and spaces (2-50 characters).";
    } elseif (empty($address)) {
        $error = "Address is required.";
    } elseif (strlen($address) < 10 || strlen($address) > 200) {
        $error = "Address must be 10-200 characters long.";
    } elseif (empty($city)) {
        $error = "City is required.";
    } elseif (!preg_match('/^[a-zA-Z\s]{2,50}$/', $city)) {
        $error = "City must contain only letters and spaces (2-50 characters).";
    } elseif (empty($age)) {
        $error = "Age is required.";
    } elseif (!is_numeric($age) || $age < 16 || $age > 99) {
        $error = "Age must be between 16 and 99.";
    } elseif (empty($gender)) {
        $error = "Gender is required.";
    } elseif (!in_array($gender, ['male', 'female', 'other'])) {
        $error = "Please select a valid gender.";
    } elseif (empty($password)) {
        $error = "Password is required.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,12}$/', $password)) {
        $error = "Password must be 7-12 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Check if user already exists
        $check_stmt = $conn->prepare("SELECT id FROM passengers WHERE email = ? OR contact = ?");
        if ($check_stmt) {
            $check_stmt->bind_param("ss", $email, $contact);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "User with this email or contact number already exists.";
            } else {
                // Hash password and insert
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Prepare SQL insert statement
                $stmt = $conn->prepare("INSERT INTO passengers (full_name, email, contact, emergency_contact, emergency_name, address, city, age, gender, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssss", $fullName, $email, $contact, $emergencyContact, $emergencyName, $address, $city, $age, $gender, $hashedPassword);
                
                // Execute and check for success
                if ($stmt->execute()) {
                    $_SESSION['passenger_id'] = $conn->insert_id;
                    $_SESSION['passenger_name'] = $fullName;
                    $_SESSION['passenger_email'] = $email;
                    
                    // Clean output buffer and redirect
                    ob_end_clean();
                    header("Location: passengerhomeinterface.php");
                    exit();
                } else {
                    $error = "Error: Could not save data. " . $conn->error;
                }
                
                $stmt->close();
            }
            $check_stmt->close();
        } else {
            $error = "Database error. Please try again.";
        }
    }
    
    if (isset($conn)) {
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passenger Sign Up</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e8 0%, #ffffff 50%, #f0fff0 100%);
            overflow-x: hidden;
        }
        
        .main-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        
        .container {
            background: white;
            width: 100%;
            max-width: 600px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(46, 125, 50, 0.1);
            border: 2px solid #c8e6c9;
            position: relative;
            z-index: 10;
            margin: 0 auto;
        }
        
        h2 {
            color: #2e7d32;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #388e3c;
            font-weight: 500;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #c8e6c9;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f9fff9;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="number"]:focus,
        input[type="password"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #4caf50;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }
        
        textarea {
            resize: vertical;
            min-height: 80px;
            font-family: inherit;
        }
        
        select {
            cursor: pointer;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .password-wrapper input {
            padding-right: 50px;
        }
        
        .toggle-eye {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            color: #2e7d32;
            user-select: none;
            transition: color 0.3s ease;
        }
        
        .toggle-eye:hover {
            color: #4caf50;
        }
        
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        button:hover {
            background: linear-gradient(135deg, #2e7d32, #1b5e20);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }
        
        .error {
            color: #d32f2f;
            background-color: #ffebee;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
            border: 1px solid #ffcdd2;
        }
        
        .success {
            color: #2e7d32;
            background-color: #e8f5e8;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
            border: 1px solid #c8e6c9;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-link a {
            color: #4caf50;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            color: #2e7d32;
            text-decoration: underline;
        }
        
        .validation-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            line-height: 1.4;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8fff8;
            border-radius: 8px;
            border-left: 4px solid #4caf50;
        }
        
        .form-section h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 600;
        }
        
        /* Hide any PHP errors or warnings completely */
        .php-error,
        .php-warning,
        b {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            position: absolute !important;
            left: -9999px !important;
        }
        
        /* Ensure perfect centering */
        @media (max-width: 768px) {
            .main-wrapper {
                padding: 15px;
            }
            
            .container {
                padding: 30px;
                max-width: 100%;
            }
            
            h2 {
                font-size: 24px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .form-section {
                padding: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .main-wrapper {
                padding: 10px;
            }
            
            .container {
                padding: 25px;
            }
            
            input[type="text"],
            input[type="email"],
            input[type="tel"],
            input[type="number"],
            input[type="password"],
            select,
            textarea,
            button {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="container">
            <h2>Passenger Registration</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" novalidate>
                <div class="form-section">
                    <h3>Personal Information</h3>
                    
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" 
                               id="full_name" 
                               name="full_name" 
                               placeholder="Enter your full name" 
                               required 
                               value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                        <div class="validation-info">Your complete name as per ID</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" 
                                   id="age" 
                                   name="age" 
                                   placeholder="Enter your age" 
                                   required 
                                   min="16" 
                                   max="99"
                                   value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>">
                            <div class="validation-info">Must be 16 or older</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                            <div class="validation-info">Required for booking</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Contact Information</h3>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               placeholder="Enter your email address" 
                               required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <div class="validation-info">Used for login and booking confirmations</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="tel" 
                               id="contact" 
                               name="contact" 
                               placeholder="Enter your 10-digit mobile number" 
                               required 
                               maxlength="10"
                               value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>">
                        <div class="validation-info">10-digit Indian mobile number (starting with 6-9)</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" 
                                  name="address" 
                                  placeholder="Enter your complete address" 
                                  required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                        <div class="validation-info">Complete address for pickup/drop services</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" 
                               id="city" 
                               name="city" 
                               placeholder="Enter your city" 
                               required 
                               value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                        <div class="validation-info">City where you frequently travel</div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Emergency Contact</h3>
                    
                    <div class="form-group">
                        <label for="emergency_name">Emergency Contact Name</label>
                        <input type="text" 
                               id="emergency_name" 
                               name="emergency_name" 
                               placeholder="Enter emergency contact name" 
                               required 
                               value="<?php echo isset($_POST['emergency_name']) ? htmlspecialchars($_POST['emergency_name']) : ''; ?>">
                        <div class="validation-info">Name of person to contact in case of emergency</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="emergency_contact">Emergency Contact Number</label>
                        <input type="tel" 
                               id="emergency_contact" 
                               name="emergency_contact" 
                               placeholder="Enter emergency contact number" 
                               required 
                               maxlength="10"
                               value="<?php echo isset($_POST['emergency_contact']) ? htmlspecialchars($_POST['emergency_contact']) : ''; ?>">
                        <div class="validation-info">10-digit mobile number of emergency contact</div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Account Security</h3>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Create a strong password" 
                                   required>
                            <span class="toggle-eye" onclick="togglePassword('password', this)">👁️</span>
                        </div>
                        <div class="validation-info">7-12 characters with uppercase, lowercase, number & special character</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   placeholder="Confirm your password" 
                                   required>
                            <span class="toggle-eye" onclick="togglePassword('confirm_password', this)">👁️</span>
                        </div>
                        <div class="validation-info">Must match the password above</div>
                    </div>
                </div>
                
                <button type="submit">Create Passenger Account</button>
                
                <div class="login-link">
                    <p>Already have an account? <a href="passengerlogin.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(id, icon) {
            const field = document.getElementById(id);
            if (field.type === "password") {
                field.type = "text";
                icon.textContent = "🙈";
            } else {
                field.type = "password";
                icon.textContent = "👁️";
            }
        }
        
        // Real-time validation feedback
        document.getElementById('contact').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            e.target.value = value;
        });
        
        document.getElementById('emergency_contact').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            e.target.value = value;
        });
        
        // Name validation (only letters and spaces)
        document.getElementById('full_name').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
            e.target.value = value;
        });
        
        document.getElementById('emergency_name').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
            e.target.value = value;
        });
        
        document.getElementById('city').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^a-zA-Z\s]/g, '');
            e.target.value = value;
        });
        
        // Age validation
        document.getElementById('age').addEventListener('input', function(e) {
            let value = parseInt(e.target.value);
            if (value < 16) {
                e.target.style.borderColor = '#d32f2f';
            } else if (value > 99) {
                e.target.style.borderColor = '#d32f2f';
            } else {
                e.target.style.borderColor = '#c8e6c9';
            }
        });
        
        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = e.target.value;
            
            if (password !== confirmPassword && confirmPassword.length > 0) {
                e.target.style.borderColor = '#d32f2f';
            } else {
                e.target.style.borderColor = '#c8e6c9';
            }
        });
        
        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        });
    </script>
</body>
</html>
<?php
// End output buffering
ob_end_flush();
?>