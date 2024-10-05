<?php
session_start(); 

$isLoggedIn = isset($_SESSION['username']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Management System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('indeximages/img5.jpg');
            background-size: cover;
            background-position: center;
            color: #fff;
            margin: 0;
            padding: 0;
            position: relative;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
        }
        .nav-buttons {
            margin-bottom: 20px;
        }
        .nav-buttons a {
            color: #fff;
            text-decoration: none;
            margin-right: 15px;
            font-size: 18px;
            display: inline-block;
            padding: 10px 15px;
            border-radius: 5px;
            background: rgba(0, 0, 0, 0.5);
        }
        .nav-buttons a:hover {
            background: rgba(0, 0, 0, 0.8);
            text-decoration: none;
        }
        .nav-buttons i {
            margin-right: 8px;
        }
        h2 {
            text-align: center;
            margin-top: 0;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            color: #fff;
            background: #007bff;
            border-radius: 5px;
            text-decoration: none;
            font-size: 18px;
            text-align: center;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #0056b3;
        }
        .section {
            margin: 20px 0;
        }
        .section h3 {
            margin-top: 0;
            border-bottom: 2px solid #fff;
            padding-bottom: 5px;
        }
        .section p {
            font-size: 16px;
            line-height: 1.5;
        }
        .sidebar {
            margin-top: 20px;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            position: relative;
            height: 250px; 
            max-width: 100%; 
        }
        .slider {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .slider img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            object-fit: contain; 
        }
        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            font-size: 24px;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            user-select: none;
        }
        .arrow.left {
            left: 10px;
        }
        .arrow.right {
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-buttons">
            <?php if ($isLoggedIn): ?>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                <a href="profile.php"><i class="fas fa-user"></i> Back Profile</a>
            <?php endif; ?>
        </div>
        <h2>Welcome to the Tourist Management System</h2>
        
        <div class="sidebar">
            <div class="slider">
                <img src="indeximages/img1.jpg" alt="Tourist Spot 1">
                <span class="arrow left" onclick="prevImage()">&#9664;</span>
                <span class="arrow right" onclick="nextImage()">&#9654;</span>
            </div>
        </div>

        <div class="btn-container">
            <a href="register.php" class="btn">Register</a>
            <a href="login.php" class="btn">Login</a>
        </div>

        <div class="section">
            <h3><i class="fas fa-umbrella-beach"></i> Explore Destinations</h3>
            <p>Discover beautiful and exciting places to visit with our comprehensive tour management system. Whether you're looking for relaxing beaches, historic sites, or adventure tours, we have something for everyone.</p>
        </div>

        <div class="section">
            <h3><i class="fas fa-hotel"></i> Book Accommodations</h3>
            <p>Find and book the perfect place to stay for your trip. From luxury hotels to cozy guesthouses, our system offers a wide range of options to suit all preferences and budgets.</p>
        </div>

        <div class="section">
            <h3><i class="fas fa-car-side"></i> Plan Your Trip</h3>
            <p>Organize your itinerary with ease. Our system helps you plan your travel, including transportation and daily activities, to ensure you make the most of your trip.</p>
        </div>

        <div class="section">
            <h3><i class="fas fa-map-marked-alt"></i> Tourist Attractions</h3>
            <p>Explore popular tourist attractions and hidden gems. Our system provides detailed information about various attractions, including reviews and tips from fellow travelers.</p>
        </div>
    </div>

    <script>
        const images = [
            'indeximages/img1.jpg',
            'indeximages/img7.JPG',
            'indeximages/img6.jpg',
            'indeximages/img9.jpg',
            'indeximages/img5.jpg'
        ];
        let currentIndex = 0;
        
        function updateImage() {
            const imgElement = document.querySelector('.slider img');
            imgElement.src = images[currentIndex];
        }

        function prevImage() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
            updateImage();
        }

        function nextImage() {
            currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
            updateImage();
        }
        updateImage();
    </script>
</body>
</html>
