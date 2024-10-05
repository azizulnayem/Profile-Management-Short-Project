<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$sql = "SELECT * FROM tourist_packages";
$packagesResult = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $email = $_POST['email'];
        $name = $_POST['name'];

        $imagePath = $user['image']; 

        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image = $_FILES['image']['name'];
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($imageExtension), $allowedExtensions)) {
                $newFileName = md5(time() . $image) . '.' . $imageExtension;
                $uploadFileDir = 'uploads/';
                $imagePath = $uploadFileDir . $newFileName;

                if (move_uploaded_file($imageTmpPath, $imagePath)) {
                    if ($user['image'] && file_exists($user['image'])) {
                        unlink($user['image']);
                    }
                } else {
                    echo "Error moving the uploaded file.";
                    exit();
                }
            } else {
                echo "Invalid file type.";
                exit();
            }
        }

        $sql = "UPDATE users SET email = ?, name = ?, image = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $email, $name, $imagePath, $username);

        if ($stmt->execute()) {
            $user = array_merge($user, ['email' => $email, 'name' => $name, 'image' => $imagePath]);
            echo "<script>alert('Profile updated successfully.');</script>";
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    } 
    elseif (isset($_POST['delete'])) {
        $password = $_POST['password'];
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $storedPasswordHash = $stmt->get_result()->fetch_assoc()['password'];

        if (password_verify($password, $storedPasswordHash)) {
            $sql = "DELETE FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                if ($user['image'] && file_exists($user['image'])) {
                    unlink($user['image']);
                }
                session_destroy();
                header("Location: index.php");
                exit();
            } else {
                echo "Error deleting account: " . $stmt->error;
            }
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } 
    elseif (isset($_POST['remove_image'])) {
        if ($user['image'] && file_exists($user['image'])) {
            unlink($user['image']);
            $sql = "UPDATE users SET image = NULL WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                $user['image'] = null;
                echo "<script>alert('Profile image removed successfully.');</script>";
            } else {
                echo "Error removing image: " . $stmt->error;
            }
        }
    } 
    elseif (isset($_POST['order'])) {
        $packageId = $_POST['package_id'];
        echo "<script>alert('Package ordered: " . htmlspecialchars($packageId) . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
            margin: 40px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.85);
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border-radius: 6px;
            text-decoration: none;
            margin: 5px 0;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .nav-buttons {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .nav-buttons a {
            color: #fff;
            text-decoration: none;
            margin-left: 15px;
            font-size: 16px;
        }
        .nav-buttons a:hover {
            text-decoration: underline;
        }
        .nav-buttons i {
            margin-right: 5px;
        }
        .profile-section {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
        }
        .remove-image {
            position: Right;
            top: 10px;
            right: 10px;
            background: rgba(255, 0, 0, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            width: 30px;
            height: 30px;
            text-align: center;
            line-height: 28px;
            font-size: 20px;
            transition: background 0.3s ease;
        }
        .remove-image:hover {
            background: rgba(255, 0, 0, 1);
        }
        form {
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        .form-group {
            max-width: 400px;
            margin: 0 auto 15px auto;
        }
        input[type="email"], input[type="text"], input[type="file"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            transition: box-shadow 0.3s ease;
        }
        input[type="email"]:focus, input[type="text"]:focus, input[type="file"]:focus, input[type="password"]:focus {
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
            outline: none;
        }
        .update-btn {
            background-color: #28a745;
        }
        .update-btn:hover {
            background-color: #218838;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .package-card {
            background: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 8px;
            color: black;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .package-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }
        .package-card img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .order-btn {
            background-color: #28a745;
        }
        .order-btn:hover {
            background-color: #218838;
        }
        .packages {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 400px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.95);
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }
        .popup form {
            display: flex;
            flex-direction: column;
        }
        .popup button {
            padding: 10px;
            border-radius: 6px;
            border: none;
            color: #fff;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
        }
        .popup button.close {
            background-color: #6c757d;
        }
        .popup button.delete {
            background-color: #dc3545;
        }
        .popup button.close:hover {
            background-color: #5a6268;
        }
        .popup button.delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
    <a href="index.php" style="color: #4CAF50; background: rgba(0, 0, 0, 0.5); border: 2px solid #4CAF50; font-weight: bold; padding: 12px 20px; border-radius: 8px; text-decoration: none; transition: background 0.3s, color 0.3s, border-color 0.3s;">
    <i class="fas fa-home"></i> Home</a>
        <div class="nav-buttons">
            <a href="search.php" class="btn search-btn"><i class="fas fa-search"></i> Search Users</a>
            <a href="logout.php" class="btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <h2 style="text-align: center; margin-bottom: 20px;">Profile</h2>
        <div class="profile-section">
            <?php if (isset($user['image']) && $user['image']): ?>
                <img src="<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Image" class="profile-image">
                <form action="profile.php" method="POST">
                    <button type="submit" name="remove_image" class="remove-image" title="Remove Profile Image">&times;</button>
                </form>
            <?php else: ?>
                <img src="default-profile.png" alt="Default Profile Image" class="profile-image">
            <?php endif; ?>
        </div>
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Profile Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <div style="text-align: center;">
                <button type="submit" name="update" class="btn update-btn"><i class="fas fa-user-edit"></i> Update Profile</button>
            </div>
        </form>
        <div style="text-align: center;">
            <button onclick="showPasswordPrompt()" class="btn delete-btn"><i class="fas fa-user-times"></i> Delete Account</button>
        </div>

        <h3 style="text-align: center; margin: 30px 0 20px 0;">Popular Packages</h3>
        <div class="packages">
            <?php if ($packagesResult->num_rows > 0): ?>
                <?php while ($package = $packagesResult->fetch_assoc()): ?>
                    <div class="package-card">
                        <?php if (isset($package['image']) && $package['image']): ?>
                            <img src="<?php echo htmlspecialchars($package['image']); ?>" alt="Package Image">
                        <?php else: ?>
                            <img src="default-package.png" alt="Default Package Image">
                        <?php endif; ?>
                        <h4><?php echo htmlspecialchars($package['name']); ?></h4>
                        <p><?php echo htmlspecialchars($package['description']); ?></p>
                        <p><strong>Price:</strong> <?php echo htmlspecialchars($package['price']); ?> TK.</p>
                        <form action="profile.php" method="POST">
                            <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($package['id']); ?>">
                            <button type="submit" name="order" class="btn order-btn"><i class="fas fa-cart-plus"></i> Order</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center;">No packages available.</p>
            <?php endif; ?>
        </div>
        <div class="popup" id="passwordPrompt">
            <form action="profile.php" method="POST">
                <label for="password">Confirm Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" name="delete" class="btn delete">Confirm & Delete</button>
                <button type="button" class="btn close" onclick="closePasswordPrompt()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function showPasswordPrompt() {
            document.getElementById('passwordPrompt').style.display = 'block';
        }

        function closePasswordPrompt() {
            document.getElementById('passwordPrompt').style.display = 'none';
        }
    </script>
</body>
</html>
