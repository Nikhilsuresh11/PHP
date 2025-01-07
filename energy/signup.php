<?php
session_start();
include 'db.php'; // Include the database connection file

// Function to validate input
function validateInput($username, $email, $password) {
    // Username: Alphanumeric and underscores, 3-15 characters
    if (!preg_match('/^[a-zA-Z0-9_]{3,15}$/', $username)) {
        return "Username must be 3-15 characters long and can only contain letters, numbers, and underscores.";
    }
    
    // Email: Valid email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }
    
    // Password: At least 8 characters, at least one uppercase letter, one lowercase letter, one number, and one special character
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        return "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    return true; // All validations passed
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle signup
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate input
    $validationResult = validateInput($username, $email, $password);
    
    if ($validationResult !== true) {
        echo $validationResult; // Output validation error
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows == 0) {
            // Insert new user into database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            if ($stmt->execute()) {
                echo "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                echo "Error: Could not register user.";
            }
        } else {
            echo "Username or email already exists.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up</h2>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>
