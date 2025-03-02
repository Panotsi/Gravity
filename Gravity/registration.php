<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $fullname, $email, $hashed_password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location='login.html';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Gravity</title>
    <link rel="stylesheet" href="registration.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="grav">Gravity</div>
        <nav>
            <ul class="hash">
                <li><a href="index.html">Home</a></li>
                <li><a href="index.html#products">Products</a></li>
                <li><a href="index.html#about">About</a></li>
                <li><a href="index.html#contact">Contact</a></li>
            </ul>
        </nav>
        <button class="login" onclick="location.href='login.html'">Log in</button>
    </header>

    <section class="register">
        <h2 class="register-title">Create an Account</h2>
        <form class="register-form" method="POST" action="register.php">
            <input type="text" name="username" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" class="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.html">Log in</a></p>
    </section>

    <footer>
        <p>&copy; 2025 Gravity. All rights reserved.</p>
    </footer>
</body>
</html>