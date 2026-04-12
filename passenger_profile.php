<?php 
session_start();

// Check if user is logged in using email or full_name instead of user_id
if (!isset($_SESSION['email']) && !isset($_SESSION['full_name'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require 'db_connect.php';

// Use email or full_name as identifier instead of user_id
$identifier = isset($_SESSION['email']) ? $_SESSION['email'] : $_SESSION['full_name'];
$identifier_field = isset($_SESSION['email']) ? 'email' : 'full_name';

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $upload_dir = 'uploads/profile_pictures/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array(strtolower($file_extension), $allowed_extensions)) {
        $new_filename = 'profile_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
        
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
            // Update database with new profile picture path using email or full_name
            $update_query = "UPDATE passengers SET profile_picture = ? WHERE $identifier_field = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ss", $upload_path, $identifier);
            $update_stmt->execute();
            $update_stmt->close();
            
            $upload_success = true;
        } else {
            $upload_error = "Failed to upload image.";
        }
    } else {
        $upload_error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
}

// Fetch passenger data using email or full_name instead of user_id
$query = "SELECT full_name, email, contact, profile_picture FROM passengers WHERE $identifier_field = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$result = $stmt->get_result();
$passenger = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            color: #ecf0f1;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background: linear-gradient(135deg, #1a252f 0%, #2c3e50 100%);
            padding: 30px 0;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 2.5em;
            font-weight: 300;
            letter-spacing: 2px;
            color: #3498db;
        }

        .profile-card {
            background: rgba(52, 73, 94, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
        }

        .profile-picture-container {
            position: relative;
            width: 150px;
            height: 150px;
            flex-shrink: 0;
        }

        .profile-picture {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3498db;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .default-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3em;
            color: white;
            border: 4px solid #3498db;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .upload-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            background: #2980b9;
            transform: scale(1.1);
        }

        .file-input {
            display: none;
        }

        .profile-info {
            flex: 1;
        }

        .profile-info h2 {
            color: #3498db;
            margin-bottom: 20px;
            font-size: 1.8em;
            font-weight: 300;
        }

        .info-item {
            background: rgba(44, 62, 80, 0.6);
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #3498db;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: rgba(44, 62, 80, 0.8);
            transform: translateX(5px);
        }

        .info-label {
            font-size: 0.9em;
            color: #bdc3c7;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .info-value {
            font-size: 1.1em;
            color: #ecf0f1;
            font-weight: 300;
        }

        .driver-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1.1em;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .driver-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .drivers-list {
            display: none;
            margin-top: 20px;
        }

        .drivers-list.active {
            display: block;
        }

        .driver-item {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid rgba(231, 76, 60, 0.3);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .driver-item:hover {
            background: rgba(231, 76, 60, 0.2);
            transform: translateX(5px);
        }

        .driver-name {
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 5px;
        }

        .driver-contact {
            color: #bdc3c7;
            font-size: 0.9em;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .alert-success {
            background: rgba(39, 174, 96, 0.2);
            border: 1px solid #27ae60;
            color: #2ecc71;
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }

        .no-drivers {
            text-align: center;
            color: #bdc3c7;
            font-style: italic;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-picture-container {
                width: 120px;
                height: 120px;
            }
            
            .container {
                padding: 15px;
            }
            
            .profile-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1><i class="fas fa-user-circle"></i> MY PROFILE</h1>
    </header>

    <div class="container">
        <?php if (isset($upload_success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Profile picture updated successfully!
            </div>
        <?php endif; ?>

        <?php if (isset($upload_error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $upload_error; ?>
            </div>
        <?php endif; ?>

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-picture-container">
                    <?php if (!empty($passenger['profile_picture']) && file_exists($passenger['profile_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($passenger['profile_picture']); ?>" 
                             alt="Profile Picture" class="profile-picture">
                    <?php else: ?>
                        <div class="default-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data" style="display: inline;">
                        <input type="file" name="profile_picture" class="file-input" 
                               accept="image/*" onchange="this.form.submit()">
                        <button type="button" class="upload-btn" onclick="document.querySelector('.file-input').click()">
                            <i class="fas fa-camera"></i>
                        </button>
                    </form>
                </div>

                <div class="profile-info">
                    <h2><i class="fas fa-info-circle"></i> Personal Information</h2>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user"></i> Full Name
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($passenger['full_name']); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-phone"></i> Contact
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($passenger['contact']); ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i> Email
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($passenger['email']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Profile picture upload functionality
        document.querySelector('.file-input').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</body>
</html>