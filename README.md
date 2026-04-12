🚌 Bus Booking System Web Application
📌 Project Overview

The Bus Booking System is a web-based application designed to simplify bus ticket booking and management.
It allows passengers to search buses, book tickets, and manage bookings, while bus owners can add buses and manage services.

This project is developed using PHP, HTML, CSS, and MySQL.

🎯 Objectives
Provide an easy platform for passengers to book bus tickets.
Allow bus owners to manage bus information.
Maintain booking history and cancellation records.
Improve efficiency compared to manual booking systems.
🛠️ Technologies Used
Frontend
HTML5
CSS3
Backend
PHP
Database
MySQL
Server
XAMPP / WAMP / Localhost
📂 Project File Structure
Bus-Booking-System/
│
├── index.php                      # Homepage
├── addbus.php                     # Add bus details (Owner)
├── busdetails.php                 # View bus details
├── cancelation.php                # Cancel booking
├── db_connect.php                 # Database connection file
├── history.php                    # Booking history
├── ownerhomeinterface.php         # Owner dashboard
├── ownerlogin.php                 # Owner login
├── ownersignup.php                # Owner registration
├── passengerhomeinterface.php     # Passenger dashboard
├── passengerlogin.php             # Passenger login
├── passengersignup.php            # Passenger registration
├── profile.php                    # User profile
├── searchbus.php                  # Search available buses
├── banner.jpg                     # Website banner image
└── README.md                      # Project documentation
👥 User Roles
🧑 Passenger

Passengers can:

Register an account
Login to the system
Search buses
Book tickets
Cancel bookings
View booking history
Manage profile
🧑‍💼 Bus Owner

Owners can:

Register account
Login
Add bus details
Manage bus services
View passenger bookings
⚙️ Installation Guide

Follow these steps to run the project locally:

Step 1: Install XAMPP/WAMP

Download and install:

XAMPP (Recommended)
Step 2: Copy Project Files

Place the project folder inside:

htdocs (for XAMPP)

OR

www (for WAMP)
Step 3: Create Database
Open phpMyAdmin
Create a new database:
bus_booking
Import the SQL file (if available).
Step 4: Configure Database Connection

Open:

db_connect.php

Update database settings:

$conn = mysqli_connect("localhost", "root", "", "bus_booking");
Step 5: Run the Project

Open browser and visit:

http://localhost/Bus-Booking-System/index.php
🔍 System Features

✔ Passenger Signup & Login
✔ Owner Signup & Login
✔ Add Bus Details
✔ Search Bus
✔ Booking History
✔ Cancel Booking
✔ Profile Management
✔ Dashboard Interfaces

🧪 Testing

Test the following:

Login authentication
Bus searching
Booking process
Cancellation process
Database connection
📸 Screens (Optional)

You can add screenshots here:

Homepage
Login Page
Bus Search Page
Booking History

Example:

![Homepage](screenshots/home.png)
🚀 Future Enhancements

Possible improvements:

Online payment integration
Seat selection system
Email/SMS notifications
Admin panel
Mobile responsive UI
Real-time bus tracking
🔐 Security Improvements (Recommended)
Password hashing
SQL injection prevention
Session management
Input validation
📚 Learning Outcomes

Through this project, you can learn:

Web development using PHP
Database integration with MySQL
User authentication systems
CRUD operations
Frontend and backend integration
👨‍💻 Author

Nilay Ghosh
Final Year Student
Computer Science & Engineering
Modern Institute of Engineering & Technology

📜 License

This project is developed for educational purposes only.