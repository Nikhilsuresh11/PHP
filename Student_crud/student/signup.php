<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $roll_number = $_POST['roll_number'];
    $department_id = $_POST['department_id'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO students (name, roll_number, department_id, email, password) 
            VALUES ('$name', '$roll_number', '$department_id', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <!-- Admin Button -->
    <div class="admin-button">
        <a href="../admin/login.php">Admin Login</a>
    </div>

    <div class="form-container">
        <h1>Student Registration</h1>
        <form method="POST">
            <div class="input-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" placeholder="Full Name" required>
            </div>
            <div class="input-group">
                <label for="roll_number">Roll Number</label>
                <input type="text" name="roll_number" id="roll_number" placeholder="Roll Number" required>
            </div>
            <div class="input-group">
                <label for="department_id">Department</label>
                <select name="department_id" id="department_id" required>
                    <option value="">Select Department</option>
                    <option value="1">Data Science</option>
                    <option value="2">AIML</option>
                    <option value="3">Decision and Computing Science</option>
                    <option value="4">Software Systems</option>
                </select>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <button type="submit" class="submit-btn">Register</button>
        </form>
        <div class="existing-user">
            <p>Already have an account?</p>
            <a href="login.php" class="login-btn">Login Here</a>
        </div>
    </div>
</body>
</html>
