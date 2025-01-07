<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: ../login.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$sql = "SELECT courses.name, courses.start_date, courses.end_date, courses.credits 
        FROM enrollments 
        JOIN courses ON enrollments.course_id = courses.id 
        WHERE enrollments.student_id = $student_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Courses</title>
    <link rel="stylesheet" href="css/academics.css">
</head>
<body>
    <div class="courses-container">
        <h1>Your Courses</h1>
        <table class="courses-table">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Credits</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($course = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['name']); ?></td>
                        <td><?php echo htmlspecialchars($course['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($course['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($course['credits']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

