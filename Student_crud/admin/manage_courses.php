<?php 
include '../includes/db.php';
session_start();

// Ensure admin authentication
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// Fetch courses from the database
$sql = "SELECT courses.id, courses.name, courses.start_date, courses.end_date, courses.credits, departments.name AS department_name 
        FROM courses
        JOIN departments ON courses.department_id = departments.id";
$result = $conn->query($sql);

// Update course logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_course'])) {
    $course_id = $_POST['course_id'];
    $name = $_POST['name'];
    $department_id = $_POST['department_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $credits = $_POST['credits'];

    // Update course details in the database
    $update_sql = "UPDATE courses SET 
                    name = ?, 
                    department_id = ?, 
                    start_date = ?, 
                    end_date = ?, 
                    credits = ? 
                   WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('sisssi', $name, $department_id, $start_date, $end_date, $credits, $course_id);
    if ($stmt->execute()) {
        $message = "Course updated successfully!";
    } else {
        $message = "Error updating course.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="css/manage_courses.css">
</head>
<body>
    <div class="container">
        <h1>Manage Courses</h1>

        <!-- Display success or error message -->
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Display courses in a table -->
        <h2>Courses List</h2>
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Department</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Credits</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['department_name']; ?></td>
                        <td><?php echo $row['start_date']; ?></td>
                        <td><?php echo $row['end_date']; ?></td>
                        <td><?php echo $row['credits']; ?></td>
                        <td>
                            <!-- Button to open edit form -->
                            <a href="edit_courses.php?id=<?php echo $row['id']; ?>">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
