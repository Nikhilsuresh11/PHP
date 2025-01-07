<?php 
include '../includes/db.php';
session_start();

// Ensure admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// Fetch department-wise report data
$department_sql = "SELECT departments.name AS department_name, 
                          COUNT(students.id) AS student_count 
                   FROM departments 
                   LEFT JOIN students ON departments.id = students.department_id 
                   GROUP BY departments.id";
$department_result = $conn->query($department_sql);

// Fetch course-wise report data
$course_sql = "SELECT courses.name AS course_name, 
                       COUNT(enrollments.student_id) AS enrollment_count
                FROM courses
                LEFT JOIN enrollments ON courses.id = enrollments.course_id
                GROUP BY courses.id";
$course_result = $conn->query($course_sql);

// Fetch enrollment-wise report data
$enrollment_sql = "SELECT courses.name AS course_name, 
                          COUNT(enrollments.id) AS total_enrollments
                   FROM enrollments
                   JOIN courses ON enrollments.course_id = courses.id
                   GROUP BY courses.id";
$enrollment_result = $conn->query($enrollment_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="css/reports.css">
</head>
<body>
    <div class="container">
        <h1>Reports</h1>

        <!-- Department-wise Report -->
        <section>
            <h2>Department-wise Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Number of Students</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($report = $department_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $report['department_name']; ?></td>
                            <td><?php echo $report['student_count']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Course-wise Report -->
        <section>
            <h2>Course-wise Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Number of Students Enrolled</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($report = $course_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $report['course_name']; ?></td>
                            <td><?php echo $report['enrollment_count']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Enrollment-wise Report -->
        <section>
            <h2>Enrollment-wise Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Total Enrollments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($report = $enrollment_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $report['course_name']; ?></td>
                            <td><?php echo $report['total_enrollments']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

    </div>
</body>
</html>
