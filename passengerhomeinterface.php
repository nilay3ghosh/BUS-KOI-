<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VRAMAN - Passenger Portal</title>
  <link rel="stylesheet" href="style.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Roboto:wght@300;400;500;700&display=swap');
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(135deg, #0d1421 0%, #1a2332 25%, #2c3e50 50%, #34495e 75%, #2c3e50 100%);
      min-height: 100vh;
      color: #ecf0f1;
      overflow-x: hidden;
    }
    
    /* Animated background elements */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 20% 80%, rgba(231, 76, 60, 0.1) 0%, transparent 50%),
                  radial-gradient(circle at 80% 20%, rgba(52, 152, 219, 0.1) 0%, transparent 50%),
                  radial-gradient(circle at 40% 40%, rgba(241, 196, 15, 0.05) 0%, transparent 50%);
      z-index: -1;
      animation: backgroundMove 20s ease-in-out infinite;
    }
    
    @keyframes backgroundMove {
      0%, 100% { transform: translateX(0) translateY(0); }
      25% { transform: translateX(10px) translateY(-10px); }
      50% { transform: translateX(-5px) translateY(5px); }
      75% { transform: translateX(5px) translateY(-5px); }
    }
    
    header {
      background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
      color: #ecf0f1;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 30px;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      border-bottom: 3px solid #e74c3c;
    }
    
    .logo h1 {
      font-family: 'Orbitron', monospace;
      font-size: 2.2em;
      font-weight: 900;
      background: linear-gradient(135deg, #e74c3c, #f39c12, #e74c3c);
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 0 20px rgba(231, 76, 60, 0.5);
      letter-spacing: 3px;
      animation: glow 3s ease-in-out infinite alternate;
    }
    
    @keyframes glow {
      from { text-shadow: 0 0 20px rgba(231, 76, 60, 0.5); }
      to { text-shadow: 0 0 30px rgba(231, 76, 60, 0.8), 0 0 40px rgba(231, 76, 60, 0.6); }
    }
    
    .menu-header {
      display: flex;
      align-items: center;
      margin-right: 20px;
    }
    
    .menu-button {
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      border: none;
      padding: 15px 18px;
      cursor: pointer;
      position: relative;
      border-radius: 8px;
      box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
      color: white;
      font-size: 1.2em;
      font-weight: bold;
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }
    
    .menu-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(231, 76, 60, 0.6);
      border-color: #f39c12;
    }
    
    .menu-options {
      position: absolute;
      top: 100%;
      right: 0;
      background: linear-gradient(135deg, #2c3e50, #34495e);
      border: 2px solid #e74c3c;
      padding: 15px;
      display: none;
      border-radius: 12px;
      width: 200px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
      margin-top: 10px;
    }
    
    .menu-options a {
      display: block;
      padding: 12px 15px;
      text-decoration: none;
      color: #ecf0f1;
      font-weight: 500;
      border-radius: 6px;
      transition: all 0.3s ease;
      margin: 5px 0;
      border-left: 3px solid transparent;
    }
    
    .menu-options a:hover {
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      transform: translateX(5px);
      border-left-color: #f39c12;
      box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
    }
    
    .banner {
      width: 100%;
      height: 500px;
      background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)),
                  url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(231,76,60,0.1)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
      background-size: cover, 50px 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      margin-top: 100px;
      position: relative;
      overflow: hidden;
    }
    
    .banner::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, 
        rgba(231, 76, 60, 0.1) 0%, 
        rgba(52, 152, 219, 0.1) 25%, 
        rgba(241, 196, 15, 0.1) 50%, 
        rgba(46, 204, 113, 0.1) 75%, 
        rgba(155, 89, 182, 0.1) 100%);
      animation: colorShift 8s ease-in-out infinite;
    }
    
    @keyframes colorShift {
      0%, 100% { opacity: 0.3; }
      25% { opacity: 0.5; }
      50% { opacity: 0.4; }
      75% { opacity: 0.6; }
    }
    
    .banner-content {
      text-align: center;
      z-index: 2;
      position: relative;
    }
    
    .banner h1 {
      font-family: 'Orbitron', monospace;
      font-size: 4.5em;
      font-weight: 900;
      background: linear-gradient(135deg, #e74c3c, #f39c12, #e67e22, #e74c3c);
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 0 30px rgba(231, 76, 60, 0.8);
      letter-spacing: 8px;
      margin-bottom: 20px;
      animation: titlePulse 4s ease-in-out infinite;
      line-height: 1.2;
    }
    
    @keyframes titlePulse {
      0%, 100% { 
        transform: scale(1);
        text-shadow: 0 0 30px rgba(231, 76, 60, 0.8);
      }
      50% { 
        transform: scale(1.05);
        text-shadow: 0 0 40px rgba(231, 76, 60, 1), 0 0 60px rgba(241, 196, 15, 0.6);
      }
    }
    
    .banner p {
      font-size: 1.4em;
      font-weight: 300;
      color: #bdc3c7;
      letter-spacing: 2px;
      margin-top: 10px;
    }
    
    .passenger-options {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 80px 20px;
      gap: 40px;
      flex-wrap: wrap;
    }
    
    .passenger-options a {
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      color: white;
      padding: 25px 40px;
      font-size: 1.6em;
      font-weight: 700;
      text-decoration: none;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 2px;
      border: 3px solid transparent;
      position: relative;
      overflow: hidden;
      min-width: 250px;
      text-align: center;
    }
    
    .passenger-options a::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }
    
    .passenger-options a:hover::before {
      left: 100%;
    }
    
    .passenger-options a:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 12px 35px rgba(231, 76, 60, 0.6);
      border-color: #f39c12;
      background: linear-gradient(135deg, #c0392b, #e74c3c);
    }
    
    .passenger-options a:active {
      transform: translateY(-2px) scale(1.02);
    }
    
    footer {
      background: linear-gradient(135deg, #2c3e50, #34495e);
      color: #ecf0f1;
      text-align: center;
      padding: 40px 20px;
      margin-top: 60px;
      border-top: 3px solid #e74c3c;
      box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
    }
    
    footer p {
      margin: 10px 0;
      font-size: 1.1em;
      font-weight: 300;
    }
    
    footer p:first-child {
      font-weight: 500;
      color: #f39c12;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
      .logo h1 {
        font-size: 1.8em;
        letter-spacing: 2px;
      }
      
      .banner h1 {
        font-size: 3em;
        letter-spacing: 4px;
      }
      
      .banner p {
        font-size: 1.2em;
      }
      
      .passenger-options {
        flex-direction: column;
        gap: 25px;
        padding: 60px 20px;
      }
      
      .passenger-options a {
        font-size: 1.4em;
        padding: 20px 30px;
        min-width: 200px;
      }
      
      header {
        padding: 15px 20px;
      }
      
      .menu-options {
        width: 180px;
      }
    }
    
    @media (max-width: 480px) {
      .banner h1 {
        font-size: 2.5em;
        letter-spacing: 3px;
      }
      
      .banner {
        height: 400px;
      }
      
      .passenger-options a {
        font-size: 1.3em;
        padding: 18px 25px;
      }
    }
    
    /* Additional masculine touches */
    .menu-button:active {
      transform: translateY(1px);
    }
    
    .banner-content::after {
      content: '';
      position: absolute;
      bottom: -20px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 4px;
      background: linear-gradient(90deg, transparent, #e74c3c, transparent);
      animation: underlineGlow 3s ease-in-out infinite;
    }
    
    @keyframes underlineGlow {
      0%, 100% { box-shadow: 0 0 10px rgba(231, 76, 60, 0.5); }
      50% { box-shadow: 0 0 20px rgba(231, 76, 60, 1); }
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <h1>VRAMAN</h1>
    </div>
    <div class="menu-header">
      <button class="menu-button" id="menu-button">☰</button>
      <div class="menu-options" id="menu-options">
        <a href="profile.php">Profile</a>
        <a href="history.php">Ride History</a>
        <a href="searchbus.php">Search Bus</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </header>

  <div class="banner">
    <div class="banner-content">
      <h1>WELCOME TO VRAMAN</h1>
      <p>Your Ultimate Journey Begins Here</p>
    </div>
  </div>

  <div class="passenger-options">
    <a href="searchbus.php">Search Bus</a>
  </div>

  <footer>
    <p>Contact us: +1 123 456 7890 | Email: contact@vraman.com</p>
    <p>&copy; 2025 VRAMAN. All rights reserved.</p>
  </footer>

  <script>
    const menuButton = document.getElementById('menu-button');
    const menuOptions = document.getElementById('menu-options');
    
    menuButton.addEventListener('click', () => {
      menuOptions.style.display = menuOptions.style.display === 'block' ? 'none' : 'block';
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
      if (!menuButton.contains(e.target) && !menuOptions.contains(e.target)) {
        menuOptions.style.display = 'none';
      }
    });
    
    // Add some interactive effects
    document.addEventListener('DOMContentLoaded', function() {
      const title = document.querySelector('.banner h1');
      const letters = title.textContent.split('');
      title.innerHTML = letters.map(letter => 
        letter === ' ' ? ' ' : `<span class="letter">${letter}</span>`
      ).join('');
    });
  </script>
</body>
</html>