<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: ../login.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$sql = "SELECT students.name, students.roll_number, departments.name AS department_name 
        FROM students 
        JOIN departments ON students.department_id = departments.id 
        WHERE students.id = $student_id";
$result = $conn->query($sql);
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</h1>
            <p><strong>Roll Number:</strong> <?php echo htmlspecialchars($student['roll_number']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($student['department_name']); ?></p>
        </div>
        <div class="links-section">
            <a href="../student/academics.php" class="dashboard-link">View Academics</a>
            <a href="../student/fees.php" class="dashboard-link">View Fees</a>
            <a href="../student/department_info.php" class="dashboard-link">Department Info</a>
            <a href="../student/logout.php" class="dashboard-link logout-link">Logout</a>
        </div>
    </div>
</body>
</html>
