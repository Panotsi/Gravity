<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location='registration.php';</script>";
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