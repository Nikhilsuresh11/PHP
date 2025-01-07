<?php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $roll_number = $_POST['roll_number'];
    $department_id = $_POST['department_id'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO students (name, roll_number, department_id, email, password) 
            VALUES ('$name', '$roll_number', '$department_id', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        header('Location: manage_students.php');
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
    <title>Add Student</title>
    <link rel="stylesheet" href="css/add_students.css">
</head>
<body>
    <div class="form-container">
        <h1>Add New Student</h1>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>
            <div class="input-group">
                <input type="text" name="roll_number" placeholder="Roll Number" required>
            </div>
            <div class="input-group">
                <select name="department_id" required>
                    <option value="">Select Department</option>
                    <?php
                    $departments = $conn->query("SELECT id, name FROM departments");
                    while ($department = $departments->fetch_assoc()) {
                        echo "<option value='{$department['id']}'>{$department['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="submit-btn">Add Student</button>
        </form>
    </div>
</body>
</html>
