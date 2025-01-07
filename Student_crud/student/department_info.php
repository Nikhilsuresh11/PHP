<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: ../login.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch student's department
$sql = "SELECT departments.name AS department_name, departments.hod_name 
        FROM students 
        JOIN departments ON students.department_id = departments.id 
        WHERE students.id = $student_id";
$result = $conn->query($sql);
$department = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Info</title>
    <link rel="stylesheet" href="css/dept.css">
</head>
<body>
    <div class="department-container">
        <h1 class="department-name">Department: <?php echo htmlspecialchars($department['department_name']); ?></h1>
        <p class="hod-name"><strong>Head of Department:</strong> <?php echo htmlspecialchars($department['hod_name']); ?></p>
    </div>
</body>
</html>
