<?php
session_start();
include 'db.php'; // Ensure this includes $pdo

if (!$pdo) {
    die("Database connection not established.");
}

// Function to validate input
function validateInput($username, $email, $password) {
    if (!preg_match('/^[a-zA-Z0-9_]{3,15}$/', $username)) {
        return "Username must be 3-15 characters long and can only contain letters, numbers, and underscores.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        return "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        if (empty($username) || empty($password)) {
            echo "Username and password cannot be empty.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid username or password.";
            }
        }
    } elseif (isset($_POST['signup'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $validationResult = validateInput($username, $email, $password);
        if ($validationResult !== true) {
            echo htmlspecialchars($validationResult);
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->rowCount() == 0) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$username, $email, $hashedPassword])) {
                    echo "Registration successful!";
                } else {
                    echo "Error: Could not register user.";
                }
            } else {
                echo "Username or email already exists.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <link rel="stylesheet" href="css/login.css">
    <script>
        function showForm(formId) {
            document.getElementById('loginForm').classList.remove('active');
            document.getElementById('signupForm').classList.remove('active');
            document.getElementById(formId).classList.add('active');
        }
    </script>
</head>
<body>
    <div class="container">
        <div id="loginForm" class="form-container active ">
            <h2>Login</h2>
            <form method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
                <label for="password">Password:</label>
                <input type="password" name="password" required>
                <button type="submit" name="login">Login</button>
            </form>
            <div class="center-text">
                <p>Don't have an account? <button onclick="showForm('signupForm')" class="link-button">Sign Up</button></p>
            </div>
        </div>

        <div id="signupForm" class="form-container ">
            <h2>Sign Up</h2>
            <form method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
                <label for="email">Email:</label>
                <input type="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" name="password" required>
                <button type="submit" name="signup">Sign Up</button>
            </form>
            <div class="center-text">
                <p>Already have an account? <button onclick="showForm('loginForm')" class="link-button">Login</button></p>
            </div>
        </div>  
    </div>
</body>
</html>
