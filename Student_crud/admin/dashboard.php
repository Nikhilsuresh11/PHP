<?php
include '../includes/db.php';
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$admin_name = $_SESSION['admin_name'];

// Fetch general statistics
$student_count = $conn->query("SELECT COUNT(*) AS count FROM students")->fetch_assoc()['count'];
$course_count = $conn->query("SELECT COUNT(*) AS count FROM courses")->fetch_assoc()['count'];
$department_count = $conn->query("SELECT COUNT(*) AS count FROM departments")->fetch_assoc()['count'];

// Fetch courses grouped by department
$course_stats = $conn->query("
    SELECT departments.name AS department_name, COUNT(courses.id) AS course_count
    FROM courses
    JOIN departments ON courses.department_id = departments.id
    GROUP BY departments.name
");

// Fetch student enrollments by course
$enrollment_stats = $conn->query("
    SELECT courses.name AS course_name, COUNT(enrollments.student_id) AS student_count
    FROM enrollments
    JOIN courses ON enrollments.course_id = courses.id
    GROUP BY courses.name
");

// Fetch number of enrollments per month
$enrollment_by_month = $conn->query("
    SELECT DATE_FORMAT(enrollment_date, '%Y-%m') AS month, COUNT(*) AS enrollments_count
    FROM enrollments
    GROUP BY month
    ORDER BY month
");

// Fetch courses grouped by credits
$credits_stats = $conn->query("
    SELECT credits, COUNT(id) AS course_count
    FROM courses
    GROUP BY credits
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Styling for the button-like KPI boxes */
        .kpi-boxes {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .kpi-box {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            width: 200px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .kpi-box:hover {
            background-color: #45a049;
        }

        .kpi-box h3 {
            font-size: 1.5em;
            margin: 0;
        }

        .kpi-box p {
            font-size: 1.2em;
            margin: 10px 0 0;
        }

        .dashboard-header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }

        .dashboard-header h1 {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Welcome, <?php echo $admin_name; ?>!</h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="manage_students.php">Manage Students</a></li>
                    <li><a href="manage_courses.php">Manage Courses</a></li>
                    <li><a href="reports.php">View Reports</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main class="dashboard-content">
            <h2>Dashboard Overview</h2>

            <!-- KPI Boxes -->
            <div class="kpi-boxes">
                <div class="kpi-box">
                    <h3>Students</h3>
                    <p><?php echo $student_count; ?></p>
                </div>
                <div class="kpi-box">
                    <h3>Courses</h3>
                    <p><?php echo $course_count; ?></p>
                </div>
                <div class="kpi-box">
                    <h3>Departments</h3>
                    <p><?php echo $department_count; ?></p>
                </div>
            </div>

            <h3>Charts</h3>
            <canvas id="coursesChart" width="400" height="200"></canvas>
            <canvas id="enrollmentChart" width="400" height="200"></canvas>
            <canvas id="creditsChart" width="400" height="200"></canvas> <!-- New Chart for Courses by Credits -->
        </main>
    </div>

    <script>
        // Data for Courses by Department
        const coursesData = {
            labels: [<?php while ($row = $course_stats->fetch_assoc()) echo "'" . $row['department_name'] . "',"; ?>],
            datasets: [{
                label: 'Number of Courses',
                data: [<?php $course_stats->data_seek(0); while ($row = $course_stats->fetch_assoc()) echo $row['course_count'] . ","; ?>],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Data for Students Enrolled in Each Course
        const enrollmentData = {
            labels: [<?php while ($row = $enrollment_stats->fetch_assoc()) echo "'" . $row['course_name'] . "',"; ?>],
            datasets: [{
                label: 'Number of Students',
                data: [<?php $enrollment_stats->data_seek(0); while ($row = $enrollment_stats->fetch_assoc()) echo $row['student_count'] . ","; ?>],
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        };

        // Data for Enrollments by Month


        // Data for Courses by Credits
        const creditsData = {
            labels: [<?php while ($row = $credits_stats->fetch_assoc()) echo "'" . $row['credits'] . "',"; ?>],
            datasets: [{
                label: 'Number of Courses by Credits',
                data: [<?php $credits_stats->data_seek(0); while ($row = $credits_stats->fetch_assoc()) echo $row['course_count'] . ","; ?>],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        // Render Courses by Department Chart
        const ctx1 = document.getElementById('coursesChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: coursesData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        // Render Enrollment Chart
        const ctx2 = document.getElementById('enrollmentChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: enrollmentData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        
        // Render Credits Chart
        const ctx4 = document.getElementById('creditsChart').getContext('2d');
        new Chart(ctx4, {
            type: 'bar',
            data: creditsData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>
</html>
