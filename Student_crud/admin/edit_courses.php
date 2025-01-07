<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$course_id = $_GET['id'];

$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $course_id);
$stmt->execute();
$course_result = $stmt->get_result();

if ($course_result->num_rows == 0) {
    die("Course not found.");
}

$course = $course_result->fetch_assoc();

$department_sql = "SELECT * FROM departments";
$department_result = $conn->query($department_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_course'])) {
    $name = $_POST['name'];
    $department_id = $_POST['department_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $credits = $_POST['credits'];

    // Update course details
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
    <title>Edit Course</title>
    <link rel="stylesheet" href="css/manage_courses.css">
</head>
<body>
    <div class="container">
        <h1>Edit Course</h1>

        <!-- Display success or error message -->
        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">

            <div class="form-group">
                <label for="name">Course Name</label>
                <input type="text" id="name" name="name" value="<?php echo $course['name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="department_id">Department</label>
                <select id="department_id" name="department_id" required>
                    <?php while ($department = $department_result->fetch_assoc()): ?>
                        <option value="<?php echo $department['id']; ?>" <?php echo $department['id'] == $course['department_id'] ? 'selected' : ''; ?>>
                            <?php echo $department['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo $course['start_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo $course['end_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="credits">Credits</label>
                <input type="number" id="credits" name="credits" value="<?php echo $course['credits']; ?>" required>
            </div>

            <button type="submit" name="update_course">Update Course</button>
        </form>
    </div>
</body>
</html>
