<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$department_filter = isset($_GET['department_filter']) ? $_GET['department_filter'] : '';
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

$sql = "SELECT students.id, students.name, students.roll_number, departments.name AS department_name 
        FROM students 
        JOIN departments ON students.department_id = departments.id";

if ($department_filter || $search_query) {
    $sql .= " WHERE 1";

    if ($department_filter) {
        $sql .= " AND departments.name LIKE '%$department_filter%'";
    }

    if ($search_query) {
        $sql .= " AND (students.name LIKE '%$search_query%' OR students.roll_number LIKE '%$search_query%')";
    }
}

$result = $conn->query($sql);

$departments_result = $conn->query("SELECT * FROM departments");

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM students WHERE id = $delete_id");
    header('Location: manage_students.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link rel="stylesheet" href="css/manage_students.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Manage Students</h1>
            <a href="../admin/add_students.php" class="add-btn">Add New Student</a>
        </header>
        
        <div class="filters-container">
            <!-- Search Bar -->
            <form action="" method="get" class="search-form">
                <input type="text" name="search_query" placeholder="Search by Name or Roll Number" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>

            <form action="" method="get" class="department-filter-form">
                <select name="department_filter" onchange="this.form.submit()">
                    <option value="">Select Department</option>
                    <?php while ($department = $departments_result->fetch_assoc()): ?>
                        <option value="<?php echo $department['name']; ?>" <?php echo $department['name'] == $department_filter ? 'selected' : ''; ?>>
                            <?php echo $department['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Roll Number</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($student = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $student['id']; ?></td>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['roll_number']; ?></td>
                            <td><?php echo $student['department_name']; ?></td>
                            <td>
                                <a href="edit_students.php?id=<?php echo $student['id']; ?>" class="edit-btn">Edit</a>
                                <a href="?delete_id=<?php echo $student['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
