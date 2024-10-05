<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$search = '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
    $search = $_GET['search'];
    
    $sql = "SELECT username, email, name, image FROM users WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("s", $searchTerm);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        
        $stmt->close();
    } else {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Users</title>
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
        }
        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 12px;
            text-align: center;
        }
        .nav-buttons {
            margin-bottom: 20px;
        }
        .nav-buttons a {
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
            font-size: 16px;
            transition: color 0.3s ease;
        }
        .nav-buttons a:hover {
            color: #007bff;
        }
        .nav-buttons i {
            margin-right: 5px;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
        }
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 300px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 16px;
            outline: none;
            margin-right: 10px;
        }
        button {
            padding: 10px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        button:hover {
            background-color: #0056b3;
        }
        .search-results {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        .card {
            background: rgba(0, 0, 0, 0.8);
            border-radius: 12px;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            display: flex;
            align-items: center;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: left;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.6);
        }
        .card img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid #007bff;
        }
        .card div {
            flex: 1;
        }
        .card p {
            margin: 8px 0;
        }
        .card p strong {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-buttons">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="profile.php"><i class="fas fa-arrow-left"></i> Go Back</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <h2>Search Users</h2>
        <form action="search.php" method="GET">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>
        <div class="search-results">
            <?php if (count($results) > 0): ?>
                <?php foreach ($results as $row): ?>
                    <div class="card">
                        <?php 
                        $imagePath = '' . htmlspecialchars($row['image']);
                        $defaultImage = 'default-profile.png';
                        ?>
                        <img src="<?php echo file_exists($imagePath) ? $imagePath : $defaultImage; ?>" alt="Profile Image">
                        <div>
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($row['username']); ?></p>
                            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
