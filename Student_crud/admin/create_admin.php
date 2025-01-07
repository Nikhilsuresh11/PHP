<?php
include '../includes/db.php';

$name = 'Admin';
$email = 'admin@example.com';
$password = password_hash('admin123', PASSWORD_DEFAULT); // Hash the password

// Insert the admin user with the hashed password
$sql = "INSERT INTO admins (name, email, password) VALUES ('$name', '$email', '$password')";
if ($conn->query($sql) === TRUE) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
