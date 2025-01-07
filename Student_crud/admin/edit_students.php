<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    $sql = "SELECT * FROM students WHERE id = $student_id";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $student = $result->fetch_assoc();
    } else {
        echo "Student not found.";
        exit;
    }
} else {
    echo "No student ID provided.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $roll_number = $_POST['roll_number'];
    $department_id = $_POST['department_id'];

    $sql = "UPDATE students 
            SET name = '$name', roll_number = '$roll_number', department_id = '$department_id' 
            WHERE id = $student_id";

    if ($conn->query($sql) === TRUE) {
        header('Location: manage_students.php');
        exit;
    } else {
        $error = "Error updating student: " . $conn->error;
    }
}

$departments_sql = "SELECT * FROM departments";
$departments_result = $conn->query($departments_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="css/edit_students.css">
</head>
<body>
    <div class="form-container">
        <h1>Edit Student</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label for="name">Student Name</label>
                <input type="text" name="name" id="name" value="<?php echo $student['name']; ?>" required>
            </div>

            <div class="input-group">
                <label for="roll_number">Roll Number</label>
                <input type="text" name="roll_number" id="roll_number" value="<?php echo $student['roll_number']; ?>" required>
            </div>

            <div class="input-group">
                <label for="department_id">Department</label>
                <select name="department_id" id="department_id" required>
                    <?php while ($department = $departments_result->fetch_assoc()): ?>
                        <option value="<?php echo $department['id']; ?>" <?php echo ($student['department_id'] == $department['id']) ? 'selected' : ''; ?>>
                            <?php echo $department['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="submit-btn">Update Student</button>
        </form>

        <a href="manage_students.php" class="back-btn">Back to Student Management</a>
    </div>
</body>
</html>
